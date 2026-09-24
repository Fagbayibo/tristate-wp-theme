<?php
/**
 * Why Tristate — Figma 25:8.
 *
 * @package tristate
 */

$tristate_values = array(
	array( 'Affordable', 'World-class treatment at a fraction of the cost abroad.' ),
	array( 'Accessible', 'Super-specialty care available right here in Nigeria.' ),
	array( 'Compassionate', 'Care delivered from the heart, for every patient.' ),
	array( 'Exceptional', 'Outstanding services and exceptional clinical outcome.' ),
);
?>
<section class="why" aria-labelledby="why-title">
	<div class="container why__inner">
		<div class="why__copy">
			<div class="why__head" data-reveal-stagger>
				<p class="section-label"><?php esc_html_e( 'Why Tristate', 'tristate' ); ?></p>
				<h2 class="section-title" id="why-title">No need to travel abroad for <em>heart surgery</em>.</h2>
				<p class="why__lead"><?php esc_html_e( 'Foremost Cardiac Centre in Nigeria. Providing the best in class cardiovascular services that are affordable and accessible.', 'tristate' ); ?></p>
			</div>

			<ul class="why__values" data-reveal-stagger>
				<?php foreach ( $tristate_values as $tristate_value ) : ?>
					<li class="why__value">
						<span class="why__check"><?php tristate_icon( 'check', 16 ); ?></span>
						<span>
							<strong class="why__value-title"><?php echo esc_html( $tristate_value[0] ); ?></strong>
							<span class="why__value-text"><?php echo esc_html( $tristate_value[1] ); ?></span>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>

			<div data-reveal="up">
				<a class="btn btn--brand" href="<?php echo esc_url( home_url( '/our-team/' ) ); ?>">
					<?php esc_html_e( 'Meet Our Team', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span>
				</a>
			</div>
		</div>

		<div class="why__media">
			<figure class="why__pill" data-reveal="clip-up">
				<img src="<?php echo esc_url( TRISTATE_URI . '/assets/images/why-1.jpg' ); ?>" width="900" height="1244" alt="<?php esc_attr_e( 'Smiling Tristate doctor with a stethoscope', 'tristate' ); ?>" loading="lazy" data-parallax="0.05">
			</figure>
			<!-- Outer element drifts on scroll; inner one animates in (kept separate so the transforms don't clash). -->
			<div class="why__small" data-parallax="-0.12">
				<figure data-reveal="up" data-delay="0.3">
					<img src="<?php echo esc_url( TRISTATE_URI . '/assets/images/why-2.jpg' ); ?>" width="1100" height="733" alt="<?php esc_attr_e( 'Doctor checking an elderly patient’s blood pressure', 'tristate' ); ?>" loading="lazy">
				</figure>
			</div>
			<div class="why__badge" data-reveal="pop" data-delay="0.5">
				<div class="why__badge-inner">
					<span class="why__badge-num">400+</span>
					<span class="why__badge-label"><?php esc_html_e( 'Open Heart Surgeries', 'tristate' ); ?></span>
				</div>
			</div>
		</div>
	</div>
</section>
