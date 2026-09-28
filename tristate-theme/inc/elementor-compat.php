<?php
/**
 * Compatibility shims for legacy Elementor add-ons.
 *
 * The old theme's companion plugins (e.g. tfteam, used on /our-team/) still
 * reference Elementor's colour/typography "scheme" classes, which Elementor
 * removed in 3.x. The previous theme defined them; without them those widgets
 * fatal while registering their controls.
 *
 * Modern Elementor ignores the `scheme` control argument, so these only need
 * to exist with the old constants. Each is defined only if Elementor doesn't
 * provide it, so nothing changes if a future Elementor reinstates them.
 *
 * @package tristate
 */

namespace Elementor\Core\Schemes;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( __NAMESPACE__ . '\Color' ) ) {
	class Color {
		const COLOR_1 = '1';
		const COLOR_2 = '2';
		const COLOR_3 = '3';
		const COLOR_4 = '4';

		public static function get_type() {
			return 'color';
		}
	}
}

if ( ! class_exists( __NAMESPACE__ . '\Typography' ) ) {
	class Typography {
		const TYPOGRAPHY_1 = '1';
		const TYPOGRAPHY_2 = '2';
		const TYPOGRAPHY_3 = '3';
		const TYPOGRAPHY_4 = '4';

		public static function get_type() {
			return 'typography';
		}
	}
}

// Pre-2.9 names used by older add-ons.
foreach ( array( 'Color' => 'Scheme_Color', 'Typography' => 'Scheme_Typography' ) as $current => $legacy ) {
	if ( ! class_exists( 'Elementor\\' . $legacy ) ) {
		class_alias( __NAMESPACE__ . '\\' . $current, 'Elementor\\' . $legacy );
	}
}
