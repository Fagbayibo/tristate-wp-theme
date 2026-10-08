<?php
/**
 * Tristate theme setup.
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

define( 'TRISTATE_VERSION', wp_get_theme( get_template() )->get( 'Version' ) ); // single source: style.css
define( 'TRISTATE_URI', get_template_directory_uri() );
define( 'TRISTATE_DIR', get_template_directory() );

require TRISTATE_DIR . '/inc/appointments.php';
require TRISTATE_DIR . '/inc/downloads.php';
require TRISTATE_DIR . '/inc/updater.php';

// Plugins load before the theme, so Elementor has already announced itself here.
if ( did_action( 'elementor/loaded' ) ) {
	require TRISTATE_DIR . '/inc/elementor-compat.php';
}

/**
 * Site-wide contact details. Kept in one place so the header, hero and footer
 * never drift apart. (Candidates for an ACF options page later.)
 */
function tristate_contact( $key ) {
	$contact = array(
		'phone'           => '+234 8106815163',
		'phone_alt'       => '08106815163',
		'email'           => 'info@tristatehs.com',
		'emergency'       => '0700TRISTATE',
		'emergency_tel'   => '070087478283', // 0700-TRISTATE on a phone keypad — TODO: confirm dialable digits.
		'appointment_url' => home_url( '/#appointment' ), // Booking form on the homepage.
	);
	return isset( $contact[ $key ] ) ? $contact[ $key ] : '';
}

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'tristate' ),
		'footer'  => __( 'Footer Quick Links', 'tristate' ),
	) );
} );

/** Cache-busting version for a theme asset: its last-modified time. */
function tristate_asset_version( $path ) {
	$file = TRISTATE_DIR . $path;
	return file_exists( $file ) ? (string) filemtime( $file ) : TRISTATE_VERSION;
}

/** Designed inner-page templates → their stylesheet (assets/css/<name>.css). */
function tristate_landing_template() {
	$templates = array(
		'page-templates/about-us.php'     => 'about',
		'page-templates/our-services.php' => 'our-services',
	);
	foreach ( $templates as $template => $name ) {
		if ( is_page_template( $template ) ) {
			return $name;
		}
	}
	return '';
}

/**
 * URL of the first published page using a page template, so templates can link
 * to each other whatever slug the page was given.
 */
function tristate_template_url( $template, $fallback_path ) {
	$pages = get_posts( array(
		'post_type'   => 'page',
		'post_status' => 'publish',
		'meta_key'    => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery
		'meta_value'  => $template, // phpcs:ignore WordPress.DB.SlowDBQuery
		'numberposts' => 1,
		'fields'      => 'ids',
	) );
	return $pages ? get_permalink( $pages[0] ) : home_url( $fallback_path );
}

add_action( 'wp_enqueue_scripts', function () {
	$landing = tristate_landing_template();

	// Fonts: Playfair Display (headings) + Poppins (UI/body).
	wp_enqueue_style(
		'tristate-fonts',
		'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:wght@400;500;600&display=swap',
		array(),
		null
	);

	$css = array( 'tokens', 'base', 'header', 'footer' );
	if ( is_front_page() ) {
		array_push( $css, 'hero', 'intro', 'mission', 'services', 'stats', 'why', 'testimonials', 'news', 'appointment' );
	} else {
		$css[] = 'pages'; // Inner pages, blog, search, 404.
		if ( $landing ) {
			array_push( $css, 'landing', $landing );
		}
	}
	$deps = array( 'tristate-fonts' );
	foreach ( $css as $file ) {
		wp_enqueue_style( "tristate-$file", TRISTATE_URI . "/assets/css/$file.css", $deps, tristate_asset_version( "/assets/css/$file.css" ) );
		$deps = array( "tristate-$file" );
	}

	wp_enqueue_script( 'gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js', array(), null, array( 'strategy' => 'defer' ) );
	wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollTrigger.min.js', array( 'gsap' ), null, array( 'strategy' => 'defer' ) );
	wp_enqueue_script( 'tristate-header', TRISTATE_URI . '/assets/js/header.js', array(), tristate_asset_version( '/assets/js/header.js' ), array( 'strategy' => 'defer' ) );
	wp_enqueue_script( 'tristate-animations', TRISTATE_URI . '/assets/js/animations.js', array( 'gsap-scrolltrigger' ), tristate_asset_version( '/assets/js/animations.js' ), array( 'strategy' => 'defer' ) );

	if ( is_front_page() ) {
		wp_enqueue_script( 'tristate-hero', TRISTATE_URI . '/assets/js/hero.js', array( 'gsap' ), tristate_asset_version( '/assets/js/hero.js' ), array( 'strategy' => 'defer' ) );
		wp_enqueue_script( 'tristate-testimonials', TRISTATE_URI . '/assets/js/testimonials.js', array( 'gsap' ), tristate_asset_version( '/assets/js/testimonials.js' ), array( 'strategy' => 'defer' ) );
		wp_enqueue_script( 'tristate-appointment', TRISTATE_URI . '/assets/js/appointment.js', array(), tristate_asset_version( '/assets/js/appointment.js' ), array( 'strategy' => 'defer' ) );
	}

	if ( $landing ) {
		wp_enqueue_script( 'tristate-landing', TRISTATE_URI . '/assets/js/landing.js', array( 'gsap-scrolltrigger', 'tristate-animations' ), tristate_asset_version( '/assets/js/landing.js' ), array( 'strategy' => 'defer' ) );
	}

	// Frictionless chat widget (usefrictionless.com). The workspace ID is public by design.
	// Footer rather than defer: WordPress drops the defer strategy when an inline "after" script is attached.
	wp_enqueue_script( 'frictionless', 'https://usefrictionless.com/widget/v1.js', array(), null, array( 'in_footer' => true ) );
	wp_add_inline_script( 'frictionless', 'Frictionless.init({ company_id: "62107b4e-e09a-4c94-8960-309af27dea94" });' );
} );

/**
 * Before first paint: add `anim` to <html> so scroll-reveal elements start hidden
 * (no flash of content). Skipped for reduced motion; undone if GSAP never loads.
 */
add_action( 'wp_head', function () {
	wp_print_inline_script_tag(
		"(function(d){if(window.matchMedia&&matchMedia('(prefers-reduced-motion: reduce)').matches)return;d.classList.add('anim');setTimeout(function(){if(!window.ScrollTrigger)d.classList.remove('anim')},3000)})(document.documentElement);"
	);
}, 1 );

add_filter( 'wp_resource_hints', function ( $urls, $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}, 10, 2 );

/** Mark the current menu item so CSS can colour it gold, as in the design. */
add_filter( 'nav_menu_link_attributes', function ( $atts, $item ) {
	if ( in_array( 'current-menu-item', (array) $item->classes, true ) ) {
		$atts['aria-current'] = 'page';
	}
	return $atts;
}, 10, 2 );

/**
 * Fallback menu used until a menu is assigned in Appearance → Menus.
 * Mirrors the items in the Figma design.
 */
function tristate_primary_menu_fallback() {
	$items = array(
		'Home'         => home_url( '/' ),
		'About Us'     => home_url( '/about-us/' ),
		'Our Team'     => home_url( '/our-team/' ),
		'Our Services' => home_url( '/services/' ),
		'Cases'        => home_url( '/cases/' ),
		'News'         => home_url( '/news/' ),
		'Contact'      => home_url( '/contact/' ),
	);
	echo '<ul class="nav-menu">';
	foreach ( $items as $label => $url ) {
		$is_current = ( 'Home' === $label && is_front_page() );
		$classes    = $is_current ? ' current-menu-item' : '';
		$classes   .= 'Our Services' === $label ? ' menu-item-has-children' : ''; // Shows the ▾ from the design.
		printf(
			'<li class="menu-item%s"><a href="%s"%s>%s</a></li>',
			$classes,
			esc_url( $url ),
			$is_current ? ' aria-current="page"' : '',
			esc_html( $label )
		);
	}
	echo '</ul>';
}

/**
 * Was this page built with Elementor?
 *
 * Pages built in Elementor are rendered untouched (full width, no theme wrapper)
 * so the existing site keeps working while the new pages are built one by one.
 */
function tristate_is_elementor_content( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id || ! did_action( 'elementor/loaded' ) ) {
		return false;
	}
	return 'builder' === get_post_meta( $post_id, '_elementor_edit_mode', true );
}

/** Rough reading time in minutes for the current post (200 words per minute). */
function tristate_reading_time( $post_id = null ) {
	$words = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ? $post_id : get_the_ID() ) ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/** Output an SVG icon from assets/images/icons as an <img>. */
function tristate_icon( $name, $size = 18 ) {
	printf(
		'<img src="%s" width="%d" height="%d" alt="" aria-hidden="true" class="icon">',
		esc_url( TRISTATE_URI . "/assets/images/icons/$name.svg" ),
		(int) $size,
		(int) $size
	);
}
