<?php
/**
 * Home: the cardiovascular programme, Tristate's signature. "Care for the Heart
 * From The Heart" is the programme motto, used exactly as supplied.
 *
 * @package tristate
 */
?>
<section class="home-heart panel-wrap" aria-labelledby="heart-title">
	<div class="panel home-heart__panel">
		<div class="home-heart__ecg" aria-hidden="true" data-observe>
			<svg class="ecg" viewBox="0 0 600 90" preserveAspectRatio="none" fill="none" stroke="currentColor" stroke-width="2"><path pathLength="1000" d="M0 50 H170 L190 50 L205 20 L222 80 L240 8 L258 70 L272 50 H360 L378 50 L392 30 L408 68 L420 50 H600"/></svg>
		</div>
		<div class="container home-heart__grid">
			<div class="home-heart__copy">
				<p class="eyebrow" data-reveal="up"><?php esc_html_e( 'Advanced Cardiovascular Services', 'tristate' ); ?></p>
				<h2 class="section-title" id="heart-title" data-split>Care for the Heart <em>From The Heart</em></h2>
				<p class="landing-lead" data-reveal="up" data-delay="0.2"><?php esc_html_e( 'Advanced cardiovascular care is Tristate’s signature programme. Explore heart assessment, investigations and treatment, with explanations to help you understand each recommendation.', 'tristate' ); ?></p>
				<div class="btn-row" data-reveal="up" data-delay="0.3">
					<a class="btn btn--light" href="<?php echo esc_url( tristate_service_url( 'cardiovascular-services' ) ); ?>"><?php esc_html_e( 'Explore Cardiovascular Care', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span></a>
				</div>
			</div>
			<figure class="home-heart__media" data-reveal="clip-up">
				<img src="<?php echo esc_url( TRISTATE_URI . '/assets/images/hero-cardiac.jpg' ); ?>" width="1600" height="1067" alt="<?php esc_attr_e( 'Tristate cardiac surgeons operating in theatre', 'tristate' ); ?>" loading="lazy" data-parallax="0.05">
			</figure>
		</div>
	</div>
</section>
