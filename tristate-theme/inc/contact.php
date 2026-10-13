<?php
/**
 * Contact page: office locations, and the message form, whose submissions are
 * stored as a private post type (Dashboard → Messages) and emailed to the
 * clinic, like appointment requests. Works with and without JavaScript.
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

/** Office locations: name, area (tag), address. Copied from the live Contact page. */
function tristate_locations() {
	return array(
		array( 'Tristate Hospital Lekki', 'Lagos', 'Plot 6 Block 1A Rock Drive, Off Durosinmi Etti, Close to IMAX Film house, Lekki Phase 1, Lagos State.' ),
		array( 'Tristate @ Reddington, Victoria Island', 'Lagos', '12 Idowu Martins Street, Victoria Island, Lagos State.' ),
		array( 'Tristate @ Reddington Ikeja', 'Lagos', 'The Duchess Hospital, 27 – 35 Joel Ogunnaike St, Ikeja GRA, Lagos State.' ),
		array( 'Tristate Hospital Ibadan', 'Oyo', 'Metro International Medical Building, Plot 16A, American Quarters, Idi Ape, Ibadan, Oyo State.' ),
		array( 'Babcock Tristate Heart & Vascular Centre', 'Ogun', 'Babcock Tristate Heart and Vascular Centre, Ilishan Remo, Ogun State.' ),
	);
}

/** What the visitor is writing about. */
function tristate_message_topics() {
	return array( 'General enquiry', 'Appointments', 'Billing & payments', 'Medical records', 'Feedback', 'Partnerships' );
}

add_action( 'init', function () {
	register_post_type( 'tristate_message', array(
		'labels'          => array(
			'name'          => __( 'Messages', 'tristate' ),
			'singular_name' => __( 'Message', 'tristate' ),
		),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => true,
		'menu_icon'       => 'dashicons-email-alt',
		'menu_position'   => 26,
		'supports'        => array( 'title', 'editor' ),
		'capability_type' => 'post',
		'capabilities'    => array( 'create_posts' => 'do_not_allow' ), // Created by the form only.
		'map_meta_cap'    => true,
	) );
} );

/** Handle the form post (logged-in and anonymous visitors). */
function tristate_handle_message() {
	$wants_json = isset( $_SERVER['HTTP_ACCEPT'] ) && false !== strpos( sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT'] ) ), 'application/json' );
	$back       = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	$respond = function ( $ok, $status = 200 ) use ( $wants_json, $back ) {
		if ( $wants_json ) {
			wp_send_json( array( 'success' => $ok ), $status );
		}
		wp_safe_redirect( add_query_arg( 'message', $ok ? 'sent' : 'error', remove_query_arg( 'message', $back ) ) . '#contact-form' );
		exit;
	};

	if ( ! isset( $_POST['tristate_message_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['tristate_message_nonce'] ), 'tristate_message' ) ) {
		$respond( false, 403 );
	}

	// Honeypot: bots fill every field. Pretend success so they move on.
	if ( ! empty( $_POST['website'] ) ) {
		$respond( true );
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$topic   = isset( $_POST['topic'] ) ? sanitize_text_field( wp_unslash( $_POST['topic'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	$valid_phone = '' === $phone || (bool) preg_match( '/^[0-9+()\s-]{7,20}$/', $phone );
	$valid_topic = in_array( $topic, tristate_message_topics(), true );

	if ( mb_strlen( $name ) < 2 || ! is_email( $email ) || ! $valid_phone || ! $valid_topic || mb_strlen( $message ) < 10 ) {
		$respond( false, 422 );
	}

	$post_id = wp_insert_post( array(
		'post_type'    => 'tristate_message',
		'post_status'  => 'private',
		'post_title'   => sprintf( '%s — %s', $name, $topic ),
		'post_content' => $message,
		'meta_input'   => array(
			'_msg_name'  => $name,
			'_msg_email' => $email,
			'_msg_phone' => $phone,
			'_msg_topic' => $topic,
		),
	) );

	$body = sprintf(
		"New message from the website\n\nName: %s\nEmail: %s\nPhone: %s\nTopic: %s\n\n%s\n\nView all messages: %s",
		$name,
		$email,
		$phone ? $phone : '—',
		$topic,
		$message,
		admin_url( 'edit.php?post_type=tristate_message' )
	);
	wp_mail( tristate_contact( 'email' ), sprintf( 'Website message: %s', $topic ), $body, array( 'Reply-To: ' . $name . ' <' . $email . '>' ) );

	$respond( (bool) $post_id );
}
add_action( 'admin_post_tristate_message', 'tristate_handle_message' );
add_action( 'admin_post_nopriv_tristate_message', 'tristate_handle_message' );

/** Admin list columns. */
add_filter( 'manage_tristate_message_posts_columns', function () {
	return array(
		'cb'    => '<input type="checkbox" />',
		'title' => __( 'Message', 'tristate' ),
		'email' => __( 'Email', 'tristate' ),
		'phone' => __( 'Phone', 'tristate' ),
		'date'  => __( 'Received', 'tristate' ),
	);
} );

add_action( 'manage_tristate_message_posts_custom_column', function ( $column, $post_id ) {
	if ( 'email' === $column ) {
		$email = get_post_meta( $post_id, '_msg_email', true );
		printf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $email ) );
	} elseif ( 'phone' === $column ) {
		$phone = get_post_meta( $post_id, '_msg_phone', true );
		echo $phone ? sprintf( '<a href="tel:%1$s">%1$s</a>', esc_attr( $phone ) ) : '—';
	}
}, 10, 2 );
