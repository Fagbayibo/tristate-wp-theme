<?php
/**
 * Home: the two hospitals and virtual consultations.
 *
 * @package tristate
 */

$tristate_virtual = tristate_service( 'virtual-consultations' );
?>
<section class="home-visit landing-section" aria-labelledby="visit-title">
	<div class="container">
		<div class="landing-head">
			<div>
				<p class="eyebrow" data-reveal="up"><?php esc_html_e( 'Our Hospitals', 'tristate' ); ?></p>
				<h2 class="section-title" id="visit-title" data-split>Choose how <em>to visit</em></h2>
			</div>
			<p class="landing-lead" data-reveal="up" data-delay="0.2"><?php esc_html_e( 'Find Tristate Hospitals in Lekki, Lagos, and Idi-Ape, Ibadan. For suitable consultations, you can also speak with a clinician remotely.', 'tristate' ); ?></p>
		</div>

		<ul class="visit-grid" data-reveal-stagger>
			<?php foreach ( tristate_locations() as $tristate_place ) : ?>
				<li class="visit-card">
					<span class="visit-card__icon" aria-hidden="true"><?php echo tristate_svg( 'building-hospital', array( 'stroke' => 1.5 ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
					<span class="landing-tag"><?php echo esc_html( $tristate_place['area'] ); ?></span>
					<h3 class="visit-card__title"><?php echo esc_html( $tristate_place['name'] ); ?></h3>
					<p class="visit-card__text"><?php echo esc_html( $tristate_place['address'] ); ?></p>
					<a class="text-link" href="<?php echo esc_url( tristate_directions_url( $tristate_place['address'] ) ); ?>" target="_blank" rel="noopener">
						<?php
						/* translators: %s: hospital short name, e.g. Lekki */
						printf( esc_html__( 'Get Directions to %s', 'tristate' ), esc_html( $tristate_place['short'] ) );
						?>
						<span class="btn__arrow" aria-hidden="true">↗</span>
					</a>
				</li>
			<?php endforeach; ?>
			<li class="visit-card visit-card--virtual">
				<span class="visit-card__icon" aria-hidden="true"><?php echo tristate_svg( 'video', array( 'stroke' => 1.5 ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
				<span class="landing-tag"><?php esc_html_e( 'Remote', 'tristate' ); ?></span>
				<h3 class="visit-card__title"><?php echo esc_html( $tristate_virtual['title'] ); ?></h3>
				<p class="visit-card__text"><?php echo esc_html( $tristate_virtual['intro'][0] ); ?></p>
				<a class="text-link" href="<?php echo esc_url( tristate_service_url( 'virtual-consultations' ) ); ?>"><?php esc_html_e( 'Virtual Consultations', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span></a>
			</li>
		</ul>

		<div class="btn-row home-visit__actions" data-reveal="up">
			<a class="btn btn--brand" href="<?php echo esc_url( tristate_page_url( 'our-hospitals' ) ); ?>"><?php esc_html_e( 'Locations and Directions', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span></a>
			<a class="btn btn--outline-brand" href="<?php echo esc_url( tristate_service_url( 'virtual-consultations' ) ); ?>"><?php esc_html_e( 'Virtual Consultations', 'tristate' ); ?></a>
		</div>
	</div>
</section>
