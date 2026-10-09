<?php
/**
 * Home hero slider — Figma 25:3 (Hero + Nav, Variation B).
 *
 * Each headline is a list of [text, italic] segments so the italic phrase from
 * the design can be expressed per slide. Slides 2–3 currently reuse slide 1's
 * image and intro copy — only slide 1 is fully designed in Figma.
 *
 * @package tristate
 */

$tristate_slides = array(
	array(
		'tab'      => 'A premier Healthcare provider',
		'headline' => array( array( 'A premier', false ), array( 'Healthcare provider', true ), array( '.', false ) ),
		'text'     => 'We are known for exceptional and compassionate patient care innovation and research.',
		'image'    => TRISTATE_URI . '/assets/images/hero-home.jpg',
	),
	array(
		'tab'      => 'Caring for the heart from the heart',
		'headline' => array( array( 'Caring for the heart', false ), array( 'from the heart', true ), array( '.', false ) ),
		'text'     => 'We are known for exceptional and compassionate patient care innovation and research.',
		'image'    => TRISTATE_URI . '/assets/images/hero-care.jpg',
	),
	array(
		'tab'      => 'Foremost Cardiac Centre in Nigeria',
		'headline' => array( array( 'Foremost', false ), array( 'Cardiac Centre', true ), array( 'in Nigeria.', false ) ),
		'text'     => 'We are known for exceptional and compassionate patient care innovation and research.',
		'image'    => TRISTATE_URI . '/assets/images/hero-cardiac.jpg',
	),
);

/** Wrap each word in masks so it can rise into view. */
if ( ! function_exists( 'tristate_hero_headline' ) ) :
function tristate_hero_headline( $segments ) {
	$out = '';
	foreach ( $segments as $segment ) {
		list( $text, $italic ) = $segment;
		$words = preg_split( '/\s+/', trim( $text ) );
		foreach ( $words as $word ) {
			// Attach punctuation-only segments to the previous word (no leading space).
			$is_punct = (bool) preg_match( '/^[.,!?]$/', $word );
			$out     .= ( $out && ! $is_punct ? ' ' : '' );
			$out     .= sprintf(
				'<span class="hero__word%s"><span>%s</span></span>',
				$italic ? ' is-italic' : '',
				esc_html( $word )
			);
		}
	}
	return $out;
}
endif;
?>
<section class="hero" data-hero aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Highlights', 'tristate' ); ?>">
	<div class="hero__media" aria-hidden="true">
		<?php foreach ( $tristate_slides as $tristate_i => $tristate_slide ) : ?>
			<img class="hero__image<?php echo 0 === $tristate_i ? ' is-active' : ''; ?>" src="<?php echo esc_url( $tristate_slide['image'] ); ?>" alt="" width="1600" height="1067" <?php echo 0 === $tristate_i ? 'fetchpriority="high"' : 'loading="lazy"'; ?> data-hero-image>
		<?php endforeach; ?>
		<div class="hero__overlay"></div>
		<div class="hero__overlay-top"></div>
	</div>

	<div class="container hero__inner">
		<div class="hero__copy">
			<p class="eyebrow hero__eyebrow" data-hero-intro><?php esc_html_e( 'Tristate Healthcare System', 'tristate' ); ?></p>

			<div class="hero__slides">
				<?php foreach ( $tristate_slides as $tristate_i => $tristate_slide ) : ?>
					<div class="hero__slide<?php echo 0 === $tristate_i ? ' is-active' : ''; ?>" id="hero-slide-<?php echo (int) $tristate_i; ?>" role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( sprintf( '%d / %d', $tristate_i + 1, count( $tristate_slides ) ) ); ?>" <?php echo 0 === $tristate_i ? '' : 'aria-hidden="true"'; ?> data-hero-slide>
						<?php $tristate_tag = 0 === $tristate_i ? 'h1' : 'h2'; ?>
						<<?php echo $tristate_tag; ?> class="hero__title"><?php echo tristate_hero_headline( $tristate_slide['headline'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped per word. ?></<?php echo $tristate_tag; ?>>
						<p class="hero__text"><?php echo esc_html( $tristate_slide['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="hero__buttons" data-hero-intro>
				<a class="btn btn--primary" href="<?php echo esc_url( tristate_contact( 'appointment_url' ) ); ?>">
					<?php esc_html_e( 'Book Appointment', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span>
				</a>
				<a class="btn btn--ghost" href="#our-story">
					<span class="btn--ghost__play"><?php tristate_icon( 'play', 16 ); ?></span>
					<?php esc_html_e( 'Watch Our Story', 'tristate' ); ?>
				</a>
			</div>
		</div>


		<div class="hero__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Choose slide', 'tristate' ); ?>" data-hero-intro>
			<?php foreach ( $tristate_slides as $tristate_i => $tristate_slide ) : ?>
				<button class="hero__tab<?php echo 0 === $tristate_i ? ' is-active' : ''; ?>" type="button" role="tab" aria-selected="<?php echo 0 === $tristate_i ? 'true' : 'false'; ?>" aria-controls="hero-slide-<?php echo (int) $tristate_i; ?>" data-hero-tab>
					<span class="hero__tab-progress" aria-hidden="true"></span>
					<span class="hero__tab-num"><?php echo esc_html( sprintf( '%02d', $tristate_i + 1 ) ); ?></span>
					<span class="hero__tab-title"><?php echo esc_html( $tristate_slide['tab'] ); ?></span>
				</button>
			<?php endforeach; ?>
		</div>
	</div>
</section>
