<?php
/**
 * Intro statement — Figma 25:4.
 *
 * @package tristate
 */

$tristate_intro_images = array(
	array( 'file' => 'intro-1.jpg', 'w' => 1100, 'h' => 1650, 'speed' => '-0.04' ),
	array( 'file' => 'intro-2.jpg', 'w' => 1100, 'h' => 733, 'speed' => '0.06' ),
	array( 'file' => 'intro-3.jpg', 'w' => 1100, 'h' => 1650, 'speed' => '-0.06' ),
);
?>
<section class="intro" aria-labelledby="intro-title">
	<div class="container">
		<div class="intro__row">
			<p class="section-label section-label--bar" data-reveal><?php esc_html_e( 'Welcome', 'tristate' ); ?></p>
			<h2 class="intro__statement" id="intro-title" data-words-scrub>
				<strong>World-class</strong> healthcare service providers driven by the compassionate move to provide patients with <em>affordable, accessible, compassionate care</em>.
			</h2>
		</div>

		<div class="intro__images">
			<?php foreach ( $tristate_intro_images as $tristate_i => $tristate_img ) : ?>
				<figure class="intro__image intro__image--<?php echo (int) $tristate_i + 1; ?>" data-reveal="clip-up" data-delay="<?php echo esc_attr( $tristate_i * 0.12 ); ?>">
					<img src="<?php echo esc_url( TRISTATE_URI . '/assets/images/' . $tristate_img['file'] ); ?>" width="<?php echo (int) $tristate_img['w']; ?>" height="<?php echo (int) $tristate_img['h']; ?>" alt="" loading="lazy" data-parallax="<?php echo esc_attr( $tristate_img['speed'] ); ?>">
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
