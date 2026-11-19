<?php
/**
 * LOCAL DEVELOPMENT ONLY — installed as a must-use plugin by dev/seed.php.
 * Playground cannot send email, so every wp_mail() is saved instead as an
 * HTML file under wp-content/uploads/dev-mail/ (newest listed at
 * /wp-content/uploads/dev-mail/index.html) and reported as sent.
 * Never deploy this.
 */

add_filter( 'pre_wp_mail', function ( $return, $atts ) {
	$dir = WP_CONTENT_DIR . '/uploads/dev-mail';
	wp_mkdir_p( $dir );

	$to      = is_array( $atts['to'] ) ? implode( ', ', $atts['to'] ) : $atts['to'];
	$headers = is_array( $atts['headers'] ) ? implode( "\n", $atts['headers'] ) : (string) $atts['headers'];
	$name    = gmdate( 'His' ) . '-' . substr( md5( $to . $atts['subject'] . microtime() ), 0, 6 ) . '.html';
	$meta    = '<pre style="margin:0;padding:12px 16px;background:#222;color:#eee;font:12px/1.5 monospace;white-space:pre-wrap">'
		. esc_html( "To: $to\nSubject: {$atts['subject']}\n$headers" ) . '</pre>';
	$body    = false !== stripos( $headers, 'text/html' ) ? $atts['message'] : '<pre>' . esc_html( $atts['message'] ) . '</pre>';
	file_put_contents( "$dir/$name", $meta . $body ); // phpcs:ignore

	// Index of caught mail, newest first.
	$files = glob( "$dir/[0-9]*.html" );
	rsort( $files );
	$links = array_map( static function ( $file ) {
		return '<li><a href="' . basename( $file ) . '">' . basename( $file ) . '</a></li>';
	}, $files );
	file_put_contents( "$dir/index.html", '<h1>Caught mail</h1><ul>' . implode( '', $links ) . '</ul>' ); // phpcs:ignore

	return true;
}, 10, 2 );
