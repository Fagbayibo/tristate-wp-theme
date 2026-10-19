<?php
/**
 * Template Name: Patient Guide
 *
 * The Patient Guide page gets this design from its slug (see functions.php).
 * The steps run in visit order, so they are numbered.
 *
 * @package tristate
 */

$tristate_steps = array(
	array( 'Before you arrive', 'Confirm your hospital, clinic and appointment time. Bring relevant results, a medicines list and your HMO details. Follow any test preparation instructions. Tell us about language, mobility or other support needs and ask about bringing someone with you.' ),
	array( 'During your consultation', 'Describe what has changed and how it affects your life. If a test or treatment is recommended, ask why, what the alternatives are and what it may cost. If an explanation is unclear, ask us to go through it again.' ),
	array( 'Before you leave', 'Make sure you understand your medicine instructions, any warning signs and your follow-up plan. Confirm when results will be available, who will explain them and whom to contact with questions.' ),
	array( 'Share your experience', 'Tell the reception team what worked well or where you need more help. Ask how to share feedback or raise a concern with the hospital.' ),
);

get_header();
?>
<div class="landing landing--info">

	<section class="info-hero">
		<div class="blog-head__glow" aria-hidden="true"></div>
		<div class="container info-hero__inner">
			<p class="eyebrow eyebrow--center" data-reveal="up">Patient Guide</p>
			<h1 class="landing-title" data-split data-delay="0.15">Make your visit <em>work for you</em></h1>
			<p class="landing-lead" data-reveal="up" data-delay="0.35">A little preparation can help you get more from your appointment. Write down your main concerns and the questions you want answered.</p>
		</div>
	</section>

	<section class="info-body">
		<div class="container info-guide">
			<ol class="guide-steps">
				<?php foreach ( $tristate_steps as $tristate_i => $tristate_step ) : ?>
					<li class="sd-point guide-step" data-observe>
						<span class="sd-point__num"><?php echo esc_html( sprintf( '%02d', $tristate_i + 1 ) ); ?></span>
						<div>
							<h2 class="sd-point__title"><?php echo esc_html( $tristate_step[0] ); ?></h2>
							<p class="sd-point__text"><?php echo esc_html( $tristate_step[1] ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>

			<aside class="guide-aside" data-reveal="up">
				<span class="info-panel__icon" aria-hidden="true"><?php echo tristate_svg( 'calendar-event', array( 'stroke' => 1.5 ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
				<a class="btn btn--brand" href="<?php echo esc_url( tristate_contact( 'appointment_url' ) ); ?>">Request an Appointment <span class="btn__arrow" aria-hidden="true">→</span></a>
				<a class="btn btn--outline-brand" href="<?php echo esc_url( tristate_page_url( 'our-hospitals' ) ); ?>">Locations and Directions</a>
				<a class="btn btn--outline-brand" href="<?php echo esc_url( tristate_page_url( 'hmo-partners' ) ); ?>">Check My HMO Cover</a>
			</aside>
		</div>
	</section>

</div>
<?php
get_footer();
