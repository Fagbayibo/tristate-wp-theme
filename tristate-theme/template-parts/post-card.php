<?php
/**
 * Post card: blog listing, archives, search results and "Keep reading".
 *
 * Args: featured (bool) — the wide card that leads the blog listing.
 *
 * @package tristate
 */

$tristate_featured = ! empty( $args['featured'] );
$tristate_cats     = get_the_category();
$tristate_heading  = $tristate_featured ? 'h2' : 'h3';
?>
<article <?php post_class( 'post-card' . ( $tristate_featured ? ' post-card--featured' : '' ) ); ?>>
	<a class="post-card__image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( $tristate_featured ? 'full' : 'large', array( 'loading' => $tristate_featured ? 'eager' : 'lazy', 'alt' => '' ) ); ?>
		<?php else : ?>
			<span class="post-card__placeholder"><?php echo tristate_line_icon( 'heart', '1.2' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
		<?php endif; ?>
		<?php if ( $tristate_cats ) : ?>
			<span class="post-card__tag"><?php echo esc_html( $tristate_cats[0]->name ); ?></span>
		<?php endif; ?>
	</a>

	<div class="post-card__body">
		<p class="post-card__meta">
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></time>
			<span aria-hidden="true">·</span>
			<span><?php echo esc_html( tristate_read_label() ); ?></span>
		</p>

		<<?php echo $tristate_heading; // phpcs:ignore WordPress.Security.EscapeOutput -- h2/h3 ?> class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></<?php echo $tristate_heading; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
		<p class="post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), $tristate_featured ? 34 : 20 ) ); ?></p>

		<span class="post-card__more" aria-hidden="true">
			<?php esc_html_e( 'Read article', 'tristate' ); ?>
			<span class="post-card__circle"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17 17 7M8 7h9v9"/></svg></span>
		</span>
	</div>
</article>
