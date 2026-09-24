<?php
/**
 * Appointment requests: stored as a private post type (Dashboard → Appointments)
 * and emailed to the clinic. Works with and without JavaScript.
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

/** Departments offered in the booking form. */
function tristate_departments() {
	return array(
		'Cardiovascular Services',
		'Laboratory Services',
		'Radiology',
		'Emergency & Critical Care',
		'Anaesthesiology',
		'Dialysis',
		'Obstetrics and Gynaecology',
		'Outpatient',
		'Pharmaceutical',
		'Respiratory & Critical Care',
	);
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

	$name       = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$phone      = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$department = isset( $_POST['department'] ) ? sanitize_text_field( wp_unslash( $_POST['department'] ) ) : '';
	$date       = isset( $_POST['date'] ) ? sanitize_text_field( wp_unslash( $_POST['date'] ) ) : '';

	$valid_phone = (bool) preg_match( '/^[0-9+()\s-]{7,20}$/', $phone );
	$valid_dept  = in_array( $department, tristate_departments(), true );
	$valid_date  = '' === $date || (bool) preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date );

	if ( '' === $name || ! $valid_phone || ! $valid_dept || ! $valid_date ) {
		$respond( false, 422 );
	}

	$post_id = wp_insert_post( array(
		'post_type'   => 'tristate_appointment',
		'post_status' => 'private',
		'post_title'  => sprintf( '%s — %s', $name, $department ),
		'meta_input'  => array(
			'_appt_name'       => $name,
			'_appt_phone'      => $phone,
			'_appt_department' => $department,
			'_appt_date'       => $date,
		),
	) );

	$body = sprintf(
		"New appointment request\n\nName: %s\nPhone: %s\nDepartment: %s\nPreferred date: %s\n\nView all requests: %s",
		$name,
		$phone,
		$department,
		$date ? $date : '—',
		admin_url( 'edit.php?post_type=tristate_appointment' )
	);
	wp_mail( tristate_contact( 'email' ), sprintf( 'Appointment request: %s', $name ), $body );

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
		'pref_date'  => __( 'Preferred date', 'tristate' ),
		'date'       => __( 'Received', 'tristate' ),
	);
} );

add_action( 'manage_tristate_appointment_posts_custom_column', function ( $column, $post_id ) {
	if ( 'phone' === $column ) {
		$phone = get_post_meta( $post_id, '_appt_phone', true );
		printf( '<a href="tel:%1$s">%1$s</a>', esc_attr( $phone ) );
	} elseif ( 'pref_date' === $column ) {
		$date = get_post_meta( $post_id, '_appt_date', true );
		echo esc_html( $date ? $date : '—' );
	}
}, 10, 2 );
