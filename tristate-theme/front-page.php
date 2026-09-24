<?php
/**
 * Homepage — sections are added here in design order.
 *
 * @package tristate
 */

get_header();

get_template_part( 'template-parts/sections/hero' );
get_template_part( 'template-parts/sections/intro' );
get_template_part( 'template-parts/sections/mission' );
get_template_part( 'template-parts/sections/services' );
get_template_part( 'template-parts/sections/stats' );
get_template_part( 'template-parts/sections/why' );
get_template_part( 'template-parts/sections/testimonials' );
get_template_part( 'template-parts/sections/news' );
get_template_part( 'template-parts/sections/appointment' );

get_footer();
