<?php
/**
 * Template Name: Our Hospitals
 *
 * The Our Hospitals page (and the old Contact Us page, which redirects to it)
 * gets this design from its slug (see functions.php). The message form is
 * handled in inc/contact.php.
 *
 * @package tristate
 */

// phpcs:disable WordPress.Security.NonceVerification -- display only.
$tristate_status = isset( $_GET['message'] ) ? sanitize_key( $_GET['message'] ) : '';
$tristate_topic  = isset( $_GET['topic'] ) ? sanitize_key( $_GET['topic'] ) : '';
// phpcs:enable
$tristate_topic_pick = 'hmo' === $tristate_topic ? 'HMO cover' : '';
$tristate_locations  = tristate_locations();
$tristate_first      = reset( $tristate_locations );

// Quick contact cards: Tabler icon, label, text, href.
$tristate_quick = array(
	array( 'mail', 'General enquiries', tristate_contact( 'email' ), 'mailto:' . tristate_contact( 'email' ) ),
	array( 'phone', 'Emergency line', tristate_contact( 'emergency' ), 'tel:' . tristate_contact( 'emergency_tel' ) ),
	array( 'video', 'Speak with a clinician remotely', 'Virtual Consultations', tristate_service_url( 'virtual-consultations' ) ),
);

get_header();
?>
<div class="landing landing--contact">

	<!-- Hero ------------------------------------------------------------------ -->
	<section class="ct-hero">
		<div class="blog-head__glow" aria-hidden="true"></div>
		<div class="container">
			<div class="ct-hero__head">
				<p class="eyebrow eyebrow--center" data-reveal="up">Choose how to visit</p>
				<h1 class="landing-title" data-split data-delay="0.15">Our <em>Hospitals</em></h1>
				<p class="landing-lead" data-reveal="up" data-delay="0.35">Find Tristate Hospitals in Lekki, Lagos, and Idi-Ape, Ibadan. For suitable consultations, you can also speak with a clinician remotely.</p>
			</div>

			<ul class="ct-quick" data-reveal-stagger>
				<?php foreach ( $tristate_quick as $tristate_card ) : ?>
					<li class="ct-quick__card">
						<span class="ct-quick__icon"><?php echo tristate_svg( $tristate_card[0] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
						<span class="ct-quick__label"><?php echo esc_html( $tristate_card[1] ); ?></span>
						<a class="ct-quick__value" href="<?php echo esc_url( $tristate_card[3], array( 'tel', 'mailto', 'http', 'https' ) ); ?>"><?php echo esc_html( $tristate_card[2] ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<!-- Hospitals + map ----------------------------------------------------------- -->
	<section class="ct-locations" id="locations">
		<div class="container">
			<div class="ct-locations__grid">
				<ol class="ct-places" data-reveal-stagger data-places>
					<?php foreach ( array_values( $tristate_locations ) as $tristate_i => $tristate_place ) : ?>
						<?php $tristate_key = array_keys( $tristate_locations )[ $tristate_i ]; ?>
						<li class="ct-place<?php echo 0 === $tristate_i ? ' is-active' : ''; ?>" id="<?php echo esc_attr( $tristate_key ); ?>">
							<button class="ct-place__pick" type="button" data-map="<?php echo esc_url( tristate_map_url( $tristate_place['address'] ) ); ?>" aria-pressed="<?php echo 0 === $tristate_i ? 'true' : 'false'; ?>">
								<span class="ct-place__num"><?php echo esc_html( sprintf( '%02d', $tristate_i + 1 ) ); ?></span>
								<span class="ct-place__text">
									<span class="ct-place__name"><?php echo esc_html( $tristate_place['name'] ); ?></span>
									<span class="ct-place__address"><?php echo esc_html( $tristate_place['address'] ); ?></span>
								</span>
								<span class="landing-tag ct-place__tag"><?php echo esc_html( $tristate_place['area'] ); ?></span>
							</button>
							<div class="ct-place__actions">
								<a class="btn btn--outline-brand" href="<?php echo esc_url( tristate_directions_url( $tristate_place['address'] ) ); ?>" target="_blank" rel="noopener">Get Directions to <?php echo esc_html( $tristate_place['short'] ); ?> <span aria-hidden="true">↗</span></a>
								<a class="btn btn--brand" href="<?php echo esc_url( add_query_arg( 'visit', $tristate_key, home_url( '/' ) ) . '#appointment' ); ?>">Request <?php echo esc_html( 'Ibadan' === $tristate_place['short'] ? 'an' : 'a' ); ?> <?php echo esc_html( $tristate_place['short'] ); ?> Appointment</a>
							</div>
						</li>
					<?php endforeach; ?>
				</ol>

				<div class="ct-map" data-reveal="clip-up">
					<iframe src="<?php echo esc_url( tristate_map_url( $tristate_first['address'] ) ); ?>" title="<?php esc_attr_e( 'Map of the selected Tristate hospital', 'tristate' ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen data-map-frame></iframe>
				</div>
			</div>
		</div>
	</section>

	<!-- Form + emergency ------------------------------------------------------- -->
	<section class="ct-main" id="contact-form">
		<div class="container ct-main__grid">
			<div class="ct-form-card" data-reveal="up">
				<div class="ct-form-wrap" data-message-form-wrap<?php echo 'sent' === $tristate_status ? ' hidden' : ''; ?>>
					<p class="eyebrow">Send a message</p>
					<h2 class="section-title" style="--title-size: 44" data-split>How can we <em>help you?</em></h2>
					<p class="ct-form-card__intro">Tell us a little about what you need and the right team will get back to you by email or phone. Please do not include detailed medical records.</p>

					<form class="ct-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-message-form data-email="<?php echo esc_attr( tristate_contact( 'email' ) ); ?>" novalidate>
						<input type="hidden" name="action" value="tristate_message">
						<?php wp_nonce_field( 'tristate_message', 'tristate_message_nonce' ); ?>
						<div class="ct-form__hp" aria-hidden="true">
							<label for="msg-website">Website</label>
							<input type="text" id="msg-website" name="website" tabindex="-1" autocomplete="off">
						</div>

						<div class="ct-form__row">
							<div class="field">
								<label class="field__label" for="msg-name">Full name</label>
								<input class="field__control" type="text" id="msg-name" name="name" autocomplete="name" required>
							</div>
							<div class="field">
								<label class="field__label" for="msg-email">Email address</label>
								<input class="field__control" type="email" id="msg-email" name="email" autocomplete="email" required>
							</div>
						</div>

						<div class="ct-form__row">
							<div class="field">
								<label class="field__label" for="msg-phone">Phone <span>(optional)</span></label>
								<input class="field__control" type="tel" id="msg-phone" name="phone" autocomplete="tel" inputmode="tel">
							</div>
							<div class="field field--select">
								<label class="field__label" for="msg-topic">Topic</label>
								<select class="field__control" id="msg-topic" name="topic" required>
									<option value="" disabled<?php selected( $tristate_topic_pick, '' ); ?>>Choose a topic</option>
									<?php foreach ( tristate_message_topics() as $tristate_name ) : ?>
										<option<?php selected( $tristate_topic_pick, $tristate_name ); ?>><?php echo esc_html( $tristate_name ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>

						<div class="field">
							<label class="field__label" for="msg-message">Message</label>
							<textarea class="field__control ct-form__textarea" id="msg-message" name="message" rows="6" required></textarea>
						</div>

						<div class="ct-form__foot">
							<button class="btn btn--brand ct-form__submit" type="submit">
								<span data-submit-label>Send Message</span> <span class="btn__arrow" aria-hidden="true">→</span>
							</button>
							<p class="ct-form__privacy">We only use your details to reply to this message.</p>
						</div>

						<p class="ct-form__error" role="alert" data-message-error<?php echo 'error' === $tristate_status ? '' : ' hidden'; ?>>Please add your name, a valid email, a topic and a short message.</p>
					</form>
				</div>

				<div class="ct-success" data-message-success<?php echo 'sent' === $tristate_status ? '' : ' hidden'; ?> tabindex="-1">
					<span class="ct-success__icon"><?php tristate_icon( 'check', 28 ); ?></span>
					<h2 class="section-title" style="--title-size: 36">Message sent.</h2>
					<p>Thank you for reaching out. Our team will get back to you.</p>
					<p class="ct-success__note">For emergencies, seek immediate medical care. <a href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>"><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a></p>
				</div>
			</div>

			<aside class="ct-aside">
				<div class="ct-urgent" data-reveal="up" data-delay="0.15" data-observe>
					<p class="ct-urgent__label"><span class="pulse-dot" aria-hidden="true"></span>Emergency line</p>
					<a class="ct-urgent__number" href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>"><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a>
					<p class="ct-urgent__text">For a medical emergency, seek immediate care at the nearest emergency department. Do not wait for an online appointment or message reply.</p>
					<svg class="ecg" viewBox="0 0 600 90" preserveAspectRatio="none" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path pathLength="1000" d="M0 50 H170 L190 50 L205 20 L222 80 L240 8 L258 70 L272 50 H360 L378 50 L392 30 L408 68 L420 50 H600"/></svg>
				</div>

				<div class="ct-social" data-reveal="up" data-delay="0.25">
					<p class="ct-social__label">Follow us on social media</p>
					<ul class="ct-social__list">
						<?php foreach ( tristate_socials() as $tristate_slug => $tristate_social ) : ?>
							<li>
								<a href="<?php echo esc_url( $tristate_social[1] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $tristate_social[0] ); ?>">
									<?php tristate_icon( $tristate_slug, $tristate_social[2] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</aside>
		</div>
	</section>

</div>
<?php
get_footer();
