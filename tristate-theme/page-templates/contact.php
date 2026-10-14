<?php
/**
 * Template Name: Contact Us
 *
 * The Contact Us page gets this design automatically from its slug (see
 * functions.php). The message form is handled in inc/contact.php.
 *
 * @package tristate
 */

$tristate_status    = isset( $_GET['message'] ) ? sanitize_key( $_GET['message'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification -- display only.
$tristate_locations = tristate_locations();
$tristate_map       = static function ( $address ) {
	return 'https://maps.google.com/maps?q=' . rawurlencode( $address ) . '&t=m&z=15&output=embed';
};
$tristate_route     = static function ( $address ) {
	return 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode( $address );
};

// Quick contact cards: Tabler icon, label, lines [ text, href ].
$tristate_quick = array(
	array(
		'phone',
		'Care line',
		array(
			array( tristate_contact( 'emergency' ), 'tel:' . tristate_contact( 'emergency_tel' ) ),
		),
	),
	array(
		'mail',
		'Email',
		array( array( tristate_contact( 'email' ), 'mailto:' . tristate_contact( 'email' ) ) ),
	),
	array(
		'clock',
		'Opening hours',
		array( array( '8 am – 5 pm, every day', '' ), array( 'Emergency care 24/7', '' ) ),
	),
);

get_header();
?>
<div class="landing landing--contact">

	<!-- Hero ------------------------------------------------------------------ -->
	<section class="ct-hero">
		<div class="blog-head__glow" aria-hidden="true"></div>
		<div class="container">
			<div class="ct-hero__head">
				<p class="eyebrow eyebrow--center" data-reveal="up">Contact us</p>
				<h1 class="landing-title" data-split data-delay="0.15">We are <em>here for you</em></h1>
				<p class="landing-lead" data-reveal="up" data-delay="0.35">If you need urgent health advice, please call on the numbers below. For everything else, send us a message or visit one of our centres.</p>
			</div>

			<ul class="ct-quick" data-reveal-stagger>
				<?php foreach ( $tristate_quick as $tristate_card ) : ?>
					<li class="ct-quick__card">
						<span class="ct-quick__icon"><?php echo tristate_svg( $tristate_card[0] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
						<span class="ct-quick__label"><?php echo esc_html( $tristate_card[1] ); ?></span>
						<?php foreach ( $tristate_card[2] as $tristate_line ) : ?>
							<?php if ( $tristate_line[1] ) : ?>
								<a class="ct-quick__value" href="<?php echo esc_url( $tristate_line[1], array( 'tel', 'mailto' ) ); ?>"><?php echo esc_html( $tristate_line[0] ); ?></a>
							<?php else : ?>
								<span class="ct-quick__value"><?php echo esc_html( $tristate_line[0] ); ?></span>
							<?php endif; ?>
						<?php endforeach; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<!-- Form + emergency ------------------------------------------------------- -->
	<section class="ct-main" id="contact-form">
		<div class="container ct-main__grid">
			<div class="ct-form-card" data-reveal="up">
				<div class="ct-form-wrap" data-message-form-wrap<?php echo 'sent' === $tristate_status ? ' hidden' : ''; ?>>
					<p class="eyebrow">Send a message</p>
					<h2 class="section-title" style="--title-size: 44" data-split>How can we <em>help you?</em></h2>
					<p class="ct-form-card__intro">Tell us a little about what you need and the right team will get back to you by email or phone.</p>

					<form class="ct-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-message-form data-phone="<?php echo esc_attr( tristate_contact( 'emergency' ) ); ?>" novalidate>
						<input type="hidden" name="action" value="tristate_message">
						<?php wp_nonce_field( 'tristate_message', 'tristate_message_nonce' ); ?>
						<div class="ct-form__hp" aria-hidden="true">
							<label for="msg-website">Website</label>
							<input type="text" id="msg-website" name="website" tabindex="-1" autocomplete="off">
						</div>

						<div class="ct-form__row">
							<div class="field">
								<label class="ct-form__label" for="msg-name">Full name</label>
								<input class="field__control" type="text" id="msg-name" name="name" autocomplete="name" required>
							</div>
							<div class="field">
								<label class="ct-form__label" for="msg-email">Email address</label>
								<input class="field__control" type="email" id="msg-email" name="email" autocomplete="email" required>
							</div>
						</div>

						<div class="ct-form__row">
							<div class="field">
								<label class="ct-form__label" for="msg-phone">Phone <span>(optional)</span></label>
								<input class="field__control" type="tel" id="msg-phone" name="phone" autocomplete="tel" inputmode="tel">
							</div>
							<div class="field field--select">
								<label class="ct-form__label" for="msg-topic">Topic</label>
								<select class="field__control" id="msg-topic" name="topic" required>
									<option value="" selected disabled>Choose a topic</option>
									<?php foreach ( tristate_message_topics() as $tristate_topic ) : ?>
										<option><?php echo esc_html( $tristate_topic ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>

						<div class="field">
							<label class="ct-form__label" for="msg-message">Message</label>
							<textarea class="field__control ct-form__textarea" id="msg-message" name="message" rows="6" required></textarea>
						</div>

						<div class="ct-form__foot">
							<button class="btn btn--brand ct-form__submit" type="submit" data-loading="Sending…">
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
					<p>Thank you for reaching out. Our team will get back to you shortly.</p>
					<p class="ct-success__note">Need urgent care? Call <a href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>"><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a></p>
				</div>
			</div>

			<aside class="ct-aside">
				<div class="ct-urgent" data-reveal="up" data-delay="0.15" data-observe>
					<p class="ct-urgent__label"><span class="pulse-dot" aria-hidden="true"></span>24/7 Emergency line</p>
					<a class="ct-urgent__number" href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>"><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a>
					<p class="ct-urgent__text">For a medical emergency, call now rather than sending a message. Our emergency unit and standby ambulances are ready around the clock.</p>
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

	<!-- Locations + map ----------------------------------------------------------- -->
	<section class="ct-locations" id="locations">
		<div class="container">
			<div class="landing-head">
				<div>
					<p class="eyebrow" data-reveal="up">Our locations</p>
					<h2 class="section-title" style="--title-size: 46" data-split>Find a Tristate <em>centre near you</em></h2>
				</div>
				<p class="landing-lead" data-reveal="up" data-delay="0.25">Our centres in Lagos and Ibadan. Choose one to see it on the map.</p>
			</div>

			<div class="ct-locations__grid">
				<ol class="ct-places" data-reveal-stagger data-places>
					<?php foreach ( $tristate_locations as $tristate_i => $tristate_place ) : ?>
						<li class="ct-place<?php echo 0 === $tristate_i ? ' is-active' : ''; ?>">
							<button class="ct-place__pick" type="button" data-map="<?php echo esc_url( $tristate_map( $tristate_place[2] ) ); ?>" aria-pressed="<?php echo 0 === $tristate_i ? 'true' : 'false'; ?>">
								<span class="ct-place__num"><?php echo esc_html( sprintf( '%02d', $tristate_i + 1 ) ); ?></span>
								<span class="ct-place__text">
									<span class="ct-place__name"><?php echo esc_html( $tristate_place[0] ); ?></span>
									<span class="ct-place__address"><?php echo esc_html( $tristate_place[2] ); ?></span>
								</span>
								<span class="landing-tag ct-place__tag"><?php echo esc_html( $tristate_place[1] ); ?></span>
							</button>
							<a class="ct-place__route" href="<?php echo esc_url( $tristate_route( $tristate_place[2] ) ); ?>" target="_blank" rel="noopener">
								Get directions <span class="screen-reader-text">to <?php echo esc_html( $tristate_place[0] ); ?></span><span aria-hidden="true">↗</span>
							</a>
						</li>
					<?php endforeach; ?>
				</ol>

				<div class="ct-map" data-reveal="clip-up">
					<iframe src="<?php echo esc_url( $tristate_map( $tristate_locations[0][2] ) ); ?>" title="<?php esc_attr_e( 'Map of the selected Tristate centre', 'tristate' ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen data-map-frame></iframe>
				</div>
			</div>
		</div>
	</section>

</div>
<?php
get_footer();
