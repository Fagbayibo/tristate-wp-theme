<?php
/**
 * Search results.
 *
 * @package tristate
 */

get_header();

global $wp_query;

get_template_part( 'template-parts/page-hero', null, array(
	'title'   => sprintf( __( 'Results for &ldquo;%s&rdquo;', 'tristate' ), esc_html( get_search_query() ) ),
	'eyebrow' => __( 'Search', 'tristate' ),
	/* translators: %d: number of results */
	'meta'    => esc_html( sprintf( _n( '%d result', '%d results', (int) $wp_query->found_posts, 'tristate' ), (int) $wp_query->found_posts ) ),
) );
?>

<div class="container page-content">
	<div class="search-panel">
		<?php get_search_form(); ?>
	</div>

	<?php if ( have_posts() ) : ?>
		<div class="post-grid" data-reveal-stagger>
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/post-card' );
			endwhile;
			?>
		</div>

		<?php
		the_posts_pagination( array(
			'mid_size'  => 1,
			'prev_text' => '&larr; ' . __( 'Previous', 'tristate' ),
			'next_text' => __( 'Next', 'tristate' ) . ' &rarr;',
		) );
		?>
	<?php else : ?>
		<p class="empty-state"><?php esc_html_e( 'Nothing matched your search. Try a different word, or call us on 0700TRISTATE.', 'tristate' ); ?></p>
	<?php endif; ?>
</div>

<?php
get_footer();
