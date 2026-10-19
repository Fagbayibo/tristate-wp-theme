<?php
/**
 * Request an Appointment (homepage). Kept short, as the brief asks: name, phone,
 * service or "Help me choose", and where to be seen; everything else optional.
 * Service pages link here with ?service=<slug> (or ?visit=virtual) to preselect.
 * Submissions are handled in inc/appointments.php.
 *
 * @package tristate
 */

// phpcs:disable WordPress.Security.NonceVerification -- display only: preselects form options.
$tristate_status  = isset( $_GET['appointment'] ) ? sanitize_key( $_GET['appointment'] ) : '';
$tristate_service = isset( $_GET['service'] ) ? sanitize_key( $_GET['service'] ) : '';
$tristate_visit   = isset( $_GET['visit'] ) ? sanitize_key( $_GET['visit'] ) : ( isset( $_GET['hospital'] ) ? sanitize_key( $_GET['hospital'] ) : '' );
// phpcs:enable

$tristate_bookable = tristate_bookable_services();
$tristate_visits   = tristate_visit_options();
if ( ! isset( $tristate_bookable[ $tristate_service ] ) && 'help' !== $tristate_service ) {
	$tristate_service = '';
}
?>
<section class="appointment" id="appointment" aria-labelledby="appointment-title">
	<div class="container appointment__inner">
		<div class="appointment__media">
			<figure class="appointment__image" data-reveal="clip-up">
				<img src="<?php echo esc_url( TRISTATE_URI . '/assets/images/appointment.jpg' ); ?>" width="1100" height="812" alt="<?php esc_attr_e( 'A Tristate doctor seated in a consultation room', 'tristate' ); ?>" loading="lazy" data-parallax="0.05">
			</figure>
			<a class="appointment__hours" href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>" data-reveal="up" data-delay="0.5">
				<span class="appointment__dot"><?php tristate_icon( 'status-dot', 12 ); ?></span>
				<span>
					<strong><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></strong>
					<span><?php esc_html_e( 'Emergency line', 'tristate' ); ?></span>
				</span>
			</a>
		</div>

		<div class="appointment__card" data-reveal="up" data-delay="0.15">
			<div class="appointment__form-wrap" data-appointment-form-wrap<?php echo 'sent' === $tristate_status ? ' hidden' : ''; ?>>
				<p class="section-label"><?php esc_html_e( 'Request an Appointment', 'tristate' ); ?></p>
				<h2 class="section-title" id="appointment-title" style="--title-size: 40">Tell us how <em>we can help</em></h2>
				<p class="appointment__intro"><?php esc_html_e( 'Choose your service and preferred hospital, or select “Help me choose”. Our team will contact you about availability. Your appointment is confirmed only when you receive confirmation from us.', 'tristate' ); ?></p>

				<form class="appointment-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-appointment-form data-email="<?php echo esc_attr( tristate_contact( 'email' ) ); ?>" novalidate>
					<input type="hidden" name="action" value="tristate_appointment">
					<?php wp_nonce_field( 'tristate_appointment', 'tristate_appointment_nonce' ); ?>
					<div class="appointment-form__hp" aria-hidden="true">
						<label for="appt-website">Website</label>
						<input type="text" id="appt-website" name="website" tabindex="-1" autocomplete="off">
					</div>

					<div class="appointment-form__row">
						<div class="field">
							<label class="field__label" for="appt-name"><?php esc_html_e( 'Full name', 'tristate' ); ?></label>
							<input class="field__control" type="text" id="appt-name" name="name" autocomplete="name" required>
						</div>
						<div class="field">
							<label class="field__label" for="appt-phone"><?php esc_html_e( 'Phone number', 'tristate' ); ?></label>
							<input class="field__control" type="tel" id="appt-phone" name="phone" autocomplete="tel" inputmode="tel" required>
						</div>
					</div>

					<div class="field field--select">
						<label class="field__label" for="appt-service"><?php esc_html_e( 'Service', 'tristate' ); ?></label>
						<select class="field__control" id="appt-service" name="service" required>
							<option value="" disabled<?php selected( $tristate_service, '' ); ?>><?php esc_html_e( 'Choose a service', 'tristate' ); ?></option>
							<option value="help"<?php selected( $tristate_service, 'help' ); ?>><?php esc_html_e( 'Help me choose', 'tristate' ); ?></option>
							<?php foreach ( tristate_service_groups() as $tristate_group ) : ?>
								<?php
								$tristate_options = array_intersect( $tristate_group['services'], array_keys( $tristate_bookable ) );
								if ( ! $tristate_options ) {
									continue;
								}
								?>
								<optgroup label="<?php echo esc_attr( $tristate_group['label'] ); ?>">
									<?php foreach ( $tristate_options as $tristate_slug ) : ?>
										<option value="<?php echo esc_attr( $tristate_slug ); ?>"<?php selected( $tristate_service, $tristate_slug ); ?>><?php echo esc_html( $tristate_bookable[ $tristate_slug ] ); ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php endforeach; ?>
						</select>
					</div>

					<fieldset class="field appointment-form__choice">
						<legend class="appointment-form__legend"><?php esc_html_e( 'Where would you like to be seen?', 'tristate' ); ?></legend>
						<div class="appointment-form__options">
							<?php foreach ( $tristate_visits as $tristate_key => $tristate_label ) : ?>
								<label class="appointment-form__option">
									<input type="radio" name="visit" value="<?php echo esc_attr( $tristate_key ); ?>"<?php checked( $tristate_visit, $tristate_key ); ?> required>
									<span><?php echo esc_html( $tristate_label ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</fieldset>

					<div class="appointment-form__row">
						<div class="field">
							<label class="field__label" for="appt-email"><?php esc_html_e( 'Email', 'tristate' ); ?> <span><?php esc_html_e( '(optional)', 'tristate' ); ?></span></label>
							<input class="field__control" type="email" id="appt-email" name="email" autocomplete="email" inputmode="email">
						</div>
						<div class="field" data-date-field>
							<label class="field__label" for="appt-date"><?php esc_html_e( 'Preferred date', 'tristate' ); ?> <span><?php esc_html_e( '(optional)', 'tristate' ); ?></span></label>
							<input class="field__control" type="date" id="appt-date" name="date" min="<?php echo esc_attr( wp_date( 'Y-m-d' ) ); ?>">
						</div>
					</div>

					<div class="field field--select">
						<label class="field__label" for="appt-payment"><?php esc_html_e( 'Payment method', 'tristate' ); ?> <span><?php esc_html_e( '(optional)', 'tristate' ); ?></span></label>
						<select class="field__control" id="appt-payment" name="payment">
							<option value=""><?php esc_html_e( 'Choose one', 'tristate' ); ?></option>
							<?php foreach ( tristate_payment_methods() as $tristate_method ) : ?>
								<option><?php echo esc_html( $tristate_method ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="field">
						<label class="field__label" for="appt-message"><?php esc_html_e( 'Brief message', 'tristate' ); ?> <span><?php esc_html_e( '(optional)', 'tristate' ); ?></span></label>
						<textarea class="field__control appointment-form__textarea" id="appt-message" name="message" rows="3" maxlength="1000"></textarea>
					</div>

					<p class="appointment-form__privacy">
						<?php esc_html_e( 'Please do not include detailed medical records in this form.', 'tristate' ); ?>
						<?php
						printf(
							/* translators: %s: link to the Privacy Notice */
							esc_html__( 'Read our %s to understand how your information is used.', 'tristate' ),
							'<a href="' . esc_url( get_privacy_policy_url() ? get_privacy_policy_url() : home_url( '/privacy-policy/' ) ) . '">' . esc_html__( 'Privacy Notice', 'tristate' ) . '</a>'
						);
						?>
					</p>

					<button class="appointment-form__submit" type="submit">
						<span data-submit-label><?php esc_html_e( 'Send Appointment Request', 'tristate' ); ?></span> <span class="btn__arrow" aria-hidden="true">→</span>
					</button>

					<p class="appointment-form__error" role="alert" data-appointment-error<?php echo 'error' === $tristate_status ? '' : ' hidden'; ?>><?php esc_html_e( 'Please add your name, phone number, a service (or Help me choose) and where you would like to be seen.', 'tristate' ); ?></p>
				</form>

				<p class="appointment__note">
					<?php esc_html_e( 'For emergencies, seek immediate medical care. This form is for routine appointment enquiries.', 'tristate' ); ?>
					<a href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>"><?php echo esc_html( sprintf( /* translators: %s: phone number */ __( 'Emergency line: %s', 'tristate' ), tristate_contact( 'emergency' ) ) ); ?></a>
				</p>
			</div>

			<div class="appointment__success" data-appointment-success<?php echo 'sent' === $tristate_status ? '' : ' hidden'; ?> tabindex="-1">
				<span class="appointment__success-icon"><?php tristate_icon( 'check', 28 ); ?></span>
				<h2 class="section-title" style="--title-size: 36"><?php esc_html_e( 'Request received.', 'tristate' ); ?></h2>
				<p><?php esc_html_e( 'Your request has been received. We will contact you to arrange your appointment.', 'tristate' ); ?></p>
				<p class="appointment__note">
					<?php esc_html_e( 'For emergencies, seek immediate medical care.', 'tristate' ); ?>
					<a href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>"><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a>
				</p>
			</div>
		</div>
	</div>
</section>
