<?php
/**
 * Testimonials slider — Figma 25:9.
 * Quotes are taken verbatim from tristatehs.com (first one as written in Figma).
 *
 * @package tristate
 */

$tristate_testimonials = array(
	array(
		'name'     => 'Barrister Akinyele Oladeji',
		'short'    => 'Barrister A. Oladeji',
		'initials' => 'AO',
		'quote'    => 'Even a thousand times cannot begin to express my heartfelt gratitude for the patient, thorough and gracious manner with which you handled my case yesterday. Indeed there are still angels amongst men.',
	),
	array(
		'name'     => 'Benjamin',
		'short'    => 'Benjamin',
		'initials' => 'B',
		'quote'    => 'The Money we were billed at Tristate Cardiovascular is far less compared to the quote gotten from USA and India.',
	),
	array(
		'name'     => 'Bamidele',
		'short'    => 'Bamidele',
		'initials' => 'B',
		'quote'    => 'Courteous and very professional service, the staff was extremely helpful, caring and compassionate.',
	),
	array(
		'name'     => 'Kolawole',
		'short'    => 'Kolawole',
		'initials' => 'K',
		'quote'    => 'Your service is fantastic, I wish I have a better adjective to qualify you people.',
	),
);
?>
<section class="testimonials" aria-labelledby="testimonials-title" data-testimonials>
	<div class="container testimonials__inner">
		<h2 class="section-label testimonials__label" id="testimonials-title" data-reveal><?php esc_html_e( 'What Clients Say About Us', 'tristate' ); ?></h2>

		<span class="testimonials__mark" aria-hidden="true" data-reveal="up">&ldquo;</span>

		<div class="testimonials__stage" aria-live="polite">
			<?php foreach ( $tristate_testimonials as $tristate_i => $tristate_t ) : ?>
				<figure class="testimonial<?php echo 0 === $tristate_i ? ' is-active' : ''; ?>" id="testimonial-<?php echo (int) $tristate_i; ?>" data-testimonial <?php echo 0 === $tristate_i ? '' : 'aria-hidden="true"'; ?>>
					<blockquote class="testimonial__quote"><p><?php echo esc_html( $tristate_t['quote'] ); ?></p></blockquote>
					<figcaption class="testimonial__author">
						<span class="testimonial__stars" role="img" aria-label="<?php esc_attr_e( '5 out of 5 stars', 'tristate' ); ?>">★★★★★</span>
						<span class="testimonial__name"><?php echo esc_html( $tristate_t['name'] ); ?></span>
					</figcaption>
				</figure>
			<?php endforeach; ?>
		</div>

		<div class="testimonials__nav" data-reveal="up">
			<button class="testimonials__arrow" type="button" data-testimonial-prev aria-label="<?php esc_attr_e( 'Previous testimonial', 'tristate' ); ?>">←</button>
			<div class="testimonials__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Choose testimonial', 'tristate' ); ?>">
				<?php foreach ( $tristate_testimonials as $tristate_i => $tristate_t ) : ?>
					<button class="testimonials__tab<?php echo 0 === $tristate_i ? ' is-active' : ''; ?>" type="button" role="tab" aria-selected="<?php echo 0 === $tristate_i ? 'true' : 'false'; ?>" aria-controls="testimonial-<?php echo (int) $tristate_i; ?>" data-testimonial-tab>
						<span class="testimonials__initials" aria-hidden="true"><?php echo esc_html( $tristate_t['initials'] ); ?></span>
						<?php echo esc_html( $tristate_t['short'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>
			<button class="testimonials__arrow testimonials__arrow--next" type="button" data-testimonial-next aria-label="<?php esc_attr_e( 'Next testimonial', 'tristate' ); ?>">→</button>
		</div>
	</div>
</section>
