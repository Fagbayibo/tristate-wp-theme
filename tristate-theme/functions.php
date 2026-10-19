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
require TRISTATE_DIR . '/inc/blog.php';
require TRISTATE_DIR . '/inc/contact.php';
require TRISTATE_DIR . '/inc/downloads.php';
require TRISTATE_DIR . '/inc/icons.php';
require TRISTATE_DIR . '/inc/menu.php';
require TRISTATE_DIR . '/inc/pages.php';
require TRISTATE_DIR . '/inc/people.php';
require TRISTATE_DIR . '/inc/services.php';
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
		'email'           => 'info@tristatehs.com',
		// The one phone line, shown everywhere as the emergency number (0700TRISTATE did not connect).
		'emergency'       => '0810 681 5163',
		'emergency_tel'   => '+2348106815163',
		'appointment_url' => home_url( '/#appointment' ), // Booking form on the homepage.
	);
	return isset( $contact[ $key ] ) ? $contact[ $key ] : '';
}

/** Social profiles: slug (icon in assets/images/icons) => [ label, url, icon size ]. */
function tristate_socials() {
	return array(
		'facebook'  => array( 'Facebook', 'https://web.facebook.com/Tristate-Healthcare-110376837308912', 18 ),
		'x'         => array( 'X', 'https://twitter.com/TristateHs', 18 ),
		'instagram' => array( 'Instagram', 'https://www.instagram.com/tristate_hs/', 18 ),
		'youtube'   => array( 'YouTube', 'https://www.youtube.com/channel/UCqVsmjdjxg9cSz5FDYG7yyw', 20 ),
		'linkedin'  => array( 'LinkedIn', 'https://www.linkedin.com/company/tristate-healthcare-system', 18 ),
	);
}

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	// No menu locations: the navigation follows the services and pages in code (inc/menu.php).
} );

/** Cache-busting version for a theme asset: its last-modified time. */
function tristate_asset_version( $path ) {
	$file = TRISTATE_DIR . $path;
	return file_exists( $file ) ? (string) filemtime( $file ) : TRISTATE_VERSION;
}

/**
 * Designed inner-page templates: their stylesheet (assets/css/<css>.css) and the
 * page slugs that get them automatically. The slug match matters because
 * Elementor's "Page Layout" setting rewrites a page's Template field, so a page
 * left on Default or an Elementor layout still shows its design; any other
 * template chosen wins.
 */
function tristate_landing_templates() {
	return array(
		'page-templates/about-us.php'      => array( 'css' => 'about', 'slugs' => array( 'about-us' ) ),
		'page-templates/our-services.php'  => array( 'css' => 'our-services', 'slugs' => array( 'our-services' ) ),
		'page-templates/service.php'       => array( 'css' => 'service', 'slugs' => tristate_service_slugs() ),
		'page-templates/contact.php'       => array( 'css' => 'contact', 'slugs' => array( 'our-hospitals', 'contact-us' ) ),
		'page-templates/our-team.php'      => array( 'css' => 'info', 'slugs' => array( 'our-team' ) ),
		'page-templates/hmo-partners.php'  => array( 'css' => 'info', 'slugs' => array( 'hmo-partners' ) ),
		'page-templates/patient-guide.php' => array( 'css' => 'info', 'slugs' => array( 'patient-guide' ) ),
	);
}

/** The designed template the current page uses, or ''. */
function tristate_current_landing_template() {
	if ( ! is_page() ) {
		return '';
	}
	$templates = tristate_landing_templates();
	$assigned  = get_page_template_slug( get_queried_object_id() ); // '' when Default
	if ( $assigned && 0 !== strpos( $assigned, 'elementor_' ) ) { // elementor_header_footer, elementor_canvas…
		return isset( $templates[ $assigned ] ) ? $assigned : '';
	}
	$slug = get_post_field( 'post_name', get_queried_object_id() );
	foreach ( $templates as $template => $info ) {
		if ( in_array( $slug, $info['slugs'], true ) ) {
			return $template;
		}
	}
	return '';
}

/** Stylesheet name for the current designed page, or ''. */
function tristate_landing_template() {
	$template = tristate_current_landing_template();
	return $template ? tristate_landing_templates()[ $template ]['css'] : '';
}

add_filter(
	'template_include',
	function ( $template ) {
		$designed = tristate_current_landing_template();
		$file     = $designed ? locate_template( $designed ) : '';
		return $file ? $file : $template;
	},
	99 // after Elementor, which swaps in its own templates around priority 12
);

/**
 * A designed page replaces an old Elementor layout, so on the front end tell
 * Elementor it isn't an Elementor page. Otherwise Elementor loads its whole
 * frontend for content that never prints (and logs "elementorFrontendConfig is
 * not defined"). The editor and its preview still see the real value.
 */
add_filter(
	'get_post_metadata',
	function ( $value, $object_id, $meta_key ) {
		if ( '_elementor_edit_mode' !== $meta_key || is_admin() || ! did_action( 'wp' ) || isset( $_GET['elementor-preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return $value;
		}
		if ( (int) $object_id === get_queried_object_id() && tristate_current_landing_template() ) {
			return array( '' );
		}
		return $value;
	},
	10,
	3
);

add_action( 'wp_enqueue_scripts', function () {
	$landing = tristate_landing_template();

	// Fonts (client brief): Montserrat for headings and interface, EB Garamond for reading text.
	wp_enqueue_style(
		'tristate-fonts',
		'https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Montserrat:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	$css = array( 'tokens', 'base', 'header', 'footer' );
	if ( is_front_page() ) {
		array_push( $css, 'hero', 'landing', 'home', 'appointment' );
	} else {
		$css[] = 'pages'; // Inner pages, blog, search, 404.
		if ( $landing ) {
			array_push( $css, 'landing', $landing );
		} elseif ( tristate_is_blog() ) {
			array_push( $css, 'landing', 'blog' );
		}
	}
	$css[] = 'small-screens'; // Last, so its phone type scale wins.
	$deps = array( 'tristate-fonts' );
	foreach ( $css as $file ) {
		wp_enqueue_style( "tristate-$file", TRISTATE_URI . "/assets/css/$file.css", $deps, tristate_asset_version( "/assets/css/$file.css" ) );
		$deps = array( "tristate-$file" );
	}

	wp_enqueue_script( 'gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js', array(), null, array( 'strategy' => 'defer' ) );
	wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollTrigger.min.js', array( 'gsap' ), null, array( 'strategy' => 'defer' ) );
	wp_enqueue_script( 'tristate-header', TRISTATE_URI . '/assets/js/header.js', array(), tristate_asset_version( '/assets/js/header.js' ), array( 'strategy' => 'defer' ) );
	wp_enqueue_script( 'lenis', 'https://cdn.jsdelivr.net/npm/lenis@1.3.26/dist/lenis.min.js', array(), null, array( 'strategy' => 'defer' ) );
	wp_enqueue_script( 'tristate-smooth-scroll', TRISTATE_URI . '/assets/js/smooth-scroll.js', array( 'lenis', 'gsap-scrolltrigger' ), tristate_asset_version( '/assets/js/smooth-scroll.js' ), array( 'strategy' => 'defer' ) );
	wp_enqueue_script( 'tristate-animations', TRISTATE_URI . '/assets/js/animations.js', array( 'gsap-scrolltrigger' ), tristate_asset_version( '/assets/js/animations.js' ), array( 'strategy' => 'defer' ) );

	if ( is_front_page() ) {
		wp_enqueue_script( 'tristate-hero', TRISTATE_URI . '/assets/js/hero.js', array( 'gsap' ), tristate_asset_version( '/assets/js/hero.js' ), array( 'strategy' => 'defer' ) );
		wp_enqueue_script( 'tristate-landing', TRISTATE_URI . '/assets/js/landing.js', array( 'gsap-scrolltrigger', 'tristate-animations' ), tristate_asset_version( '/assets/js/landing.js' ), array( 'strategy' => 'defer' ) );
		wp_enqueue_script( 'tristate-appointment', TRISTATE_URI . '/assets/js/appointment.js', array(), tristate_asset_version( '/assets/js/appointment.js' ), array( 'strategy' => 'defer' ) );
	}

	if ( is_singular( 'post' ) ) {
		wp_enqueue_script( 'tristate-blog', TRISTATE_URI . '/assets/js/blog.js', array(), tristate_asset_version( '/assets/js/blog.js' ), array( 'strategy' => 'defer' ) );
	}

	if ( 'contact' === $landing ) {
		wp_enqueue_script( 'tristate-contact', TRISTATE_URI . '/assets/js/contact.js', array(), tristate_asset_version( '/assets/js/contact.js' ), array( 'strategy' => 'defer' ) );
	}

	if ( $landing ) {
		wp_enqueue_script( 'tristate-landing', TRISTATE_URI . '/assets/js/landing.js', array( 'gsap-scrolltrigger', 'tristate-animations' ), tristate_asset_version( '/assets/js/landing.js' ), array( 'strategy' => 'defer' ) );
	}

	// Frictionless chat widget (usefrictionless.com). The workspace ID is public by design.
	// Footer rather than defer: WordPress drops the defer strategy when an inline "after" script is attached.
	wp_enqueue_script( 'frictionless', 'https://usefrictionless.com/widget/v1.js', array(), null, array( 'in_footer' => true ) );
	wp_add_inline_script( 'frictionless', 'window.Frictionless && Frictionless.init({ company_id: "62107b4e-e09a-4c94-8960-309af27dea94" });' ); // the widget may fail to load
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
