<?php
/**
 * Post card used by the blog listing, archives and search results.
 *
 * @package tristate
 */

$tristate_cats = get_the_category();
?>
<article <?php post_class( 'post-card' ); ?>>
	<a class="post-card__image news-image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy', 'alt' => '' ) ); ?>
		<?php endif; ?>
	</a>

	<div class="post-card__body">
		<div class="news-meta">
			<?php if ( $tristate_cats ) : ?>
				<span class="news-meta__tag"><?php echo esc_html( $tristate_cats[0]->name ); ?></span>
			<?php endif; ?>
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></time>
		</div>

		<h2 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>

		<a class="text-link text-link--sm" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Read more: %s', 'tristate' ), get_the_title() ) ); ?>">
			<?php esc_html_e( 'Read More', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span>
		</a>
	</div>
</article>
