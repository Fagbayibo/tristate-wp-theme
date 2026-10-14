<?php
/**
 * Appointment requests: stored as a private post type (Dashboard → Appointments)
 * and emailed to the clinic. Works with and without JavaScript.
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

/** Patient types offered in the booking form. */
function tristate_patient_types() {
	return array( 'New patient', 'Returning patient' );
}

add_action( 'init', function () {
	register_post_type( 'tristate_appointment', array(
		'labels'          => array(
			'name'          => __( 'Appointments', 'tristate' ),
			'singular_name' => __( 'Appointment', 'tristate' ),
		),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => true,
		'menu_icon'       => 'dashicons-calendar-alt',
		'menu_position'   => 25,
		'supports'        => array( 'title' ),
		'capability_type' => 'post',
		'capabilities'    => array( 'create_posts' => 'do_not_allow' ), // Created by the form only.
		'map_meta_cap'    => true,
	) );
} );

/** Handle the form post (logged-in and anonymous visitors). */
function tristate_handle_appointment() {
	$wants_json = isset( $_SERVER['HTTP_ACCEPT'] ) && false !== strpos( sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT'] ) ), 'application/json' );
	$back       = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	$respond = function ( $ok, $status = 200 ) use ( $wants_json, $back ) {
		if ( $wants_json ) {
			wp_send_json( array( 'success' => $ok ), $status );
		}
		wp_safe_redirect( add_query_arg( 'appointment', $ok ? 'sent' : 'error', remove_query_arg( 'appointment', $back ) ) . '#appointment' );
		exit;
	};

	if ( ! isset( $_POST['tristate_appointment_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['tristate_appointment_nonce'] ), 'tristate_appointment' ) ) {
		$respond( false, 403 );
	}

	// Honeypot: bots fill every field. Pretend success so they move on.
	if ( ! empty( $_POST['website'] ) ) {
		$respond( true );
	}

	$name   = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$phone  = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$email  = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$type   = isset( $_POST['patient_type'] ) ? sanitize_text_field( wp_unslash( $_POST['patient_type'] ) ) : '';
	$reason = isset( $_POST['reason'] ) ? sanitize_textarea_field( wp_unslash( $_POST['reason'] ) ) : '';
	$date   = isset( $_POST['date'] ) ? sanitize_text_field( wp_unslash( $_POST['date'] ) ) : '';

	$valid_phone  = (bool) preg_match( '/^[0-9+()\s-]{7,20}$/', $phone );
	$valid_email  = '' === $email || is_email( $email );
	$valid_type   = in_array( $type, tristate_patient_types(), true );
	$valid_reason = '' !== $reason && mb_strlen( $reason ) <= 1000;
	$valid_date   = '' === $date || (bool) preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date );

	if ( '' === $name || ! $valid_phone || ! $valid_email || ! $valid_type || ! $valid_reason || ! $valid_date ) {
		$respond( false, 422 );
	}

	$post_id = wp_insert_post( array(
		'post_type'   => 'tristate_appointment',
		'post_status' => 'private',
		'post_title'  => sprintf( '%s — %s', $name, $type ),
		'meta_input'  => array(
			'_appt_name'   => $name,
			'_appt_phone'  => $phone,
			'_appt_email'  => $email,
			'_appt_type'   => $type,
			'_appt_reason' => $reason,
			'_appt_date'   => $date,
		),
	) );

	$body = sprintf(
		"New appointment request\n\nName: %s\nPhone: %s\nEmail: %s\nPatient type: %s\nPreferred date: %s\n\nReason for visit:\n%s\n\nView all requests: %s",
		$name,
		$phone,
		$email ? $email : '—',
		$type,
		$date ? $date : '—',
		$reason,
		admin_url( 'edit.php?post_type=tristate_appointment' )
	);
	// Lets staff answer straight from their inbox when the patient left an email.
	$headers = $email ? array( 'Reply-To: ' . $email ) : array();
	wp_mail( tristate_contact( 'email' ), sprintf( 'Appointment request: %s', $name ), $body, $headers );

	$respond( (bool) $post_id );
}
add_action( 'admin_post_tristate_appointment', 'tristate_handle_appointment' );
add_action( 'admin_post_nopriv_tristate_appointment', 'tristate_handle_appointment' );

/** Admin list columns. */
add_filter( 'manage_tristate_appointment_posts_columns', function () {
	return array(
		'cb'         => '<input type="checkbox" />',
		'title'      => __( 'Request', 'tristate' ),
		'phone'      => __( 'Phone', 'tristate' ),
		'reason'     => __( 'Reason for visit', 'tristate' ),
		'pref_date'  => __( 'Preferred date', 'tristate' ),
		'date'       => __( 'Received', 'tristate' ),
	);
} );

add_action( 'manage_tristate_appointment_posts_custom_column', function ( $column, $post_id ) {
	if ( 'phone' === $column ) {
		$phone = get_post_meta( $post_id, '_appt_phone', true );
		printf( '<a href="tel:%1$s">%1$s</a>', esc_attr( $phone ) );
		$email = get_post_meta( $post_id, '_appt_email', true );
		if ( $email ) {
			printf( '<br><a href="mailto:%1$s">%1$s</a>', esc_attr( $email ) );
		}
	} elseif ( 'reason' === $column ) {
		// Requests sent before the reason field existed only have a department.
		$reason = get_post_meta( $post_id, '_appt_reason', true );
		echo esc_html( $reason ? wp_trim_words( $reason, 25 ) : get_post_meta( $post_id, '_appt_department', true ) );
	} elseif ( 'pref_date' === $column ) {
		$date = get_post_meta( $post_id, '_appt_date', true );
		echo esc_html( $date ? $date : '—' );
	}
}, 10, 2 );
