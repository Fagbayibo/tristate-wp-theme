<?php
/**
 * Header of the blog listing, archives and search: title, intro, category
 * filters and the search box.
 *
 * Args: eyebrow, title (may hold <em>), lead.
 *
 * @package tristate
 */

$tristate_head = wp_parse_args( $args, array(
	'eyebrow' => __( 'News & Insights', 'tristate' ),
	'title'   => '',
	'lead'    => '',
) );

$tristate_blog_url = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' );
$tristate_cats     = get_categories( array( 'hide_empty' => true, 'exclude' => array( (int) get_option( 'default_category' ) ) ) );
$tristate_current  = is_category() ? get_queried_object_id() : 0;
?>
<section class="blog-head">
	<div class="blog-head__glow" aria-hidden="true"></div>
	<div class="container">
		<div class="blog-head__grid">
			<div>
				<p class="eyebrow" data-reveal="up"><?php echo esc_html( $tristate_head['eyebrow'] ); ?></p>
				<h1 class="landing-title" data-split data-delay="0.15"><?php echo wp_kses_post( $tristate_head['title'] ); ?></h1>
			</div>
			<div class="blog-head__side">
				<?php if ( $tristate_head['lead'] ) : ?>
					<p class="landing-lead" data-reveal="up" data-delay="0.3"><?php echo wp_kses_post( $tristate_head['lead'] ); ?></p>
				<?php endif; ?>
				<div data-reveal="up" data-delay="0.4"><?php get_search_form(); ?></div>
			</div>
		</div>

		<?php if ( $tristate_cats ) : ?>
			<nav class="blog-filters" aria-label="<?php esc_attr_e( 'Categories', 'tristate' ); ?>" data-reveal="up" data-delay="0.5">
				<a class="blog-filter<?php echo is_home() ? ' is-active' : ''; ?>" href="<?php echo esc_url( $tristate_blog_url ); ?>"<?php echo is_home() ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'All posts', 'tristate' ); ?></a>
				<?php foreach ( $tristate_cats as $tristate_cat ) : ?>
					<?php $tristate_on = $tristate_current === $tristate_cat->term_id; ?>
					<a class="blog-filter<?php echo $tristate_on ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_category_link( $tristate_cat ) ); ?>"<?php echo $tristate_on ? ' aria-current="page"' : ''; ?>>
						<?php echo esc_html( $tristate_cat->name ); ?> <span><?php echo esc_html( number_format_i18n( $tristate_cat->count ) ); ?></span>
					</a>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>
	</div>
</section>
