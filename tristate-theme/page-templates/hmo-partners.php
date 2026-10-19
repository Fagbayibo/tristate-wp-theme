<?php
/**
 * Template Name: HMO Partners
 *
 * The HMO Partners page gets this design from its slug (see functions.php).
 * Partner logos come from inc/people.php.
 *
 * @package tristate
 */

$tristate_check_url = add_query_arg( 'topic', 'hmo', tristate_page_url( 'our-hospitals' ) ) . '#contact-form';

get_header();
?>
<div class="landing landing--info">

	<section class="info-hero">
		<div class="blog-head__glow" aria-hidden="true"></div>
		<div class="container info-hero__inner">
			<p class="eyebrow eyebrow--center" data-reveal="up">HMO Partners</p>
			<h1 class="landing-title" data-split data-delay="0.15">Understand your cover <em>before your visit</em></h1>
			<p class="landing-lead" data-reveal="up" data-delay="0.35">Accepted partners include Leadway, AXA Mansard and Alliance.</p>
		</div>
	</section>

	<section class="info-body">
		<div class="container">
			<ul class="hmo-names hmo-names--page" data-reveal-stagger aria-label="Accepted HMO partners">
				<?php array_map( 'tristate_hmo_logo', tristate_hmo_partners() ); ?>
			</ul>

			<div class="info-panel" data-reveal="up">
				<span class="info-panel__icon" aria-hidden="true"><?php echo tristate_svg( 'shield-check', array( 'stroke' => 1.5 ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
				<div class="info-panel__copy">
					<p>Share your HMO name, membership details and the service you need. We can help check eligibility, referral requirements, prior approval and any costs you may need to pay. Coverage varies by plan and hospital.</p>
					<p>If your HMO is not listed, contact us to ask about current arrangements.</p>
				</div>
				<a class="btn btn--brand" href="<?php echo esc_url( $tristate_check_url ); ?>">Check My HMO Cover <span class="btn__arrow" aria-hidden="true">→</span></a>
			</div>
		</div>
	</section>

</div>
<?php
get_footer();
