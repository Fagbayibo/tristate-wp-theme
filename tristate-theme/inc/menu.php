<?php
/**
 * Primary navigation, from the client's brief: Our Services, Our Team, Patient
 * Guide, HMO Partners, Our Hospitals and About Us. Built in code so the grouped
 * services menu always matches the service pages (inc/services.php).
 *
 * The markup matches what header.js and header.css expect: `.nav-menu` items,
 * and for Our Services a `.mega` panel (desktop dropdown, mobile accordion).
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

/** URL of one of the theme's pages, by slug. */
function tristate_page_url( $slug, $anchor = '' ) {
	return home_url( "/$slug/" ) . ( $anchor ? "#$anchor" : '' );
}

/** Top-level items: label => slug. */
function tristate_nav_items() {
	return array(
		'Our Services'  => 'our-services',
		'Our Team'      => 'our-team',
		'Patient Guide' => 'patient-guide',
		'HMO Partners'  => 'hmo-partners',
		'Our Hospitals' => 'our-hospitals',
		'About Us'      => 'about-us',
	);
}

/** Slug of the page being viewed, or ''. */
function tristate_current_slug() {
	return is_page() ? (string) get_post_field( 'post_name', get_queried_object_id() ) : '';
}

/** Print the primary menu. */
function tristate_primary_nav() {
	$current  = tristate_current_slug();
	$services = tristate_services();
	$in_svc   = isset( $services[ $current ] );

	echo '<ul class="nav-menu">';
	foreach ( tristate_nav_items() as $label => $slug ) {
		$is_services = 'our-services' === $slug;
		$classes     = 'menu-item';
		$classes    .= $current === $slug ? ' current-menu-item' : '';
		$classes    .= $is_services && $in_svc ? ' current-menu-ancestor' : '';
		$classes    .= $is_services ? ' menu-item-has-children' : '';

		printf(
			'<li class="%s"><a href="%s"%s>%s</a>',
			esc_attr( $classes ),
			esc_url( tristate_page_url( $slug ) ),
			$current === $slug ? ' aria-current="page"' : '',
			esc_html( $label )
		);
		if ( $is_services ) {
			tristate_services_mega( $current );
		}
		echo '</li>';
	}
	echo '</ul>';
}

/** The Our Services dropdown: every area of care with its services. */
function tristate_services_mega( $current ) {
	$services = tristate_services();
	?>
	<button class="submenu-toggle" type="button" aria-expanded="false" aria-controls="mega-services"><span class="screen-reader-text"><?php esc_html_e( 'Show Our Services links', 'tristate' ); ?></span></button>
	<div class="mega mega--services" id="mega-services" data-mega>
		<div class="mega__inner">
			<div class="mega-groups">
				<?php foreach ( tristate_service_groups() as $tristate_key => $tristate_group ) : ?>
					<div class="mega-group">
						<a class="mega-group__title" href="<?php echo esc_url( tristate_page_url( 'our-services', $tristate_key ) ); ?>"><?php echo esc_html( $tristate_group['label'] ); ?></a>
						<ul class="sub-menu">
							<?php foreach ( $tristate_group['services'] as $tristate_slug ) : ?>
								<li class="menu-item<?php echo $current === $tristate_slug ? ' current-menu-item' : ''; ?>">
									<a href="<?php echo esc_url( tristate_service_url( $tristate_slug ) ); ?>"<?php echo $current === $tristate_slug ? ' aria-current="page"' : ''; ?>>
										<span class="sub-menu__icon"><?php echo tristate_svg( $services[ $tristate_slug ]['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
										<span class="sub-menu__label"><?php echo esc_html( $services[ $tristate_slug ]['title'] ); ?></span>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="mega-feature">
				<p class="mega-feature__label"><?php esc_html_e( 'Emergency line', 'tristate' ); ?></p>
				<a class="mega-feature__number" href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>"><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a>
				<p class="mega-feature__text"><?php esc_html_e( 'For a medical emergency, seek immediate care at the nearest emergency department.', 'tristate' ); ?></p>
				<a class="mega-feature__all" href="<?php echo esc_url( tristate_page_url( 'our-services' ) ); ?>"><?php esc_html_e( 'View All Services', 'tristate' ); ?> <span aria-hidden="true">→</span></a>
				<a class="mega-feature__all mega-feature__all--sub" href="<?php echo esc_url( tristate_page_url( 'our-services', 'a-z' ) ); ?>"><?php esc_html_e( 'A–Z directory', 'tristate' ); ?> <span aria-hidden="true">→</span></a>
			</div>
		</div>
	</div>
	<?php
}
