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

/** Share targets for the current post: [ label, url, svg path(s) ]. */
function tristate_share_links() {
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( wp_strip_all_tags( get_the_title() ) );

	return array(
		'whatsapp' => array( 'WhatsApp', "https://wa.me/?text=$title%20$url", '<path d="M3.5 20.5 4.8 16A8.5 8.5 0 1 1 8 19.2Z"/><path d="M9 8.5c0 3.5 3 6.5 6.5 6.5l1.3-1.6-2-1-1 .9a5 5 0 0 1-2.6-2.6l.9-1-1-2Z"/>' ),
		'facebook' => array( 'Facebook', "https://www.facebook.com/sharer/sharer.php?u=$url", '<path d="M14 8h3V4h-3a4 4 0 0 0-4 4v3H7v4h3v6h4v-6h3l1-4h-4V8Z"/>' ),
		'x'        => array( 'X', "https://twitter.com/intent/tweet?url=$url&text=$title", '<path d="m4 4 16 16M20 4 4 20"/>' ),
		'linkedin' => array( 'LinkedIn', "https://www.linkedin.com/sharing/share-offsite/?url=$url", '<rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 10v7M8 7v.01M12 17v-4a2 2 0 0 1 4 0v4M12 10v7"/>' ),
	);
}
