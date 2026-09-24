<?php
/**
 * Theme updates from GitHub Releases.
 *
 * Publishing a release on GitHub (tag e.g. v1.0.1) makes a workflow build
 * tristate-theme.zip and attach it to the release. This file tells WordPress
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
define( 'TRISTATE_GITHUB_REPO', 'Fagbayibo/tristate-theme' );

/** Name of the zip the release workflow attaches (never GitHub's "Source code" zip). */
define( 'TRISTATE_RELEASE_ASSET', 'tristate-theme.zip' );

/**
 * Latest release as { version, package, url }, or null.
 *
 * Cached for six hours: WordPress runs its update check on many admin page
 * loads, and GitHub allows 60 unauthenticated API calls per hour per server IP.
 * "Check again" on Dashboard → Updates skips the cache.
 */
function tristate_latest_release() {
	$cache_key = 'tristate_latest_release';
	$force     = is_admin() && isset( $_GET['force-check'] ); // phpcs:ignore WordPress.Security.NonceVerification

	$cached = get_site_transient( $cache_key );
	if ( ! $force && false !== $cached ) {
		return $cached ?: null; // '' = cached failure
	}

	$response = wp_remote_get(
		'https://api.github.com/repos/' . TRISTATE_GITHUB_REPO . '/releases/latest',
		array(
			'timeout' => 10,
			'headers' => array(
				'Accept'     => 'application/vnd.github+json',
				'User-Agent' => 'tristate-theme-updater', // GitHub rejects requests without one
			),
		)
	);

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		set_site_transient( $cache_key, '', 30 * MINUTE_IN_SECONDS ); // back off, don't hammer the API
		return null;
	}

	$body    = json_decode( wp_remote_retrieve_body( $response ), true );
	$package = '';

	foreach ( (array) ( $body['assets'] ?? array() ) as $asset ) {
		if ( TRISTATE_RELEASE_ASSET === ( $asset['name'] ?? '' ) ) {
			$package = $asset['browser_download_url'];
			break;
		}
	}

	// No built zip yet (workflow still running or failed): offer nothing rather than a broken package.
	if ( empty( $body['tag_name'] ) || ! $package ) {
		set_site_transient( $cache_key, '', 30 * MINUTE_IN_SECONDS );
		return null;
	}

	$release = array(
		'version' => ltrim( $body['tag_name'], 'vV' ),
		'package' => $package,
		'url'     => $body['html_url'],
	);

	set_site_transient( $cache_key, $release, 6 * HOUR_IN_SECONDS );
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
