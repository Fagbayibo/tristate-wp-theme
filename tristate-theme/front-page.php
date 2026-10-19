<?php
/**
 * Homepage, in the order of the client's content brief.
 *
 * @package tristate
 */

get_header();

get_template_part( 'template-parts/sections/hero' );
get_template_part( 'template-parts/sections/care' );
get_template_part( 'template-parts/sections/heart' );
get_template_part( 'template-parts/sections/informed' );
get_template_part( 'template-parts/sections/team' );
get_template_part( 'template-parts/sections/hmo' );
get_template_part( 'template-parts/sections/visit' );
get_template_part( 'template-parts/sections/appointment' );

get_footer();
