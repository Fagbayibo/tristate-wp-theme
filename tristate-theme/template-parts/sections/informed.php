<?php
/**
 * Home: feeling informed about your care, leading to the Patient Guide.
 *
 * @package tristate
 */
?>
<section class="home-split landing-section" aria-labelledby="informed-title">
	<div class="container home-split__grid">
		<figure class="home-split__media landing-media" data-reveal="clip-up">
			<img src="<?php echo esc_url( TRISTATE_URI . '/assets/images/hero-care.jpg' ); ?>" width="1600" height="1067" alt="<?php esc_attr_e( 'A Tristate clinician going through results with a patient on a tablet', 'tristate' ); ?>" loading="lazy" data-parallax="0.05">
		</figure>
		<div class="home-split__copy">
			<p class="eyebrow" data-reveal="up"><?php esc_html_e( 'Patient Guide', 'tristate' ); ?></p>
			<h2 class="section-title" id="informed-title" data-split>Feel informed about <em>your care</em></h2>
			<p class="landing-lead" data-reveal="up" data-delay="0.2"><?php esc_html_e( 'A good care experience includes being listened to, understanding your options and knowing what happens after your visit. Tell us what matters to you, including concerns about treatment, cost or recovery.', 'tristate' ); ?></p>
			<div class="btn-row" data-reveal="up" data-delay="0.3">
				<a class="btn btn--brand" href="<?php echo esc_url( tristate_page_url( 'patient-guide' ) ); ?>"><?php esc_html_e( 'Plan Your Visit', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span></a>
			</div>
		</div>
	</div>
</section>
