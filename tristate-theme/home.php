<?php
/**
 * Blog listing (the page set as "Posts page", or the site root when the front
 * page shows posts).
 *
 * @package tristate
 */

get_header();
?>
<div class="landing landing--blog">
	<?php
	get_template_part( 'template-parts/blog-head', null, array(
		'eyebrow' => get_option( 'page_for_posts' ) ? get_the_title( get_option( 'page_for_posts' ) ) : __( 'News & Insights', 'tristate' ),
		'title'   => __( 'Health insights &amp; <em>hospital news</em>', 'tristate' ),
		'lead'    => __( 'Stories from our theatres, guidance from our specialists and updates from across Tristate Healthcare System.', 'tristate' ),
	) );

	get_template_part( 'template-parts/post-list', null, array( 'empty' => __( 'No posts published yet.', 'tristate' ) ) );

	get_template_part( 'template-parts/blog-cta' );
	?>
</div>
<?php
get_footer();
