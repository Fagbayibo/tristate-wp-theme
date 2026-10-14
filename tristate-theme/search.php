<?php
/**
 * Search results.
 *
 * @package tristate
 */

get_header();

global $wp_query;
?>
<div class="landing landing--blog">
	<?php
	get_template_part( 'template-parts/blog-head', null, array(
		'eyebrow' => __( 'Search', 'tristate' ),
		/* translators: %s: search terms */
		'title'   => sprintf( __( 'Results for <em>&ldquo;%s&rdquo;</em>', 'tristate' ), esc_html( get_search_query() ) ),
		/* translators: %d: number of results */
		'lead'    => esc_html( sprintf( _n( '%d result found.', '%d results found.', (int) $wp_query->found_posts, 'tristate' ), (int) $wp_query->found_posts ) ),
	) );

	get_template_part( 'template-parts/post-list', null, array( 'empty' => sprintf( /* translators: %s: phone number */ __( 'Nothing matched your search. Try a different word, or call us on %s.', 'tristate' ), tristate_contact( 'emergency' ) ) ) );

	?>
</div>
<?php
get_footer();
