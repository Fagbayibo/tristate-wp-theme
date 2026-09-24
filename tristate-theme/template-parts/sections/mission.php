<?php
/**
 * Mission & Vision — Figma 25:5.
 *
 * @package tristate
 */

$tristate_mission_items = array(
	array(
		'title' => __( 'Our Mission', 'tristate' ),
		'text'  => __( 'To provide our patients with affordable, accessible, compassionate and exceptional care in a serene, learning and research environment', 'tristate' ),
	),
	array(
		'title' => __( 'Our Vision', 'tristate' ),
		'text'  => __( 'A premier Healthcare provider, recognized for exceptional and compassionate patient care innovation and research.', 'tristate' ),
	),
);
?>
<section class="mission" aria-labelledby="mission-title">
	<div class="mission__media" data-reveal="clip-left">
		<img src="<?php echo esc_url( TRISTATE_URI . '/assets/images/mission.jpg' ); ?>" width="1100" height="1375" alt="<?php esc_attr_e( 'Tristate surgeon in scrubs standing in an operating theatre', 'tristate' ); ?>" loading="lazy" data-parallax="0.06">

		<blockquote class="mission__quote" data-reveal="up" data-delay="0.4">
			<p>&ldquo;Caring for the heart<br>from the heart.&rdquo;</p>
			<cite><?php esc_html_e( 'Tristate Healthcare System', 'tristate' ); ?></cite>
		</blockquote>
	</div>

	<div class="mission__copy">
		<div class="mission__head" data-reveal-stagger>
			<p class="section-label"><?php esc_html_e( 'Who We Are', 'tristate' ); ?></p>
			<h2 class="section-title" id="mission-title" style="--title-size: 46">Purpose-driven, <em>patient-first</em>.</h2>
		</div>

		<ol class="mission__items">
			<?php foreach ( $tristate_mission_items as $tristate_i => $tristate_item ) : ?>
				<li class="mission__item" data-reveal="up" data-delay="<?php echo esc_attr( $tristate_i * 0.12 ); ?>">
					<span class="mission__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $tristate_i + 1 ) ); ?></span>
					<div>
						<h3 class="mission__item-title"><?php echo esc_html( $tristate_item['title'] ); ?></h3>
						<p class="mission__item-text"><?php echo esc_html( $tristate_item['text'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>

		<div data-reveal="up">
			<a class="btn btn--brand" href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">
				<?php esc_html_e( 'More About Us', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span>
			</a>
		</div>
	</div>
</section>
