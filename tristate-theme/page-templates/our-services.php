<?php
/**
 * Template Name: Our Services
 *
 * Assign it to the Our Services page (Page → Template). The page's own editor
 * content is not used: the copy lives here, like the homepage sections.
 *
 * @package tristate
 */

$tristate_appointment = tristate_contact( 'appointment_url' );
$tristate_about       = tristate_template_url( 'page-templates/about-us.php', '/about-us/' );
$tristate_img         = TRISTATE_URI . '/assets/images/';
$tristate_arrow       = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9"/></svg>';

$tristate_icon = 'tristate_line_icon'; // inc/menu.php

// id, title, short name (closing marquee), tag, icon, swatch colours, copy, page slug, image, image alt, badge
$tristate_services = array(
	array( 'svc-lab', 'Laboratory Services', 'Laboratory', 'Diagnostics', 'flask', '#8c101b', '#5e0d17', 'Delivering high-yield clinical testing, pathology, and biochemical analysis with rapid turnaround times to ensure accurate diagnosis and personalized treatment planning.', 'laboratory-services', 'service-laboratory.jpg', 'Tristate laboratory scientist at work', 'Rapid turnaround' ),
	array( 'svc-obgyn', 'Obstetrics &amp; Gynaecology', 'Obstetrics &amp; Gynaecology', "Women's Health", 'venus', '#e8c9cd', '#b86a75', 'Providing comprehensive, compassionate healthcare for women across all life stages, covering routine ante-natal care, complex maternal-fetal health, and specialized gynecological procedures.', 'obstetrics-and-gynaecology', 'intro-1.jpg', 'A smiling Tristate patient', 'Every life stage' ),
	array( 'svc-pharma', 'Pharmaceutical Services', 'Pharmaceutical', 'Medication Safety', 'capsule', '#3a1a20', '#8c101b', 'Ensuring safe, precision medication dispensing and personalized therapy management to optimize recovery and prevent adverse interactions for both inpatient and outpatient care.', 'pharmaceutical-services', 'intro-3.jpg', 'A Tristate clinician with a stethoscope', 'Inpatient &amp; outpatient' ),
	array( 'svc-anaes', 'Anaesthesiology', 'Anaesthesiology', 'Perioperative Care', 'monitor', '#a3343f', '#5e0d17', 'Offering expert perioperative care, specialized surgical sedation, and advanced physiological monitoring to maximize patient safety and comfort during complex medical operations.', 'anaesthesiology', 'service-cardiovascular.jpg', 'Tristate surgical team in theatre', 'Safety &amp; comfort' ),
	array( 'svc-opd', 'Outpatient Department (OPD)', 'Outpatient', 'Consultations', 'stetho', '#d79aa3', '#8c101b', 'Serving as a structured entry point for direct consultant access, comprehensive second opinions, and routine follow-up care without unnecessary delays.', 'outpatient-department', 'intro-2.jpg', 'A Tristate consultant at her desk', 'Direct consultant access' ),
	array( 'svc-edu', 'Education &amp; Research', 'Education &amp; Research', 'Innovation', 'cap', '#5e0d17', '#2a0a10', 'Pioneering medical innovation and training local healthcare professionals to continuously advance specialist medical standards and treatment outcomes across Nigeria.', 'education-research', 'service-radiology.jpg', 'Tristate clinicians reviewing findings together', 'Advancing standards' ),
	array( 'svc-er', 'Emergency &amp; Critical Care Services', 'Emergency &amp; Critical Care', '24/7', 'cross', '#c2283a', '#8c101b', 'Providing 24/7 rapid stabilization, intensive care monitoring, and immediate interventional routing for acute, life-threatening medical emergencies.', 'emergency-critical-services', 'service-emergency-alt.jpg', 'Tristate emergency response team', 'Open 24/7' ),
);
$tristate_total = count( $tristate_services );

// Floating hero tiles: position, size, rotation, float timing, pointer depth, image or icon.
$tristate_tiles = array(
	array( 'left:6%;top:24%', 128, -8, '7s', '0s', 0.9, 'why-1.jpg', '', '72px' ),
	array( 'left:15%;top:62%', 96, 6, '8s', '-2s', 0.5, '', 'flask', '' ),
	array( 'left:22%;top:13%', 78, 10, '6s', '-1s', 0.3, '', 'stetho blush', '8px' ),
	array( 'left:24%;top:80%', 92, -4, '9s', '-4s', 0.45, 'intro-1.jpg', '', '' ),
	array( 'right:6%;top:20%', 136, 8, '7.5s', '-3s', 0.85, 'service-laboratory.jpg', '', '58px' ),
	array( 'right:20%;top:11%', 76, -10, '6.5s', '-2.5s', 0.35, '', 'capsule blush', '6px' ),
	array( 'right:13%;top:62%', 120, -6, '8.5s', '-1.5s', 0.7, 'service-emergency-alt.jpg', '', '' ),
	array( 'right:29%;top:82%', 70, 12, '7s', '-5s', 0.4, '', 'cross', '' ),
);

get_header();
?>
<div class="landing landing--services">

	<!-- Hero with floating tiles -------------------------------------------------- -->
	<section class="svc-hero" data-tiles>
		<div class="svc-hero__rings" aria-hidden="true"><span></span><span></span><span></span></div>

		<?php foreach ( $tristate_tiles as $tristate_i => $tristate_tile ) : ?>
			<?php
			list( $tristate_pos, $tristate_size, $tristate_rot, $tristate_dur, $tristate_delay, $tristate_depth, $tristate_photo, $tristate_glyph, $tristate_mobile_top ) = $tristate_tile;
			$tristate_glyph = explode( ' ', $tristate_glyph );
			$tristate_class = 'svc-tile' . ( $tristate_mobile_top ? '' : ' svc-tile--desktop' );
			$tristate_inner = 'svc-tile__in' . ( $tristate_photo ? '' : ' is-icon' ) . ( in_array( 'blush', $tristate_glyph, true ) ? ' is-blush' : '' );
			?>
			<div class="<?php echo esc_attr( $tristate_class ); ?>" style="<?php echo esc_attr( $tristate_pos . ( $tristate_mobile_top ? ';--mt:' . $tristate_mobile_top : '' ) ); ?>" data-depth="<?php echo esc_attr( $tristate_depth ); ?>" aria-hidden="true">
				<div class="svc-tile__float" style="<?php echo esc_attr( "--dur:$tristate_dur;--delay:$tristate_delay" ); ?>">
					<div class="<?php echo esc_attr( $tristate_inner ); ?>" style="<?php echo esc_attr( "--s:{$tristate_size}px;--r:{$tristate_rot}deg;--i:$tristate_i" ); ?>">
						<?php if ( $tristate_photo ) : ?>
							<img src="<?php echo esc_url( $tristate_img . $tristate_photo ); ?>" alt="">
						<?php else : ?>
							<?php echo $tristate_icon( $tristate_glyph[0], '1.6' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>

		<div class="svc-hero__center">
			<p class="eyebrow eyebrow--center" data-reveal="up">Our Services</p>
			<h1 class="landing-title" data-split data-delay="0.3">Specialist Medical Care Under One <em>Coordinated Platform</em></h1>
			<p class="landing-lead" data-reveal="up" data-delay="0.45">At Tristate Healthcare System, we combine advanced medical technology, experienced specialists, and coordinated pathways to deliver care you can trust and compassion you can feel.</p>
			<div class="btn-row svc-hero__actions" data-reveal="up" data-delay="0.6">
				<a class="btn btn--brand" href="#services">Explore Services <span class="btn__arrow svc-hero__down" aria-hidden="true">↓</span></a>
				<a class="btn btn--outline-brand" href="<?php echo esc_url( $tristate_appointment ); ?>">Book Appointment</a>
			</div>
		</div>

		<div class="svc-hero__cue" aria-hidden="true"><i></i>Scroll</div>
	</section>

	<!-- Pillars ------------------------------------------------------------------------ -->
	<section class="svc-pillars">
		<div class="container">
			<div class="svc-pillars__grid" data-reveal-stagger>
				<?php
				$tristate_pillars = array(
					array( 'monitor', 'Advanced Medical Technology', 'Precise clinical evaluation and advanced diagnostics.' ),
					array( 'doctor', 'Experienced Specialists', 'World-class, internationally trained clinicians.' ),
					array( 'pathway', 'Coordinated Pathways', 'One clear journey from assessment to long-term management.' ),
				);
				foreach ( $tristate_pillars as $tristate_i => $tristate_pillar ) :
					?>
					<article class="svc-pillar">
						<span class="svc-pillar__num"><?php echo esc_html( sprintf( '%02d', $tristate_i + 1 ) ); ?></span>
						<span class="svc-pillar__icon"><?php echo $tristate_icon( $tristate_pillar[0] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
						<h3 class="svc-pillar__title"><?php echo esc_html( $tristate_pillar[1] ); ?></h3>
						<p><?php echo esc_html( $tristate_pillar[2] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
			<p class="svc-statement" data-split>Care you can <em>trust.</em> Compassion you can <em>feel.</em></p>
		</div>
	</section>

	<!-- Service index ----------------------------------------------------------------------- -->
	<section class="svc-index" id="services">
		<div class="container">
			<div class="landing-head">
				<div>
					<p class="eyebrow" data-reveal="up">What we offer</p>
					<h2 class="section-title" style="--title-size: 46" data-split>Seven specialist services, <em>one platform</em></h2>
				</div>
				<p class="landing-lead" data-reveal="up" data-delay="0.25">Choose a service to jump straight to it, or scroll to explore each one in turn.</p>
			</div>

			<div class="svc-index__grid" data-reveal-stagger>
				<?php foreach ( $tristate_services as $tristate_svc ) : ?>
					<a class="svc-link" href="#<?php echo esc_attr( $tristate_svc[0] ); ?>">
						<span class="svc-link__swatch" style="<?php echo esc_attr( "--c1:{$tristate_svc[5]};--c2:{$tristate_svc[6]}" ); ?>"><?php echo $tristate_icon( $tristate_svc[4] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
						<span class="svc-link__text"><strong><?php echo wp_kses_post( $tristate_svc[1] ); ?></strong><small><?php echo esc_html( $tristate_svc[3] ); ?></small></span>
						<span class="svc-link__go"><?php echo $tristate_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Stacked service cards ----------------------------------------------------------------- -->
	<section class="svc-stack" aria-label="Service details">
		<div class="container svc-stack__inner">
			<?php foreach ( $tristate_services as $tristate_i => $tristate_svc ) : ?>
				<?php
				$tristate_variant = array( '', ' svc-card--blush', ' svc-card--dark' )[ $tristate_i % 3 ];
				?>
				<article class="svc-card<?php echo esc_attr( $tristate_variant ); ?>" id="<?php echo esc_attr( $tristate_svc[0] ); ?>" style="--i: <?php echo (int) $tristate_i; ?>" data-observe>
					<div class="svc-card__inner">
						<div class="svc-card__body">
							<div class="svc-card__meta">
								<span class="svc-card__count"><?php echo esc_html( sprintf( '%02d', $tristate_i + 1 ) ); ?> <span>/ <?php echo esc_html( sprintf( '%02d', $tristate_total ) ); ?></span></span>
								<span class="landing-tag"><?php echo esc_html( $tristate_svc[3] ); ?></span>
							</div>
							<span class="svc-card__icon"><?php echo $tristate_icon( $tristate_svc[4] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
							<h3 class="svc-card__title"><?php echo wp_kses_post( $tristate_svc[1] ); ?></h3>
							<p class="svc-card__text"><?php echo esc_html( $tristate_svc[7] ); ?></p>
							<a class="svc-card__link" href="<?php echo esc_url( home_url( '/' . $tristate_svc[8] . '/' ) ); ?>">
								Learn more <span class="screen-reader-text">about <?php echo wp_kses_post( $tristate_svc[1] ); ?></span>
								<span class="svc-card__link-circle" aria-hidden="true"><?php echo $tristate_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
							</a>
						</div>
						<div class="svc-card__media">
							<img src="<?php echo esc_url( $tristate_img . $tristate_svc[9] ); ?>" alt="<?php echo esc_attr( $tristate_svc[10] ); ?>" loading="lazy">
							<span class="svc-card__badge"><span class="pulse-dot" aria-hidden="true"></span><?php echo wp_kses_post( $tristate_svc[11] ); ?></span>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>

	<!-- Closing ------------------------------------------------------------------------------- -->
	<section class="svc-cta" aria-label="Get started">
		<div class="marquee" style="--speed: 50s">
			<?php for ( $tristate_copy = 0; $tristate_copy < 2; $tristate_copy++ ) : ?>
				<div class="marquee__track"<?php echo $tristate_copy ? ' aria-hidden="true"' : ''; ?>>
					<?php foreach ( $tristate_services as $tristate_svc ) : ?>
						<span class="svc-cta__word"><?php echo wp_kses_post( $tristate_svc[2] ); ?> <i aria-hidden="true"></i></span>
					<?php endforeach; ?>
				</div>
			<?php endfor; ?>
		</div>

		<div class="container">
			<div class="svc-cta__card" data-reveal="up">
				<div class="svc-cta__main">
					<p class="eyebrow">Not sure where to start?</p>
					<h2 class="section-title" style="--title-size: 40" data-split>Begin with our <em>Outpatient Department</em></h2>
					<p class="svc-cta__text">A structured entry point for direct consultant access, comprehensive second opinions, and routine follow-up care without unnecessary delays.</p>
					<div class="btn-row">
						<a class="btn btn--brand" href="<?php echo esc_url( $tristate_appointment ); ?>">Book Appointment <span class="btn__arrow" aria-hidden="true">→</span></a>
						<a class="btn btn--outline-brand" href="<?php echo esc_url( $tristate_about ); ?>">About Tristate</a>
					</div>
				</div>
				<div class="svc-cta__emergency" data-observe>
					<p class="svc-cta__emergency-label"><span class="pulse-dot" aria-hidden="true"></span>24/7 Emergency line</p>
					<a class="svc-cta__emergency-number" href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>"><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a>
					<p class="svc-cta__emergency-text">Rapid stabilization, intensive care monitoring, and immediate interventional routing.</p>
					<svg class="ecg" viewBox="0 0 600 90" preserveAspectRatio="none" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path pathLength="1000" d="M0 50 H170 L190 50 L205 20 L222 80 L240 8 L258 70 L272 50 H360 L378 50 L392 30 L408 68 L420 50 H600"/></svg>
				</div>
			</div>
		</div>
	</section>

</div>
<?php
get_footer();
