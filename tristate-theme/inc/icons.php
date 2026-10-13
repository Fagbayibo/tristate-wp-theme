<?php
/**
 * Line icons from Tabler Icons (MIT licence, https://tabler.io/icons).
 *
 * Each icon is the unmodified SVG file in assets/icons/, printed inline so it
 * takes the surrounding text colour. To add one, download its outline SVG from
 * tabler.io/icons into assets/icons/ and call tristate_svg( 'its-name' ).
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

/**
 * Inline SVG for a bundled Tabler icon.
 *
 * @param string $name Icon file name without .svg (e.g. 'brand-whatsapp').
 * @param array  $args stroke (width, default 1.75), class, path_length (adds
 *                     pathLength to each path, for stroke-drawing animations).
 * @return string Escaped-safe SVG markup (static files from the theme).
 */
function tristate_svg( $name, $args = array() ) {
	static $cache = array();

	$args = wp_parse_args( $args, array( 'stroke' => 1.75, 'class' => '', 'path_length' => 0 ) );
	$name = sanitize_file_name( $name );

	if ( ! isset( $cache[ $name ] ) ) {
		$file  = TRISTATE_DIR . '/assets/icons/' . $name . '.svg';
		$svg   = is_readable( $file ) ? file_get_contents( $file ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file
		$inner = preg_replace( '#^.*?<svg[^>]*>|</svg>\s*$#s', '', $svg );
		// Tabler starts every icon with an invisible 24×24 bounding-box path.
		$inner          = preg_replace( '#<path stroke="none" d="M0 0h24v24H0z" fill="none"\s*/>#', '', $inner );
		$cache[ $name ] = trim( preg_replace( '/\s+/', ' ', $inner ) );
	}

	$inner = $cache[ $name ];
	if ( $args['path_length'] ) {
		$inner = str_replace( '<path ', '<path pathLength="' . (int) $args['path_length'] . '" ', $inner );
	}

	return sprintf(
		'<svg%s viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%s</svg>',
		$args['class'] ? ' class="' . esc_attr( $args['class'] ) . '"' : '',
		esc_attr( $args['stroke'] ),
		$inner
	);
}
