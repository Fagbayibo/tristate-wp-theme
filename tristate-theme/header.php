<?php
/**
 * Site header: top contact strip + main navigation.
 * On the front page it overlays the hero (transparent); elsewhere it is solid.
 *
 * @package tristate
 */

$tristate_overlay = is_front_page();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'tristate' ); ?></a>

<header class="site-header<?php echo $tristate_overlay ? ' site-header--overlay' : ''; ?>" data-header>
	<div class="topbar">
		<div class="container topbar__inner">
			<div class="topbar__contacts">
				<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', tristate_contact( 'phone' ) ) ); ?>"><?php echo esc_html( tristate_contact( 'phone' ) ); ?></a>
				<a href="mailto:<?php echo esc_attr( tristate_contact( 'email' ) ); ?>"><?php echo esc_html( tristate_contact( 'email' ) ); ?></a>
			</div>
			<a class="topbar__emergency" href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>">
				<?php
				/* translators: %s: emergency phone number */
				printf( esc_html__( 'Emergency: %s', 'tristate' ), esc_html( tristate_contact( 'emergency' ) ) );
				?>
				<span aria-hidden="true">&nbsp;·&nbsp;</span> 24/7
			</a>
		</div>
	</div>

	<div class="navbar">
		<div class="navbar__bar" data-navbar>
			<div class="container navbar__inner">
				<a class="navbar__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<img src="<?php echo esc_url( TRISTATE_URI . '/assets/images/logo-white.png' ); ?>" width="198" height="60" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				</a>

				<nav class="navbar__nav" id="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'tristate' ); ?>" data-lenis-prevent>
					<?php
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'nav-menu',
						'depth'          => 2,
						'fallback_cb'    => 'tristate_primary_menu_fallback',
						'walker'         => new Tristate_Primary_Walker(),
					) );
					?>
					<div class="drawer-foot">
						<a class="btn btn--light navbar__cta navbar__cta--mobile" href="<?php echo esc_url( tristate_contact( 'appointment_url' ) ); ?>"><?php esc_html_e( 'Book Appointment', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span></a>
						<div class="drawer-foot__contacts">
							<a href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>">
								<span><?php esc_html_e( '24/7 Emergency', 'tristate' ); ?></span>
								<strong><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></strong>
							</a>
							<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', tristate_contact( 'phone' ) ) ); ?>">
								<span><?php esc_html_e( 'Call us', 'tristate' ); ?></span>
								<strong><?php echo esc_html( tristate_contact( 'phone_alt' ) ); ?></strong>
							</a>
						</div>
					</div>
				</nav>

				<a class="btn btn--light navbar__cta" href="<?php echo esc_url( tristate_contact( 'appointment_url' ) ); ?>"><?php esc_html_e( 'Book Appointment', 'tristate' ); ?></a>

				<button class="navbar__toggle" type="button" aria-controls="site-nav" aria-expanded="false" data-nav-toggle>
					<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'tristate' ); ?></span>
					<span class="navbar__toggle-lines" aria-hidden="true"></span>
				</button>
			</div>
		</div>
	</div>
</header>

<main id="main" class="site-main">
