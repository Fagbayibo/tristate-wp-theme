<?php
/**
 * Blog helpers: which screens use the blog design, tidier post titles and the
 * share links on a post.
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

/** Blog listing, category/tag/date archives, search results or a single post. */
function tristate_is_blog() {
	return is_home() || is_category() || is_tag() || is_date() || is_author() || is_search() || is_singular( 'post' );
}

/**
 * Several older posts were titled in capitals ("COMPLEX HEART SURGERY"), which
 * shouts in Playfair. On the front end, a title that is mostly capitals is
 * shown in title case, keeping medical acronyms and any word already written in
 * mixed case ("ANOTHER FIRST IN NIGERIA: Uniportal VATS"). Others are untouched.
 */
function tristate_title_case( $title ) {
	$capitals = preg_match_all( '/\p{Lu}/u', $title );
	$smalls   = preg_match_all( '/\p{Ll}/u', $title );
	if ( ! $capitals || $smalls * 2 > $capitals ) {
		return $title; // already sentence or title case
	}

	$keep  = array( 'PDA', 'ASD', 'VSD', 'CABG', 'VATS', 'COVID', 'COVID-19', 'ICU', 'CCU', 'ECG', 'EKG', 'OPD', 'ABPM', 'MRI', 'CT', 'HIV', 'ENT', 'IVF', 'NHIS', 'II', 'III' );
	$small = array( 'a', 'an', 'and', 'as', 'at', 'by', 'for', 'in', 'of', 'on', 'or', 'the', 'to', 'with' );
	$words = explode( ' ', $title );

	foreach ( $words as $i => $word ) {
		$bare = trim( $word, '.,:;!?()"\'' );
		if ( preg_match( '/\p{Ll}/u', $bare ) || in_array( $bare, $keep, true ) || ( preg_match( '/\d/', $bare ) && ! preg_match( '/^\d+$/', $bare ) ) ) {
			continue; // mixed-case words, acronyms, and codes like COVID-19
		}
		$lower       = mb_strtolower( $word );
		$words[ $i ] = ( $i > 0 && in_array( trim( $lower, '.,:;!?()' ), $small, true ) ) ? $lower : mb_convert_case( $lower, MB_CASE_TITLE );
	}

	return implode( ' ', $words );
}

add_filter(
	'the_title',
	function ( $title, $post_id = 0 ) {
		if ( is_admin() || ! $post_id || 'post' !== get_post_type( $post_id ) ) {
			return $title;
		}
		return tristate_title_case( $title );
	},
	10,
	2
);

/** "5 min read" for a post. */
function tristate_read_label( $post_id = null ) {
	$minutes = tristate_reading_time( $post_id );
	/* translators: %s: minutes */
	return sprintf( _n( '%s min read', '%s min read', $minutes, 'tristate' ), number_format_i18n( $minutes ) );
}

/** Share targets for the current post: [ label, url, Tabler icon name ]. */
function tristate_share_links() {
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( wp_strip_all_tags( get_the_title() ) );

	return array(
		'whatsapp' => array( 'WhatsApp', "https://wa.me/?text=$title%20$url", 'brand-whatsapp' ),
		'facebook' => array( 'Facebook', "https://www.facebook.com/sharer/sharer.php?u=$url", 'brand-facebook' ),
		'x'        => array( 'X', "https://twitter.com/intent/tweet?url=$url&text=$title", 'brand-x' ),
		'linkedin' => array( 'LinkedIn', "https://www.linkedin.com/sharing/share-offsite/?url=$url", 'brand-linkedin' ),
	);
}
