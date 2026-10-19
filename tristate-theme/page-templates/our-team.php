<?php
/**
 * Template Name: Our Team
 *
 * The Our Team page gets this design from its slug (see functions.php).
 * Profiles come from inc/people.php; portraits are added when supplied.
 *
 * @package tristate
 */

get_header();
?>
<div class="landing landing--info">

	<section class="info-hero">
		<div class="blog-head__glow" aria-hidden="true"></div>
		<div class="container info-hero__inner">
			<p class="eyebrow eyebrow--center" data-reveal="up">Our Leadership Team</p>
			<h1 class="landing-title" data-split data-delay="0.15">Get to know your <em>care team</em></h1>
			<p class="landing-lead" data-reveal="up" data-delay="0.35">Learn about our clinicians’ experience and areas of practice, and find out where they consult. Choose a specialty or hospital to begin.</p>
			<div class="btn-row info-hero__actions" data-reveal="up" data-delay="0.5">
				<a class="btn btn--brand" href="<?php echo esc_url( tristate_page_url( 'our-services' ) ); ?>">Browse by Specialty <span class="btn__arrow" aria-hidden="true">→</span></a>
				<a class="btn btn--outline-brand" href="<?php echo esc_url( tristate_page_url( 'our-hospitals' ) ); ?>">Browse by Hospital</a>
			</div>
		</div>
	</section>

	<section class="info-body">
		<div class="container">
			<ul class="leaders" data-reveal-stagger>
				<?php foreach ( tristate_leaders() as $tristate_person ) : ?>
					<li class="leader">
						<span class="leader__portrait" aria-hidden="true"><?php echo esc_html( tristate_initials( $tristate_person['name'] ) ); ?></span>
						<div class="leader__body">
							<h2 class="leader__name"><?php echo esc_html( $tristate_person['name'] ); ?></h2>
							<p class="leader__title"><?php echo esc_html( $tristate_person['title'] ); ?></p>
							<?php if ( $tristate_person['specialty'] ) : ?>
								<p class="person-card__specialty"><?php echo esc_html( $tristate_person['specialty'] ); ?></p>
							<?php endif; ?>
							<p class="leader__bio"><?php echo esc_html( $tristate_person['bio'] ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>

			<ul class="clinicians" data-reveal-stagger>
				<?php foreach ( tristate_clinicians() as $tristate_name ) : ?>
					<li class="person-card">
						<span class="person-card__portrait" aria-hidden="true"><?php echo esc_html( tristate_initials( $tristate_name ) ); ?></span>
						<div>
							<h2 class="person-card__name"><?php echo esc_html( $tristate_name ); ?></h2>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

</div>
<?php
get_footer();
