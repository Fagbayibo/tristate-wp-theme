<?php
/**
 * LOCAL DEVELOPMENT ONLY — run by blueprint.json when the Playground site boots.
 * Replaces the default "Hello world!" post with the three news posts from the
 * Figma design so the homepage News section can be previewed. Never deploy this.
 */

require_once '/wordpress/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

wp_delete_post( 1, true ); // "Hello world!"

$health = wp_insert_term( 'Health', 'category' );
$cat_id = is_wp_error( $health ) ? (int) get_term_by( 'name', 'Health', 'category' )->term_id : (int) $health['term_id'];

$images = get_theme_root() . '/tristate-theme/assets/images/';
$posts  = array(
	array( 'PDA DEVICE CLOSURE DONE ON TWO CHILDREN RECENTLY', '2021-09-23 10:00:00', 'news-featured.jpg' ),
	array( 'Why Should You Take a Stress Test?', '2021-07-12 10:00:00', 'news-1.jpg' ),
	array( 'Can I contact Covid after getting the Covid-19 vaccine?', '2021-06-25 10:00:00', 'news-2.jpg' ),
);

foreach ( $posts as $p ) {
	list( $title, $date, $file ) = $p;

	$post_id = wp_insert_post( array(
		'post_title'    => $title,
		'post_content'  => '<!-- wp:paragraph --><p>Sample post content for local development.</p><!-- /wp:paragraph -->',
		'post_status'   => 'publish',
		'post_date'     => $date,
		'post_category' => array( $cat_id ),
	) );

	$upload = wp_upload_bits( $file, null, file_get_contents( $images . $file ) );
	if ( empty( $upload['error'] ) ) {
		$attachment_id = wp_insert_attachment( array(
			'post_mime_type' => 'image/jpeg',
			'post_title'     => $title,
			'post_status'    => 'inherit',
		), $upload['file'], $post_id );
		wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $upload['file'] ) );
		set_post_thumbnail( $post_id, $attachment_id );
	}
}


/* Sample brochures & flyers ----------------------------------------------------- */

/** Build a tiny one-page PDF containing a title line (enough to test downloads). */
function tristate_seed_pdf( $title ) {
	$text    = addcslashes( $title, '\\()' ); // PDF string literals need \, ( and ) escaped.
	$stream  = "BT /F1 24 Tf 72 720 Td ($text) Tj ET\nBT /F1 12 Tf 72 690 Td (Sample file for local development - Tristate Healthcare System) Tj ET";
	$objects = array(
		'<< /Type /Catalog /Pages 2 0 R >>',
		'<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
		'<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>',
		'<< /Length ' . strlen( $stream ) . " >>\nstream\n$stream\nendstream",
		'<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
	);
	$pdf     = "%PDF-1.4\n";
	$offsets = array();
	foreach ( $objects as $i => $obj ) {
		$offsets[] = strlen( $pdf );
		$pdf      .= ( $i + 1 ) . " 0 obj\n$obj\nendobj\n";
	}
	$xref = strlen( $pdf );
	$pdf .= "xref\n0 " . ( count( $objects ) + 1 ) . "\n0000000000 65535 f \n";
	foreach ( $offsets as $offset ) {
		$pdf .= sprintf( "%010d 00000 n \n", $offset );
	}
	return $pdf . "trailer\n<< /Size " . ( count( $objects ) + 1 ) . " /Root 1 0 R >>\nstartxref\n$xref\n%%EOF";
}

$downloads = array(
	array( 'Cardiovascular Services Brochure (Sample)', 'brochure', 'service-cardiovascular.jpg' ),
	array( 'Preparing for Heart Surgery (Sample)', 'guide', 'why-1.jpg' ),
	array( 'Laboratory & Radiology Services (Sample)', 'brochure', 'service-laboratory.jpg' ),
	array( '24/7 Emergency Care Flyer (Sample)', 'flyer', 'service-emergency-alt.jpg' ),
);

foreach ( $downloads as $order => $d ) {
	list( $title, $type, $cover ) = $d;

	$download_id = wp_insert_post( array(
		'post_type'   => 'tristate_download',
		'post_title'  => $title,
		'post_status' => 'publish',
		'menu_order'  => $order,
	) );

	$pdf = wp_upload_bits( sanitize_title( $title ) . '.pdf', null, tristate_seed_pdf( $title ) );
	if ( empty( $pdf['error'] ) ) {
		$file_id = wp_insert_attachment( array( 'post_mime_type' => 'application/pdf', 'post_title' => $title, 'post_status' => 'inherit' ), $pdf['file'] );
		update_post_meta( $download_id, '_download_file_id', $file_id );
	}
	update_post_meta( $download_id, '_download_type', $type );

	$img = wp_upload_bits( 'cover-' . $cover, null, file_get_contents( $images . $cover ) );
	if ( empty( $img['error'] ) ) {
		$cover_id = wp_insert_attachment( array( 'post_mime_type' => 'image/jpeg', 'post_title' => $title . ' cover', 'post_status' => 'inherit' ), $img['file'] );
		wp_update_attachment_metadata( $cover_id, wp_generate_attachment_metadata( $cover_id, $img['file'] ) );
		set_post_thumbnail( $download_id, $cover_id );
	}
}

/* A page "built with Elementor", to prove legacy pages still render ------------- */

$elementor_data = json_encode( array(
	array(
		'id'       => 'a1b2c3d',
		'elType'   => 'section',
		'settings' => array(),
		'elements' => array(
			array(
				'id'       => 'e4f5g6h',
				'elType'   => 'column',
				'settings' => array( '_column_size' => 100 ),
				'elements' => array(
					array(
						'id'         => 'i7j8k9l',
						'elType'     => 'widget',
						'widgetType' => 'heading',
						'settings'   => array( 'title' => 'Legacy Elementor Page' ),
					),
					array(
						'id'         => 'm1n2o3p',
						'elType'     => 'widget',
						'widgetType' => 'text-editor',
						'settings'   => array( 'editor' => '<p>This page was built with Elementor and must keep rendering exactly as before.</p>' ),
					),
				),
			),
		),
	),
) );

$legacy_id = wp_insert_post( array(
	'post_type'    => 'page',
	'post_title'   => 'Legacy Elementor Page',
	'post_name'    => 'legacy-elementor-page',
	'post_status'  => 'publish',
	'post_content' => 'Fallback content.',
) );

update_post_meta( $legacy_id, '_elementor_edit_mode', 'builder' );
update_post_meta( $legacy_id, '_elementor_template_type', 'wp-page' );
update_post_meta( $legacy_id, '_elementor_version', '3.0.0' );
update_post_meta( $legacy_id, '_elementor_data', wp_slash( $elementor_data ) );

/* Designed pages: About Us and Our Services use their page templates ----------- */

foreach ( array(
	array( 'About Us', 'about-us', 'page-templates/about-us.php' ),
	array( 'Our Services', 'services', 'page-templates/our-services.php' ),
) as $page ) {
	list( $title, $slug, $template ) = $page;
	$page_id = wp_insert_post( array(
		'post_type'   => 'page',
		'post_title'  => $title,
		'post_name'   => $slug,
		'post_status' => 'publish',
	) );
	update_post_meta( $page_id, '_wp_page_template', $template );
}
