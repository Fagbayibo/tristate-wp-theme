<?php
/**
 * Single blog post.
 *
 * @package tristate
 */

get_header();

while ( have_posts() ) :
	the_post();

	if ( tristate_is_elementor_content() ) :
		the_content();
	else :
		$tristate_cats = get_the_category();
		$tristate_meta = sprintf(
			'<time datetime="%1$s">%2$s</time><span aria-hidden="true"> · </span>%3$s',
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date( 'F j, Y' ) ),
			esc_html( sprintf( _n( '%s min read', '%s min read', tristate_reading_time(), 'tristate' ), tristate_reading_time() ) )
		);

		get_template_part( 'template-parts/page-hero', null, array(
			'title'   => get_the_title(),
			'eyebrow' => $tristate_cats ? $tristate_cats[0]->name : __( 'News', 'tristate' ),
			'meta'    => $tristate_meta,
			'image'   => get_the_post_thumbnail_url( null, 'full' ),
		) );
		?>

		<article <?php post_class( 'container page-content' ); ?>>
			<div class="entry-content">
				<?php
				the_content();
				wp_link_pages( array( 'before' => '<nav class="page-links">', 'after' => '</nav>' ) );
				?>
			</div>

			<?php the_tags( '<p class="entry-tags">', '', '</p>' ); ?>

			<nav class="post-nav" aria-label="<?php esc_attr_e( 'More posts', 'tristate' ); ?>">
				<?php
				$tristate_prev = get_previous_post();
				$tristate_next = get_next_post();
				?>
				<?php if ( $tristate_prev ) : ?>
					<a class="post-nav__link" href="<?php echo esc_url( get_permalink( $tristate_prev ) ); ?>">
						<span class="post-nav__label">&larr; <?php esc_html_e( 'Previous', 'tristate' ); ?></span>
						<span class="post-nav__title"><?php echo esc_html( get_the_title( $tristate_prev ) ); ?></span>
					</a>
				<?php endif; ?>
				<?php if ( $tristate_next ) : ?>
					<a class="post-nav__link post-nav__link--next" href="<?php echo esc_url( get_permalink( $tristate_next ) ); ?>">
						<span class="post-nav__label"><?php esc_html_e( 'Next', 'tristate' ); ?> &rarr;</span>
						<span class="post-nav__title"><?php echo esc_html( get_the_title( $tristate_next ) ); ?></span>
					</a>
				<?php endif; ?>
			</nav>

			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</article>
		<?php
	endif;

endwhile;

get_footer();
