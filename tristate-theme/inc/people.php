<?php
/**
 * Our team and HMO partners, from the client's content brief (Oct 2026).
 * Clinicians whose profiles have not been supplied show their name only.
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

/**
 * Leadership profiles: name, title, specialty, biography.
 * Leadership titles are kept apart from bookable availability (brief).
 */
function tristate_leaders() {
	return array(
		array(
			'name'      => 'Professor Kamar Tayo Adeleke',
			'title'     => 'Founder and Chief Executive Officer, Tristate Healthcare System',
			'specialty' => 'Interventional Cardiologist',
			'bio'       => 'Professor Adeleke is a Nigerian-born, US-trained interventional cardiologist. He founded Tristate Healthcare System to expand access to advanced cardiovascular care in Nigeria through clinical practice, teaching and collaboration.',
		),
		array(
			'name'      => 'Dr Babalola Olusola Oluwole',
			'title'     => 'Director of Clinical Operations, Tristate Hospital Lekki',
			'specialty' => '',
			'bio'       => 'Dr Oluwole’s experience spans clinical practice, research and hospital management in Nigeria and the United States. His responsibilities at Tristate Lekki include clinical operations, quality assurance and patient safety.',
		),
	);
}

/** Clinicians listed in the brief without a profile yet. */
function tristate_clinicians() {
	return array( 'Dr Banji Idowu', 'Dr Ogbonnaya', 'Dr Oludara' );
}

/** Initials for a portrait placeholder: "Dr Babalola Olusola Oluwole" → "BO". */
function tristate_initials( $name ) {
	$words = array_values( array_diff( preg_split( '/\s+/', $name ), array( 'Dr', 'Professor', 'Prof' ) ) );
	$first = isset( $words[0] ) ? $words[0] : $name;
	$last  = count( $words ) > 1 ? end( $words ) : '';
	return strtoupper( mb_substr( $first, 0, 1 ) . ( $last ? mb_substr( $last, 0, 1 ) : '' ) );
}

/**
 * Accepted HMO partners: name, logo (assets/images/hmo, taken from each
 * company's own website), logo width × height.
 */
function tristate_hmo_partners() {
	return array(
		array( 'Leadway Health', 'leadway-health.png', 653, 216 ),
		array( 'AXA Mansard', 'axa-mansard.svg', 499, 499 ),
		array( 'Alliance Healthcare', 'alliance-healthcare.png', 335, 76 ),
	);
}

/** Print one partner's logo tile. */
function tristate_hmo_logo( $partner ) {
	printf(
		'<li class="hmo-name"><img src="%s" width="%d" height="%d" alt="%s" loading="lazy"></li>',
		esc_url( TRISTATE_URI . '/assets/images/hmo/' . $partner[1] ),
		(int) $partner[2],
		(int) $partner[3],
		esc_attr( $partner[0] )
	);
}
