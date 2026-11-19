<?php
/**
 * Emails sent by the booking and message forms: a staff notification and a
 * confirmation to the patient, both in a simple branded layout with a
 * plain-text version.
 *
 * Delivery goes through wp_mail(), so on the live site an SMTP plugin (WP Mail
 * SMTP or FluentSMTP, connected to the cPanel mailbox) handles sending. SMTP
 * credentials belong in that plugin's settings, never in the theme.
 *
 * Settings → Tristate sets who receives the notifications and sends a test.
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

/** Who receives form notifications (Settings → Tristate); info@ by default. */
function tristate_notify_email() {
	$email = get_option( 'tristate_notify_email' );
	return is_email( $email ) ? $email : tristate_contact( 'email' );
}

/**
 * Send one branded email. Returns whether wp_mail() accepted it.
 *
 * @param array $args {
 *     @type string $to       Recipient.
 *     @type string $subject  Subject line.
 *     @type string $heading  Large heading at the top of the email.
 *     @type array  $paras    Paragraphs under the heading.
 *     @type array  $rows     Label => value table (empty values are skipped).
 *     @type string $note     Small note under the table.
 *     @type array  $button   [ label, url ], optional.
 *     @type string $reply_to Reply-To address, optional.
 * }
 */
function tristate_send_mail( $args ) {
	$args = wp_parse_args( $args, array(
		'to'       => '',
		'subject'  => '',
		'heading'  => '',
		'paras'    => array(),
		'rows'     => array(),
		'note'     => '',
		'button'   => array(),
		'reply_to' => '',
	) );
	$args['rows'] = array_filter( $args['rows'], static function ( $value ) {
		return '' !== trim( (string) $value );
	} );

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		// Always the domain's own mailbox, so SPF/DKIM checks pass whoever receives it.
		sprintf( 'From: %s <%s>', 'Tristate Hospitals', tristate_contact( 'email' ) ),
	);
	if ( $args['reply_to'] && is_email( $args['reply_to'] ) ) {
		$headers[] = 'Reply-To: ' . $args['reply_to'];
	}

	// A plain-text version alongside the HTML, for clients that prefer it.
	$text     = tristate_mail_text( $args );
	$alt_body = static function ( $phpmailer ) use ( $text ) {
		$phpmailer->AltBody = $text; // phpcs:ignore WordPress.NamingConventions.ValidVariableName -- PHPMailer property
	};
	add_action( 'phpmailer_init', $alt_body );
	$sent = wp_mail( $args['to'], $args['subject'], tristate_mail_html( $args ), $headers );
	remove_action( 'phpmailer_init', $alt_body );

	return $sent;
}

/** The HTML email: tables and inline styles, so it holds up in every mail client. */
function tristate_mail_html( $args ) {
	$burgundy = '#8c101b';
	$ink      = '#1b1f24';
	$slate    = '#5b6470';
	$cream    = '#fbf6f4';
	$font     = 'Arial, Helvetica, sans-serif';

	ob_start();
	?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?php echo esc_html( $args['subject'] ); ?></title></head>
<body style="margin:0;padding:0;background:<?php echo esc_attr( $cream ); ?>;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:<?php echo esc_attr( $cream ); ?>;padding:24px 12px;">
<tr><td align="center">
	<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:100%;max-width:600px;background:#ffffff;border-radius:16px;overflow:hidden;">
		<tr><td style="background:<?php echo esc_attr( $burgundy ); ?>;padding:22px 32px;">
			<img src="<?php echo esc_url( TRISTATE_URI . '/assets/images/logo-white.png' ); ?>" width="165" height="50" alt="Tristate Hospitals" style="display:block;border:0;color:#ffffff;font-family:<?php echo esc_attr( $font ); ?>;font-size:20px;font-weight:bold;">
		</td></tr>
		<tr><td style="padding:32px 32px 8px;font-family:<?php echo esc_attr( $font ); ?>;">
			<h1 style="margin:0 0 16px;font-size:22px;line-height:1.3;color:<?php echo esc_attr( $ink ); ?>;"><?php echo esc_html( $args['heading'] ); ?></h1>
			<?php foreach ( $args['paras'] as $para ) : ?>
				<p style="margin:0 0 14px;font-size:16px;line-height:1.6;color:<?php echo esc_attr( $slate ); ?>;"><?php echo esc_html( $para ); ?></p>
			<?php endforeach; ?>
		</td></tr>
		<?php if ( $args['rows'] ) : ?>
			<tr><td style="padding:8px 32px 8px;">
				<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font-family:<?php echo esc_attr( $font ); ?>;">
					<?php foreach ( $args['rows'] as $label => $value ) : ?>
						<tr>
							<td style="padding:10px 12px 10px 0;border-top:1px solid #f0e4e5;width:34%;vertical-align:top;font-size:13px;font-weight:bold;color:<?php echo esc_attr( $slate ); ?>;"><?php echo esc_html( $label ); ?></td>
							<td style="padding:10px 0;border-top:1px solid #f0e4e5;vertical-align:top;font-size:15px;line-height:1.5;color:<?php echo esc_attr( $ink ); ?>;"><?php echo nl2br( esc_html( $value ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</td></tr>
		<?php endif; ?>
		<?php if ( $args['button'] ) : ?>
			<tr><td style="padding:18px 32px 4px;">
				<a href="<?php echo esc_url( $args['button'][1] ); ?>" style="display:inline-block;padding:13px 24px;border-radius:10px;background:<?php echo esc_attr( $burgundy ); ?>;color:#ffffff;font-family:<?php echo esc_attr( $font ); ?>;font-size:15px;font-weight:bold;text-decoration:none;"><?php echo esc_html( $args['button'][0] ); ?></a>
			</td></tr>
		<?php endif; ?>
		<?php if ( $args['note'] ) : ?>
			<tr><td style="padding:18px 32px 0;font-family:<?php echo esc_attr( $font ); ?>;font-size:14px;line-height:1.55;color:<?php echo esc_attr( $slate ); ?>;"><?php echo esc_html( $args['note'] ); ?></td></tr>
		<?php endif; ?>
		<tr><td style="padding:28px 32px 30px;font-family:<?php echo esc_attr( $font ); ?>;font-size:13px;line-height:1.6;color:<?php echo esc_attr( $slate ); ?>;">
			<div style="border-top:1px solid #f0e4e5;padding-top:18px;">
				<?php foreach ( tristate_locations() as $place ) : ?>
					<strong style="color:<?php echo esc_attr( $ink ); ?>;"><?php echo esc_html( $place['name'] ); ?></strong><br><?php echo esc_html( $place['address'] ); ?><br><br>
				<?php endforeach; ?>
				Emergency line: <a href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>" style="color:<?php echo esc_attr( $burgundy ); ?>;font-weight:bold;text-decoration:none;"><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a><br>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:<?php echo esc_attr( $burgundy ); ?>;text-decoration:none;"><?php echo esc_html( wp_parse_url( home_url(), PHP_URL_HOST ) ); ?></a>
			</div>
		</td></tr>
	</table>
</td></tr>
</table>
</body>
</html>
	<?php
	return ob_get_clean();
}

/** The same email as plain text. */
function tristate_mail_text( $args ) {
	$lines = array( $args['heading'], '' );
	foreach ( $args['paras'] as $para ) {
		$lines[] = $para;
		$lines[] = '';
	}
	foreach ( $args['rows'] as $label => $value ) {
		$lines[] = $label . ': ' . $value;
	}
	if ( $args['button'] ) {
		$lines[] = '';
		$lines[] = $args['button'][0] . ': ' . $args['button'][1];
	}
	if ( $args['note'] ) {
		$lines[] = '';
		$lines[] = $args['note'];
	}
	$lines[] = '';
	$lines[] = '—';
	foreach ( tristate_locations() as $place ) {
		$lines[] = $place['name'] . ', ' . $place['address'];
	}
	$lines[] = 'Emergency line: ' . tristate_contact( 'emergency' );
	return implode( "\n", $lines );
}

/** Note on a form entry that its staff notification could not be sent. */
function tristate_flag_mail_failure( $post_id, $sent ) {
	if ( $post_id && ! $sent ) {
		update_post_meta( $post_id, '_mail_failed', 1 );
	}
}

/**
 * Whether a confirmation email may go to this visitor-supplied address.
 *
 * The address is unverified, so a script could aim the forms at strangers and
 * make info@ send them mail. Caps it at 3 an hour per address and per IP; the
 * staff notification and the saved entry are unaffected.
 */
function tristate_may_confirm( $email ) {
	$ip   = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$keys = array( 'tristate_confirm_' . md5( strtolower( $email ) ), 'tristate_confirm_ip_' . md5( $ip ) );

	foreach ( $keys as $key ) {
		if ( (int) get_transient( $key ) >= 3 ) {
			return false;
		}
	}
	foreach ( $keys as $key ) {
		set_transient( $key, (int) get_transient( $key ) + 1, HOUR_IN_SECONDS );
	}
	return true;
}

/** Admin list: a red label on entries whose notification email failed. */
function tristate_mail_failed_label( $post_id ) {
	if ( get_post_meta( $post_id, '_mail_failed', true ) ) {
		echo '<br><span style="color:#b42318;font-weight:600;">' . esc_html__( 'Email not sent', 'tristate' ) . '</span>';
	}
}

/* Settings → Tristate ------------------------------------------------------- */

add_action( 'admin_init', function () {
	register_setting( 'tristate', 'tristate_notify_email', array(
		'type'              => 'string',
		'sanitize_callback' => static function ( $value ) {
			$value = sanitize_email( $value );
			return is_email( $value ) ? $value : '';
		},
		'default'           => '',
	) );
} );

add_action( 'admin_menu', function () {
	add_options_page( __( 'Tristate', 'tristate' ), __( 'Tristate', 'tristate' ), 'manage_options', 'tristate', 'tristate_settings_page' );
} );

function tristate_settings_page() {
	$test = isset( $_GET['test-email'] ) ? sanitize_key( $_GET['test-email'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification -- display only.
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Tristate', 'tristate' ); ?></h1>

		<?php if ( 'sent' === $test ) : ?>
			<div class="notice notice-success"><p><?php echo esc_html( sprintf( __( 'Test email sent to %s. If it does not arrive within a few minutes, check the spam folder and the SMTP plugin settings.', 'tristate' ), tristate_notify_email() ) ); ?></p></div>
		<?php elseif ( 'failed' === $test ) : ?>
			<div class="notice notice-error"><p><?php esc_html_e( 'The test email could not be sent. Check the SMTP plugin settings (host, port, username and password of the cPanel mailbox).', 'tristate' ); ?></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'tristate' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="tristate_notify_email"><?php esc_html_e( 'Send form requests to', 'tristate' ); ?></label></th>
					<td>
						<input type="email" class="regular-text" id="tristate_notify_email" name="tristate_notify_email" value="<?php echo esc_attr( get_option( 'tristate_notify_email' ) ); ?>" placeholder="<?php echo esc_attr( tristate_contact( 'email' ) ); ?>">
						<p class="description"><?php esc_html_e( 'Appointment requests and website messages are emailed here. Leave empty to use info@tristatehs.com. Emails are always sent from info@tristatehs.com.', 'tristate' ); ?></p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>

		<h2><?php esc_html_e( 'Check that email works', 'tristate' ); ?></h2>
		<p><?php esc_html_e( 'Sends a sample appointment notification to the address above.', 'tristate' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="tristate_test_email">
			<?php wp_nonce_field( 'tristate_test_email' ); ?>
			<?php submit_button( __( 'Send test email', 'tristate' ), 'secondary', 'submit', false ); ?>
		</form>
	</div>
	<?php
}

add_action( 'admin_post_tristate_test_email', function () {
	if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'tristate_test_email' ) ) {
		wp_die( esc_html__( 'You are not allowed to do that.', 'tristate' ) );
	}
	$sent = tristate_send_mail( array(
		'to'      => tristate_notify_email(),
		'subject' => 'Test email from the Tristate website',
		'heading' => 'Email from the website is working',
		'paras'   => array( 'This is a test sent from Settings → Tristate. Appointment requests and website messages will arrive at this address in this format.' ),
		'rows'    => array( 'Sent' => wp_date( 'j F Y, g:i a' ) ),
	) );
	wp_safe_redirect( add_query_arg( 'test-email', $sent ? 'sent' : 'failed', admin_url( 'options-general.php?page=tristate' ) ) );
	exit;
} );
