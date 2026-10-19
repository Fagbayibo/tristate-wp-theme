<?php
/**
 * Home: areas of care as short cards linking to the service pages. The full
 * catalogue lives on Our Services (brief: not on the homepage).
 *
 * @package tristate
 */

$tristate_services = tristate_services();
$tristate_groups   = tristate_service_groups();
unset( $tristate_groups['virtual'] ); // shown under "Choose how to visit"
?>
<section class="home-care landing-section" id="care" aria-labelledby="care-title">
	<div class="container">
		<div class="landing-head">
			<div>
				<p class="eyebrow" data-reveal="up"><?php esc_html_e( 'Our Services', 'tristate' ); ?></p>
				<h2 class="section-title" id="care-title" data-split>Care for your everyday and <em>specialist needs</em></h2>
			</div>
		</div>

		<ul class="care-grid" data-reveal-stagger>
			<?php foreach ( $tristate_groups as $tristate_key => $tristate_group ) : ?>
				<?php
				// A one-service area links straight to that service's page.
				$tristate_single = 1 === count( $tristate_group['services'] ) && $tristate_services[ $tristate_group['services'][0] ]['title'] === $tristate_group['label'];
				$tristate_href   = $tristate_single ? tristate_service_url( $tristate_group['services'][0] ) : tristate_page_url( 'our-services', $tristate_key );
				?>
				<li class="care-card">
					<span class="care-card__icon" aria-hidden="true"><?php echo tristate_svg( $tristate_group['icon'], array( 'stroke' => 1.5 ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
					<h3 class="care-card__title"><a href="<?php echo esc_url( $tristate_href ); ?>"><?php echo esc_html( $tristate_group['label'] ); ?></a></h3>
					<p class="care-card__text"><?php echo esc_html( $tristate_group['summary'] ); ?></p>
					<?php if ( ! $tristate_single ) : ?>
						<ul class="care-card__links">
							<?php foreach ( $tristate_group['services'] as $tristate_slug ) : ?>
								<li><a href="<?php echo esc_url( tristate_service_url( $tristate_slug ) ); ?>"><?php echo esc_html( $tristate_services[ $tristate_slug ]['title'] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
			<li class="care-card care-card--all">
				<a class="care-card__all" href="<?php echo esc_url( tristate_page_url( 'our-services' ) ); ?>">
					<span><?php esc_html_e( 'View All Services', 'tristate' ); ?></span>
					<span class="care-card__all-circle" aria-hidden="true"><?php echo tristate_svg( 'arrow-up-right', array( 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
				</a>
			</li>
		</ul>
	</div>
</section>
