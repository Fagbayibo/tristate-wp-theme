<?php
/**
 * Single page.
 *
 * Pages built with Elementor are output exactly as Elementor renders them —
 * no banner, no wrapper — so existing pages keep working untouched while the
 * new designs are built. Pages written in the block/classic editor get the
 * theme's own layout.
 *
 * @package tristate
 */

get_header();

while ( have_posts() ) :
	the_post();

	if ( tristate_is_elementor_content() ) :
		the_content();
	else :
		get_template_part( 'template-parts/page-hero', null, array(
			'title' => get_the_title(),
			'image' => get_the_post_thumbnail_url( null, 'full' ),
		) );
		?>
		<div class="container page-content">
			<div class="entry-content">
				<?php
				the_content();
				wp_link_pages( array( 'before' => '<nav class="page-links">', 'after' => '</nav>' ) );
				?>
			</div>
		</div>
		<?php
	endif;

endwhile;

get_footer();
