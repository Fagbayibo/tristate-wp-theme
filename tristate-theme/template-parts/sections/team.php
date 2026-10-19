<?php
/**
 * Home: the people caring for you, leading to Our Team.
 *
 * @package tristate
 */
?>
<section class="home-team landing-section" aria-labelledby="team-title">
	<div class="container home-team__grid">
		<div class="home-team__copy">
			<p class="eyebrow" data-reveal="up"><?php esc_html_e( 'Our Team', 'tristate' ); ?></p>
			<h2 class="section-title" id="team-title" data-split>Meet the people <em>caring for you</em></h2>
			<p class="landing-lead" data-reveal="up" data-delay="0.2"><?php esc_html_e( 'Find a clinician by specialty and hospital, and learn about their experience before requesting an appointment.', 'tristate' ); ?></p>
			<div class="btn-row" data-reveal="up" data-delay="0.3">
				<a class="btn btn--brand" href="<?php echo esc_url( tristate_page_url( 'our-team' ) ); ?>"><?php esc_html_e( 'Meet Our Team', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span></a>
			</div>
		</div>

		<ul class="home-team__people" data-reveal-stagger>
			<?php foreach ( tristate_leaders() as $tristate_person ) : ?>
				<li class="person-card">
					<?php tristate_portrait( $tristate_person, 'person-card__portrait' ); ?>
					<div>
						<h3 class="person-card__name"><a class="card-link" href="<?php echo esc_url( tristate_page_url( 'our-team' ) ); ?>"><?php echo esc_html( $tristate_person['name'] ); ?></a></h3>
						<p class="person-card__role"><?php echo esc_html( $tristate_person['title'] ); ?></p>
						<?php if ( $tristate_person['specialty'] ) : ?>
							<p class="person-card__specialty"><?php echo esc_html( $tristate_person['specialty'] ); ?></p>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
