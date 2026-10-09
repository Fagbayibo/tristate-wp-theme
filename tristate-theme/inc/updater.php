<?php
/**
 * Theme updates from GitHub Releases.
 *
 * `npm run release` publishes a GitHub release (tag e.g. v1.0.1) with
 * tristate-theme.zip attached. This file tells WordPress
 * about it, so Appearance → Themes shows "New version available. Update now"
 * exactly like a WordPress.org theme.
 *
 * WordPress compares the release tag against `Version:` in style.css, so bump
 * that before tagging.
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

/** GitHub "owner/repo" the releases are published to. */
define( 'TRISTATE_GITHUB_REPO', 'Fagbayibo/tristate-wp-theme' );

/** Name of the zip the release workflow attaches (never GitHub's "Source code" zip). */
define( 'TRISTATE_RELEASE_ASSET', 'tristate-theme.zip' );

/**
 * Latest release as { version, package, url }, or null.
 *
 * Reads github.com's /releases/latest redirect (→ /releases/tag/v1.2.3) rather
 * than the GitHub API: the API allows 60 calls an hour per server IP, shared by
 * every site on a shared host, and once that ran out updates silently vanished.
 *
 * Cached for an hour; "Check again" on Dashboard → Updates skips the cache.
 */
function tristate_latest_release() {
	$cache_key = 'tristate_latest_release';
	$force     = is_admin() && isset( $_GET['force-check'] ); // phpcs:ignore WordPress.Security.NonceVerification

	$cached = get_site_transient( $cache_key );
	if ( ! $force && false !== $cached ) {
		return $cached ?: null; // '' = cached failure
	}

	$fail = static function () use ( $cache_key ) {
		set_site_transient( $cache_key, '', 15 * MINUTE_IN_SECONDS ); // back off briefly, then retry
		return null;
	};

	$base   = 'https://github.com/' . TRISTATE_GITHUB_REPO . '/releases';
	$latest = wp_remote_head( "$base/latest", array( 'timeout' => 10, 'redirection' => 0 ) );
	$tag    = is_wp_error( $latest ) ? '' : basename( (string) wp_remote_retrieve_header( $latest, 'location' ) );

	if ( ! preg_match( '/^v?\d+(\.\d+)*$/', $tag ) ) {
		return $fail();
	}

	// Offer nothing until the built zip is attached (GitHub answers 302 to its storage).
	$package = "$base/download/$tag/" . TRISTATE_RELEASE_ASSET;
	$asset   = wp_remote_head( $package, array( 'timeout' => 10, 'redirection' => 0 ) );
	if ( is_wp_error( $asset ) || 302 !== (int) wp_remote_retrieve_response_code( $asset ) ) {
		return $fail();
	}

	$release = array(
		'version' => ltrim( $tag, 'vV' ),
		'package' => $package,
		'url'     => "$base/tag/$tag",
	);

	set_site_transient( $cache_key, $release, HOUR_IN_SECONDS );
	return $release;
}

/**
 * Adds the theme to WordPress' list of available updates.
 */
add_filter(
	'pre_set_site_transient_update_themes',
	function ( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient; // WordPress hasn't gathered installed versions yet
		}

		$slug    = get_template(); // folder name, e.g. "tristate-theme"
		$current = wp_get_theme( $slug )->get( 'Version' );
		$release = tristate_latest_release();

		if ( ! $release ) {
			return $transient;
		}

		$item = array(
			'theme'        => $slug,
			'new_version'  => $release['version'],
			'url'          => $release['url'], // "View version details" link
			'package'      => $release['package'],
			'requires'     => '6.4',
			'requires_php' => '7.4',
		);

		if ( version_compare( $release['version'], $current, '>' ) ) {
			$transient->response[ $slug ] = $item;
		} else {
			$transient->no_update[ $slug ] = $item;
		}

		return $transient;
	}
);

/**
 * After an update, forget the cached release so the notice clears immediately.
 */
add_action(
	'upgrader_process_complete',
	function () {
		delete_site_transient( 'tristate_latest_release' );
	}
);
