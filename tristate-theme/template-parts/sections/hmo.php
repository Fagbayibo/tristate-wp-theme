<?php
/**
 * Home: HMO partners, with each partner's logo.
 *
 * @package tristate
 */
?>
<section class="home-hmo panel-wrap" aria-labelledby="hmo-title">
	<div class="panel home-hmo__panel">
		<div class="container home-hmo__grid">
			<div class="home-hmo__copy">
				<h2 class="section-title" id="hmo-title" data-split>HMO <em>partners</em></h2>
				<p class="landing-lead" data-reveal="up" data-delay="0.2"><?php esc_html_e( 'Contact us to check your plan’s cover and any approval needed before your visit.', 'tristate' ); ?></p>
				<div class="btn-row" data-reveal="up" data-delay="0.3">
					<a class="btn btn--brand" href="<?php echo esc_url( tristate_page_url( 'hmo-partners' ) ); ?>"><?php esc_html_e( 'Check My HMO Cover', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span></a>
				</div>
			</div>
			<ul class="hmo-names" data-reveal-stagger aria-label="<?php esc_attr_e( 'Accepted HMO partners', 'tristate' ); ?>">
				<?php array_map( 'tristate_hmo_logo', tristate_hmo_partners() ); ?>
			</ul>
		</div>
	</div>
</section>
