<?php
/**
 * Appointment booking — Figma 25:11.
 * Submissions are handled in inc/appointments.php.
 *
 * @package tristate
 */

$tristate_status = isset( $_GET['appointment'] ) ? sanitize_key( $_GET['appointment'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification -- display only.
?>
<section class="appointment" id="appointment" aria-labelledby="appointment-title">
	<div class="container appointment__inner">
		<div class="appointment__media">
			<figure class="appointment__image" data-reveal="clip-up">
				<img src="<?php echo esc_url( TRISTATE_URI . '/assets/images/appointment.jpg' ); ?>" width="1100" height="812" alt="<?php esc_attr_e( 'Tristate cardiologist seated in a consultation room', 'tristate' ); ?>" loading="lazy" data-parallax="0.05">
			</figure>
			<div class="appointment__hours" data-reveal="up" data-delay="0.5">
				<span class="appointment__dot"><?php tristate_icon( 'status-dot', 12 ); ?></span>
				<span>
					<strong><?php esc_html_e( 'Open 24/7', 'tristate' ); ?></strong>
					<span><?php esc_html_e( 'Emergency & Critical Care', 'tristate' ); ?></span>
				</span>
			</div>
		</div>

		<div class="appointment__card" data-reveal="up" data-delay="0.15">
			<div class="appointment__form-wrap" data-appointment-form-wrap<?php echo 'sent' === $tristate_status ? ' hidden' : ''; ?>>
				<p class="section-label"><?php esc_html_e( 'Book Appointment', 'tristate' ); ?></p>
				<h2 class="section-title" id="appointment-title" style="--title-size: 40">Your health is in <em>good hands</em>.</h2>

				<form class="appointment-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-appointment-form data-phone="<?php echo esc_attr( tristate_contact( 'emergency' ) ); ?>" novalidate>
					<input type="hidden" name="action" value="tristate_appointment">
					<?php wp_nonce_field( 'tristate_appointment', 'tristate_appointment_nonce' ); ?>
					<div class="appointment-form__hp" aria-hidden="true">
						<label for="appt-website">Website</label>
						<input type="text" id="appt-website" name="website" tabindex="-1" autocomplete="off">
					</div>

					<div class="appointment-form__row">
						<div class="field">
							<label class="screen-reader-text" for="appt-name"><?php esc_html_e( 'Full name', 'tristate' ); ?></label>
							<input class="field__control" type="text" id="appt-name" name="name" placeholder="<?php esc_attr_e( 'Full name', 'tristate' ); ?>" autocomplete="name" required>
						</div>
						<div class="field">
							<label class="screen-reader-text" for="appt-phone"><?php esc_html_e( 'Phone number', 'tristate' ); ?></label>
							<input class="field__control" type="tel" id="appt-phone" name="phone" placeholder="<?php esc_attr_e( 'Phone number', 'tristate' ); ?>" autocomplete="tel" inputmode="tel" required>
						</div>
					</div>

					<div class="appointment-form__row">
						<div class="field">
							<label class="screen-reader-text" for="appt-email"><?php esc_html_e( 'Email address (optional)', 'tristate' ); ?></label>
							<input class="field__control" type="email" id="appt-email" name="email" placeholder="<?php esc_attr_e( 'Email (optional)', 'tristate' ); ?>" autocomplete="email" inputmode="email">
						</div>
						<div class="field field--select field--date" data-placeholder="<?php esc_attr_e( 'Preferred date', 'tristate' ); ?>" data-date-field>
							<label class="screen-reader-text" for="appt-date"><?php esc_html_e( 'Preferred date', 'tristate' ); ?></label>
							<input class="field__control" type="date" id="appt-date" name="date" min="<?php echo esc_attr( wp_date( 'Y-m-d' ) ); ?>">
						</div>
					</div>

					<fieldset class="field appointment-form__choice">
						<legend class="appointment-form__legend"><?php esc_html_e( 'Patient type', 'tristate' ); ?></legend>
						<div class="appointment-form__options">
							<?php foreach ( tristate_patient_types() as $tristate_type ) : ?>
								<label class="appointment-form__option">
									<input type="radio" name="patient_type" value="<?php echo esc_attr( $tristate_type ); ?>" required>
									<span><?php echo esc_html( $tristate_type ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</fieldset>

					<div class="field">
						<label class="screen-reader-text" for="appt-reason"><?php esc_html_e( 'Reason for visit', 'tristate' ); ?></label>
						<textarea class="field__control appointment-form__textarea" id="appt-reason" name="reason" rows="3" maxlength="1000" placeholder="<?php esc_attr_e( 'Reason for visit', 'tristate' ); ?>" required></textarea>
					</div>

					<button class="appointment-form__submit" type="submit">
						<span data-submit-label><?php esc_html_e( 'Request Appointment', 'tristate' ); ?></span> <span class="btn__arrow" aria-hidden="true">→</span>
					</button>

					<p class="appointment-form__error" role="alert" data-appointment-error<?php echo 'error' === $tristate_status ? '' : ' hidden'; ?>><?php esc_html_e( 'Please fill in your name, phone number, patient type and reason for visit.', 'tristate' ); ?></p>
				</form>

				<p class="appointment__note">
					<?php esc_html_e( 'Or call our emergency line', 'tristate' ); ?>
					<a href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>"><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a>
				</p>
			</div>

			<div class="appointment__success" data-appointment-success<?php echo 'sent' === $tristate_status ? '' : ' hidden'; ?> tabindex="-1">
				<span class="appointment__success-icon"><?php tristate_icon( 'check', 28 ); ?></span>
				<h2 class="section-title" style="--title-size: 36"><?php esc_html_e( 'Request received.', 'tristate' ); ?></h2>
				<p><?php esc_html_e( 'Thank you. Our team will call you shortly to confirm your appointment.', 'tristate' ); ?></p>
				<p class="appointment__note">
					<?php esc_html_e( 'Need urgent care? Call', 'tristate' ); ?>
					<a href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>"><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a>
				</p>
			</div>
		</div>
	</div>
</section>
