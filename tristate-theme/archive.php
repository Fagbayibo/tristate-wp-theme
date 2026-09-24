<?php
/**
 * Category, tag, author and date archives.
 *
 * @package tristate
 */

get_header();

get_template_part( 'template-parts/page-hero', null, array(
	'title'   => get_the_archive_title(),
	'eyebrow' => __( 'News & Updates', 'tristate' ),
	'meta'    => wp_strip_all_tags( get_the_archive_description() ),
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
		<p class="empty-state"><?php esc_html_e( 'Nothing found here yet.', 'tristate' ); ?></p>
	<?php endif; ?>
</div>

<?php
get_footer();
