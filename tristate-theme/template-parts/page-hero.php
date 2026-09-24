<?php
/**
 * Banner at the top of inner pages, blog posts, archives and search.
 *
 * @param array $args { title (string), eyebrow (string), meta (string), image (url) }
 *
 * @package tristate
 */

$tristate_hero = wp_parse_args( $args ?? array(), array(
	'title'   => get_the_title(),
	'eyebrow' => '',
	'meta'    => '',
	'image'   => '',
) );
?>
<section class="page-hero<?php echo $tristate_hero['image'] ? ' page-hero--image' : ''; ?>">
	<?php if ( $tristate_hero['image'] ) : ?>
		<img class="page-hero__image" src="<?php echo esc_url( $tristate_hero['image'] ); ?>" alt="" fetchpriority="high">
	<?php endif; ?>

	<div class="container page-hero__inner">
		<?php if ( $tristate_hero['eyebrow'] ) : ?>
			<p class="section-label"><?php echo esc_html( $tristate_hero['eyebrow'] ); ?></p>
		<?php endif; ?>

		<h1 class="page-hero__title"><?php echo wp_kses_post( $tristate_hero['title'] ); ?></h1>

		<?php if ( $tristate_hero['meta'] ) : ?>
			<p class="page-hero__meta"><?php echo wp_kses_post( $tristate_hero['meta'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
