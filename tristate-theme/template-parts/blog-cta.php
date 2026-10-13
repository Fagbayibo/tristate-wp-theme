<?php
/**
 * Closing call to action on blog screens.
 *
 * @package tristate
 */

get_template_part( 'template-parts/landing-cta', null, array(
	'eyebrow'   => __( 'Have a question?', 'tristate' ),
	'title'     => __( 'Talk to a <em>Tristate specialist</em>', 'tristate' ),
	'text'      => __( 'Articles can only go so far. Book an appointment and get advice that fits your own health.', 'tristate' ),
	'secondary' => array( __( 'Our Services', 'tristate' ), tristate_template_url( 'page-templates/our-services.php', '/our-services/' ) ),
) );
