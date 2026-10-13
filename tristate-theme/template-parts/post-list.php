<?php
/**
 * The post grid for the main query: blog listing, archives and search.
 * On the first page of the blog listing the newest post leads as a wide card.
 *
 * Args: empty (message when there are no posts).
 *
 * @package tristate
 */

$tristate_empty   = isset( $args['empty'] ) ? $args['empty'] : __( 'Nothing here yet.', 'tristate' );
$tristate_lead_on = is_home() && ! is_paged();
?>
<section class="blog-list">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<?php
			if ( $tristate_lead_on ) {
				the_post();
				echo '<div data-reveal="up">';
				get_template_part( 'template-parts/post-card', null, array( 'featured' => true ) );
				echo '</div>';
			}
			?>
			<?php if ( have_posts() ) : ?>
				<div class="post-grid" data-reveal-stagger>
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/post-card' );
					endwhile;
					?>
				</div>
			<?php endif; ?>

			<?php
			the_posts_pagination( array(
				'mid_size'           => 1,
				'prev_text'          => '<span aria-hidden="true">&larr;</span> ' . __( 'Newer', 'tristate' ),
				'next_text'          => __( 'Older', 'tristate' ) . ' <span aria-hidden="true">&rarr;</span>',
				'screen_reader_text' => __( 'More posts', 'tristate' ),
			) );
			?>
		<?php else : ?>
			<div class="blog-empty">
				<p class="empty-state"><?php echo esc_html( $tristate_empty ); ?></p>
				<a class="btn btn--outline-brand" href="<?php echo esc_url( get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' ) ); ?>"><?php esc_html_e( 'See all posts', 'tristate' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>
