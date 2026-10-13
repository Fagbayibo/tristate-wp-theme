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
		continue;
	endif;

	$tristate_cats     = get_the_category();
	$tristate_cat      = $tristate_cats ? $tristate_cats[0] : null;
	$tristate_blog_url = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' );
	$tristate_arrow    = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9"/></svg>';
	?>
	<div class="read-progress" aria-hidden="true"></div>

	<div class="landing landing--post">
		<article <?php post_class( 'post' ); ?>>

			<!-- Header ---------------------------------------------------------- -->
			<header class="post-head">
				<div class="blog-head__glow" aria-hidden="true"></div>
				<div class="container post-head__inner">
					<nav class="sd-crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'tristate' ); ?>" data-reveal="fade">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'tristate' ); ?></a>
						<span aria-hidden="true">/</span>
						<a href="<?php echo esc_url( $tristate_blog_url ); ?>"><?php esc_html_e( 'News', 'tristate' ); ?></a>
						<?php if ( $tristate_cat ) : ?>
							<span aria-hidden="true">/</span>
							<a href="<?php echo esc_url( get_category_link( $tristate_cat ) ); ?>"><?php echo esc_html( $tristate_cat->name ); ?></a>
						<?php endif; ?>
					</nav>

					<h1 class="post-head__title" data-split data-delay="0.1"><?php the_title(); ?></h1>

					<p class="post-head__meta" data-reveal="up" data-delay="0.35">
						<?php if ( $tristate_cat ) : ?>
							<a class="landing-tag" href="<?php echo esc_url( get_category_link( $tristate_cat ) ); ?>"><?php echo esc_html( $tristate_cat->name ); ?></a>
						<?php endif; ?>
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></time>
						<span aria-hidden="true">·</span>
						<span><?php echo esc_html( tristate_read_label() ); ?></span>
					</p>
				</div>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="panel-wrap post-cover">
					<figure class="panel post-cover__frame" data-reveal="clip-up">
						<?php the_post_thumbnail( 'full', array( 'alt' => '', 'fetchpriority' => 'high', 'data-parallax' => '0.06' ) ); ?>
					</figure>
				</div>
			<?php endif; ?>

			<!-- Body ------------------------------------------------------------ -->
			<div class="container post-body">
				<div class="post-body__main">
					<div class="entry-content">
						<?php
						the_content();
						wp_link_pages( array( 'before' => '<nav class="page-links">', 'after' => '</nav>' ) );
						?>
					</div>

					<?php the_tags( '<p class="entry-tags"><span>' . esc_html__( 'Topics', 'tristate' ) . '</span>', '', '</p>' ); ?>

					<div class="post-share">
						<p class="post-share__label"><?php esc_html_e( 'Share this article', 'tristate' ); ?></p>
						<ul class="post-share__list">
							<?php foreach ( tristate_share_links() as $tristate_net => $tristate_share ) : ?>
								<li>
									<a class="post-share__btn" href="<?php echo esc_url( $tristate_share[1] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( sprintf( __( 'Share on %s', 'tristate' ), $tristate_share[0] ) ); ?>">
										<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?php echo $tristate_share[2]; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></svg>
									</a>
								</li>
							<?php endforeach; ?>
							<li>
								<button class="post-share__btn post-share__copy" type="button" data-copy-link="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php esc_attr_e( 'Copy link', 'tristate' ); ?>">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/></svg>
									<span class="post-share__copied" role="status"></span>
								</button>
							</li>
						</ul>
					</div>

					<?php
					$tristate_prev = get_previous_post();
					$tristate_next = get_next_post();
					if ( $tristate_prev || $tristate_next ) :
						?>
						<nav class="post-nav" aria-label="<?php esc_attr_e( 'More posts', 'tristate' ); ?>">
							<?php if ( $tristate_prev ) : ?>
								<a class="post-nav__link" href="<?php echo esc_url( get_permalink( $tristate_prev ) ); ?>">
									<span class="post-nav__label">&larr; <?php esc_html_e( 'Previous article', 'tristate' ); ?></span>
									<span class="post-nav__title"><?php echo esc_html( get_the_title( $tristate_prev ) ); ?></span>
								</a>
							<?php endif; ?>
							<?php if ( $tristate_next ) : ?>
								<a class="post-nav__link post-nav__link--next" href="<?php echo esc_url( get_permalink( $tristate_next ) ); ?>">
									<span class="post-nav__label"><?php esc_html_e( 'Next article', 'tristate' ); ?> &rarr;</span>
									<span class="post-nav__title"><?php echo esc_html( get_the_title( $tristate_next ) ); ?></span>
								</a>
							<?php endif; ?>
						</nav>
					<?php endif; ?>

					<?php
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>
				</div>

				<aside class="post-body__aside" aria-label="<?php esc_attr_e( 'Speak to a specialist', 'tristate' ); ?>">
					<div class="post-help" data-observe>
						<p class="post-help__label"><span class="pulse-dot" aria-hidden="true"></span><?php esc_html_e( '24/7 Emergency line', 'tristate' ); ?></p>
						<a class="post-help__number" href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>"><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a>
						<p class="post-help__text"><?php esc_html_e( 'Questions about something you read? Our specialists can help you with advice that fits your own health.', 'tristate' ); ?></p>
						<a class="btn btn--light" href="<?php echo esc_url( tristate_contact( 'appointment_url' ) ); ?>"><?php esc_html_e( 'Book Appointment', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span></a>
						<svg class="ecg" viewBox="0 0 600 90" preserveAspectRatio="none" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path pathLength="1000" d="M0 50 H170 L190 50 L205 20 L222 80 L240 8 L258 70 L272 50 H360 L378 50 L392 30 L408 68 L420 50 H600"/></svg>
					</div>
				</aside>
			</div>
		</article>

		<?php
		// Keep reading: three more posts, from the same category when possible.
		$tristate_related = new WP_Query( array(
			'post_type'           => 'post',
			'posts_per_page'      => 3,
			'post__not_in'        => array( get_the_ID() ),
			'category__in'        => $tristate_cat ? array( $tristate_cat->term_id ) : array(),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		) );
		if ( $tristate_related->have_posts() ) :
			?>
			<section class="post-related">
				<div class="container">
					<div class="landing-head">
						<div>
							<p class="eyebrow" data-reveal="up"><?php esc_html_e( 'Keep reading', 'tristate' ); ?></p>
							<h2 class="section-title" style="--title-size: 46" data-split><?php echo wp_kses_post( __( 'More from <em>Tristate</em>', 'tristate' ) ); ?></h2>
						</div>
						<a class="sd-link post-related__all" href="<?php echo esc_url( $tristate_blog_url ); ?>" data-reveal="up">
							<?php esc_html_e( 'All articles', 'tristate' ); ?>
							<span class="sd-link__circle" aria-hidden="true"><?php echo $tristate_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
						</a>
					</div>
					<div class="post-grid" data-reveal-stagger>
						<?php
						while ( $tristate_related->have_posts() ) :
							$tristate_related->the_post();
							get_template_part( 'template-parts/post-card' );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/blog-cta' ); ?>
	</div>
	<?php
endwhile;

get_footer();
