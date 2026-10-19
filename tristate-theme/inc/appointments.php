<?php
/**
 * Appointment requests: stored as a private post type (Dashboard → Appointments)
 * and emailed to the clinic. Works with and without JavaScript.
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

/** Where the patient would like to be seen: key => label (a hospital, or virtual). */
function tristate_visit_options() {
	$options = array();
	foreach ( tristate_locations() as $key => $place ) {
		$options[ $key ] = $place['name'];
	}
	$options['virtual'] = __( 'Virtual consultation', 'tristate' );
	return $options;
}

/** Optional payment methods. */
function tristate_payment_methods() {
	return array( 'Self-pay', 'HMO', 'Not sure yet' );
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

	$field = static function ( $key, $sanitize = 'sanitize_text_field' ) {
		return isset( $_POST[ $key ] ) ? call_user_func( $sanitize, wp_unslash( $_POST[ $key ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput -- verified above; sanitized by $sanitize.
	};

	$name    = $field( 'name' );
	$phone   = $field( 'phone' );
	$service = $field( 'service', 'sanitize_key' );
	$visit   = $field( 'visit', 'sanitize_key' );
	$email   = $field( 'email', 'sanitize_email' );
	$date    = $field( 'date' );
	$payment = $field( 'payment' );
	$message = $field( 'message', 'sanitize_textarea_field' );

	$bookable = tristate_bookable_services();
	$visits   = tristate_visit_options();

	$valid = mb_strlen( $name ) > 1
		&& preg_match( '/^[0-9+()\s-]{7,20}$/', $phone )
		&& ( 'help' === $service || isset( $bookable[ $service ] ) )
		&& isset( $visits[ $visit ] )
		&& ( '' === $email || is_email( $email ) )
		&& ( '' === $date || preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) )
		&& ( '' === $payment || in_array( $payment, tristate_payment_methods(), true ) )
		&& mb_strlen( $message ) <= 1000;

	if ( ! $valid ) {
		$respond( false, 422 );
	}

	$service_name = 'help' === $service ? __( 'Help me choose', 'tristate' ) : $bookable[ $service ];
	$visit_name   = $visits[ $visit ];

	$post_id = wp_insert_post( array(
		'post_type'   => 'tristate_appointment',
		'post_status' => 'private',
		'post_title'  => sprintf( '%s — %s', $name, $service_name ),
		'meta_input'  => array(
			'_appt_name'    => $name,
			'_appt_phone'   => $phone,
			'_appt_email'   => $email,
			'_appt_service' => $service_name,
			'_appt_visit'   => $visit_name,
			'_appt_date'    => $date,
			'_appt_payment' => $payment,
			'_appt_message' => $message,
		),
	) );

	$body = sprintf(
		"New appointment request\n\nName: %s\nPhone: %s\nEmail: %s\nService: %s\nWhere: %s\nPreferred date: %s\nPayment method: %s\n\nMessage:\n%s\n\nView all requests: %s",
		$name,
		$phone,
		$email ? $email : '—',
		$service_name,
		$visit_name,
		$date ? $date : '—',
		$payment ? $payment : '—',
		$message ? $message : '—',
		admin_url( 'edit.php?post_type=tristate_appointment' )
	);
	// Lets staff answer straight from their inbox when the patient left an email.
	$headers = $email ? array( 'Reply-To: ' . $email ) : array();
	wp_mail( tristate_contact( 'email' ), sprintf( 'Appointment request: %s (%s)', $name, $service_name ), $body, $headers );

	$respond( (bool) $post_id );
}
add_action( 'admin_post_tristate_appointment', 'tristate_handle_appointment' );
add_action( 'admin_post_nopriv_tristate_appointment', 'tristate_handle_appointment' );

/** Admin list columns. */
add_filter( 'manage_tristate_appointment_posts_columns', function () {
	return array(
		'cb'        => '<input type="checkbox" />',
		'title'     => __( 'Request', 'tristate' ),
		'phone'     => __( 'Contact', 'tristate' ),
		'visit'     => __( 'Where', 'tristate' ),
		'message'   => __( 'Message', 'tristate' ),
		'pref_date' => __( 'Preferred date', 'tristate' ),
		'date'      => __( 'Received', 'tristate' ),
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
	} elseif ( 'visit' === $column ) {
		$visit   = get_post_meta( $post_id, '_appt_visit', true );
		$payment = get_post_meta( $post_id, '_appt_payment', true );
		echo esc_html( $visit ? $visit : '—' );
		if ( $payment ) {
			echo '<br>' . esc_html( $payment );
		}
	} elseif ( 'message' === $column ) {
		// Older requests kept a reason for visit (Oct 2026) or a department (before).
		$text = get_post_meta( $post_id, '_appt_message', true );
		$text = $text ? $text : get_post_meta( $post_id, '_appt_reason', true );
		$text = $text ? $text : get_post_meta( $post_id, '_appt_department', true );
		echo esc_html( $text ? wp_trim_words( $text, 25 ) : '—' );
	} elseif ( 'pref_date' === $column ) {
		$date = get_post_meta( $post_id, '_appt_date', true );
		echo esc_html( $date ? $date : '—' );
	}
}, 10, 2 );
