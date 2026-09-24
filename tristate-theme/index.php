<?php
/**
 * Fallback template. WordPress uses front-page.php, home.php, single.php,
 * page.php, archive.php, search.php or 404.php first; this covers anything else.
 *
 * @package tristate
 */

get_header();

get_template_part( 'template-parts/page-hero', null, array(
	'title' => is_home() ? __( 'News & Updates', 'tristate' ) : get_the_archive_title(),
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

		<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>
	<?php else : ?>
		<p class="empty-state"><?php esc_html_e( 'Nothing found.', 'tristate' ); ?></p>
	<?php endif; ?>
</div>

<?php
get_footer();
