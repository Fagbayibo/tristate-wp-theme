<?php
/**
 * Blog listing (the page set as "Posts page", or the site root when the front
 * page shows posts).
 *
 * @package tristate
 */

get_header();

get_template_part( 'template-parts/page-hero', null, array(
	'title'   => get_option( 'page_for_posts' ) ? get_the_title( get_option( 'page_for_posts' ) ) : __( 'News & Updates', 'tristate' ),
	'eyebrow' => __( 'News & Updates', 'tristate' ),
) );
?>

<div class="container page-content">
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
		<p class="empty-state"><?php esc_html_e( 'No posts published yet.', 'tristate' ); ?></p>
	<?php endif; ?>
</div>

<?php
get_footer();
