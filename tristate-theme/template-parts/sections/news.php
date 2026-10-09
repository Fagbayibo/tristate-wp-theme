<?php
/**
 * News & updates — Figma 25:10.
 * Shows the 3 latest blog posts: the first as the featured post, the next two as a list.
 * Falls back to the Figma demo content only when the site has no posts yet.
 *
 * @package tristate
 */

$tristate_query = new WP_Query( array(
	'post_type'           => 'post',
	'posts_per_page'      => 3,
	'ignore_sticky_posts' => false,
	'no_found_rows'       => true,
) );

$tristate_posts = array();

if ( $tristate_query->have_posts() ) {
	while ( $tristate_query->have_posts() ) {
		$tristate_query->the_post();
		$tristate_cats = get_the_category();
		$tristate_posts[] = array(
			'title' => get_the_title(),
			'url'   => get_permalink(),
			'date'  => get_the_date( 'F j, Y' ),
			'iso'   => get_the_date( 'c' ),
			'tag'   => $tristate_cats ? $tristate_cats[0]->name : '',
			'image' => get_the_post_thumbnail_url( null, 'large' ),
		);
	}
	wp_reset_postdata();
} else {
	$tristate_demo_image = TRISTATE_URI . '/assets/images/';
	$tristate_posts      = array(
		array( 'title' => 'PDA DEVICE CLOSURE DONE ON TWO CHILDREN RECENTLY', 'url' => '#', 'date' => 'September 23, 2021', 'iso' => '2021-09-23', 'tag' => 'Health', 'image' => $tristate_demo_image . 'news-featured.jpg' ),
		array( 'title' => 'Why Should You Take a Stress Test?', 'url' => '#', 'date' => 'July 12, 2021', 'iso' => '2021-07-12', 'tag' => 'Health', 'image' => $tristate_demo_image . 'news-1.jpg' ),
		array( 'title' => 'Can I contact Covid after getting the Covid-19 vaccine?', 'url' => '#', 'date' => 'June 25, 2021', 'iso' => '2021-06-25', 'tag' => 'Health', 'image' => $tristate_demo_image . 'news-2.jpg' ),
	);
}

$tristate_news_url = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/our-blog/' );

if ( ! function_exists( 'tristate_news_meta' ) ) :
	function tristate_news_meta( $post ) {
		?>
		<div class="news-meta">
			<?php if ( $post['tag'] ) : ?>
				<span class="news-meta__tag"><?php echo esc_html( $post['tag'] ); ?></span>
			<?php endif; ?>
			<time datetime="<?php echo esc_attr( $post['iso'] ); ?>"><?php echo esc_html( $post['date'] ); ?></time>
		</div>
		<?php
	}
endif;

$tristate_featured = array_shift( $tristate_posts );
?>
<section class="news" aria-labelledby="news-title">
	<div class="container">
		<div class="news__header">
			<div class="news__heading" data-reveal-stagger>
				<p class="section-label"><?php esc_html_e( 'News & Updates', 'tristate' ); ?></p>
				<h2 class="section-title" id="news-title" style="--title-size: 46">Health insights from <em>our specialists</em>.</h2>
			</div>
			<a class="text-link" href="<?php echo esc_url( $tristate_news_url ); ?>" data-reveal="up"><?php esc_html_e( 'All News', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span></a>
		</div>

		<div class="news__grid">
			<?php if ( $tristate_featured ) : ?>
				<article class="news-featured" data-reveal="up">
					<a class="news-featured__image news-image" href="<?php echo esc_url( $tristate_featured['url'] ); ?>" tabindex="-1" aria-hidden="true">
						<?php if ( $tristate_featured['image'] ) : ?>
							<img src="<?php echo esc_url( $tristate_featured['image'] ); ?>" alt="" loading="lazy">
						<?php endif; ?>
					</a>
					<?php tristate_news_meta( $tristate_featured ); ?>
					<h3 class="news-featured__title"><a href="<?php echo esc_url( $tristate_featured['url'] ); ?>"><?php echo esc_html( $tristate_featured['title'] ); ?></a></h3>
					<a class="text-link text-link--sm" href="<?php echo esc_url( $tristate_featured['url'] ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Read more: %s', 'tristate' ), $tristate_featured['title'] ) ); ?>"><?php esc_html_e( 'Read More', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span></a>
				</article>
			<?php endif; ?>

			<?php if ( $tristate_posts ) : ?>
				<div class="news-list" data-reveal-stagger>
					<?php foreach ( $tristate_posts as $tristate_post ) : ?>
						<article class="news-item">
							<a class="news-item__image news-image" href="<?php echo esc_url( $tristate_post['url'] ); ?>" tabindex="-1" aria-hidden="true">
								<?php if ( $tristate_post['image'] ) : ?>
									<img src="<?php echo esc_url( $tristate_post['image'] ); ?>" alt="" loading="lazy">
								<?php endif; ?>
							</a>
							<div class="news-item__text">
								<?php tristate_news_meta( $tristate_post ); ?>
								<h3 class="news-item__title"><a href="<?php echo esc_url( $tristate_post['url'] ); ?>"><?php echo esc_html( $tristate_post['title'] ); ?></a></h3>
								<a class="text-link text-link--sm" href="<?php echo esc_url( $tristate_post['url'] ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Read more: %s', 'tristate' ), $tristate_post['title'] ) ); ?>"><?php esc_html_e( 'Read More', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span></a>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
