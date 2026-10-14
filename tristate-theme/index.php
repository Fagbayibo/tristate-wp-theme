<?php
/**
 * Fallback for any screen without a more specific template: the blog layout.
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

	?>
</div>
<?php
get_footer();
