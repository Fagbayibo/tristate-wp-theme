<?php
/**
 * The pages the theme designs, created once if the site does not have them yet,
 * and redirects from retired pages to their replacements.
 *
 * The page content lives in the theme (page-templates/*), so a new page only
 * needs to exist with the right slug; functions.php routes it to its template.
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

/** Bump when a release adds pages to tristate_required_pages(). */
const TRISTATE_PAGES_VERSION = '2026-10-19';

/** Slug => title of every page the navigation links to. */
function tristate_required_pages() {
	$pages = array(
		'our-services'  => 'Our Services',
		'our-team'      => 'Our Team',
		'patient-guide' => 'Patient Guide',
		'hmo-partners'  => 'HMO Partners',
		'our-hospitals' => 'Our Hospitals',
		'about-us'      => 'About Us',
	);
	foreach ( tristate_services() as $slug => $service ) {
		$pages[ $slug ] = $service['title'];
	}
	return $pages;
}

/** Create any missing page (published, empty: the template supplies the content). */
add_action( 'init', function () {
	if ( TRISTATE_PAGES_VERSION === get_option( 'tristate_pages_version' ) ) {
		return;
	}
	update_option( 'tristate_pages_version', TRISTATE_PAGES_VERSION ); // first, so parallel requests don't repeat this

	foreach ( tristate_required_pages() as $slug => $title ) {
		if ( get_page_by_path( $slug, OBJECT, 'page' ) ) {
			continue;
		}
		wp_insert_post( array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => $title,
			'post_name'   => $slug,
		) );
	}
}, 20 );

/** Retired pages: send old links (and search results) to the page that replaced them. */
add_action( 'template_redirect', function () {
	global $wp;
	$path    = trim( (string) $wp->request, '/' );
	$retired = tristate_retired_pages();
	if ( isset( $retired[ $path ] ) && get_page_by_path( $retired[ $path ], OBJECT, 'page' ) ) {
		wp_safe_redirect( home_url( '/' . $retired[ $path ] . '/' ), 301 );
		exit;
	}
} );
