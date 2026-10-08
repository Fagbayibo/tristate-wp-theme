<?php
/**
 * Template Name: About Us
 *
 * Assign it to the About Us page (Page → Template). The page's own editor
 * content is not used: the copy lives here, like the homepage sections.
 *
 * @package tristate
 */

$tristate_appointment = tristate_contact( 'appointment_url' );
$tristate_services    = tristate_template_url( 'page-templates/our-services.php', '/services/' );

$tristate_specialties = array( 'Cardiology', 'Neurology', 'Neurosurgery', 'Orthopedics', 'Nephrology', 'OBGYN', 'Internal Medicine', 'Diagnostics', 'Executive Health' );

$tristate_treat = array(
	array( 'T', 'Transparency', 'We explain findings, treatment options, risks, and next steps in plain language.' ),
	array( 'R', 'Respect', 'We treat patients, families, staff, and partners with dignity.' ),
	array( 'E', 'Empathy', 'We recognize the emotional weight of illness and support patients throughout their journey.' ),
	array( 'A', 'Accessibility', 'We make advanced specialist care reachable within Nigeria.' ),
	array( 'T', 'Trust', 'We build confidence through clinical competence, clear communication, and continuity of care.' ),
);

// Icon paths are 24×24, stroked with currentColor.
$tristate_steps = array(
	array( 'Assessment', 'Listening carefully to medical histories, symptoms, lifestyle risks, and family concerns.', '<path d="M9 4H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2"/><rect x="9" y="2.5" width="6" height="3.5" rx="1"/><path d="M9 12h6M9 16h4"/>' ),
	array( 'Diagnosis', 'Utilizing precise clinical evaluation and advanced diagnostics to establish health status.', '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5M8 11h1.5l1-2 1.5 4 1-2H14"/>' ),
	array( 'Treatment Planning', 'Specialists explain findings clearly and outline custom care options.', '<path d="M9 4 3 6v14l6-2 6 2 6-2V4l-6 2-6-2Z"/><path d="M9 4v14M15 6v14"/>' ),
	array( 'Intervention or Surgery', 'Executing procedures through experienced, multidisciplinary teams.', '<path d="M10 3h4v7h7v4h-7v7h-4v-7H3v-4h7Z"/>' ),
	array( 'Recovery', 'Supporting the return to daily life through structured rehabilitation and guidance.', '<path d="M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10Z"/><path d="M8.5 11.5h2l1-2 1.5 3 1-1.5h1.5"/>' ),
	array( 'Long-Term Management', 'Monitoring risk, preventing recurrence, and maintaining long-term wellness.', '<path d="M12 3 4 6v6c0 4.5 3.4 8.3 8 9 4.6-.7 8-4.5 8-9V6l-8-3Z"/><path d="m8.5 12 2.5 2.5 4.5-5"/>' ),
);

$tristate_arrow = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9"/></svg>';
$tristate_img   = TRISTATE_URI . '/assets/images/';

get_header();
?>
<div class="landing landing--about">

	<!-- Hero ------------------------------------------------------------------ -->
	<section class="about-hero">
		<div class="container about-hero__inner">
			<p class="eyebrow eyebrow--center" data-reveal="up">About Tristate Healthcare System</p>
			<h1 class="landing-title" data-split>Advanced Specialist Care, Driven by <em>Compassion</em> and <em>Innovation</em></h1>
			<p class="landing-lead" data-reveal="up" data-delay="0.35">Tristate Healthcare System (THS) was founded to close a critical gap in African healthcare: providing access to complex specialist care locally, without the delay, financial strain, and disruption of overseas medical travel.</p>
			<div class="btn-row about-hero__actions" data-reveal="up" data-delay="0.5">
				<a class="btn btn--brand" href="<?php echo esc_url( $tristate_appointment ); ?>">Book Appointment <span class="btn__arrow" aria-hidden="true">→</span></a>
				<a class="btn btn--outline-brand" href="#pathway">Your Care Pathway</a>
			</div>
		</div>

		<div class="panel-wrap about-hero__stage">
			<div class="about-hero__media" data-expand>
				<img src="<?php echo esc_url( $tristate_img . 'hero-home.jpg' ); ?>" alt="Tristate clinicians reviewing a patient's results together" fetchpriority="high">
				<div class="about-hero__orbit" aria-hidden="true"></div>
				<div class="about-hero__overlay">
					<figure class="about-hero__quote" data-reveal="up">
						<blockquote>“Caring for the heart from the heart.”</blockquote>
						<figcaption>Tristate Healthcare System</figcaption>
					</figure>
					<div class="about-hero__chip" data-reveal="up" data-delay="0.18">
						<span class="about-hero__chip-icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path pathLength="100" d="M2 12h4l2-5 4 10 2-5h8"/></svg>
						</span>
						<div>
							<strong>Our roots</strong>
							<span>Africa's premier cardiovascular center — now an integrated multi-specialist network.</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Specialty ticker ---------------------------------------------------------- -->
	<section class="about-ticker" aria-label="Our specialties">
		<div class="container about-ticker__row">
			<a class="btn btn--brand" href="<?php echo esc_url( $tristate_services ); ?>">Explore Services <span class="btn__arrow" aria-hidden="true">→</span></a>
			<div class="marquee" style="--speed: 46s">
				<?php for ( $tristate_copy = 0; $tristate_copy < 2; $tristate_copy++ ) : ?>
					<div class="marquee__track"<?php echo $tristate_copy ? ' aria-hidden="true"' : ''; ?>>
						<?php foreach ( $tristate_specialties as $tristate_name ) : ?>
							<span class="about-ticker__word"><?php echo esc_html( $tristate_name ); ?> <?php echo $tristate_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
						<?php endforeach; ?>
					</div>
				<?php endfor; ?>
			</div>
		</div>
	</section>

	<!-- Our story ------------------------------------------------------------------ -->
	<section class="landing-section about-story" id="story">
		<div class="container">
			<div class="landing-head">
				<div>
					<p class="eyebrow" data-reveal="up">Our Story</p>
					<h2 class="section-title" style="--title-size: 46" data-split>From one premier heart centre to a <em>multi-specialist</em> network</h2>
				</div>
				<p class="landing-lead" data-reveal="up" data-delay="0.25">We exist for patients who need more than a routine hospital visit — and for the families, employers, and referring physicians who stand beside them.</p>
			</div>

			<div class="about-story__grid">
				<article class="about-story__col" data-reveal="up">
					<p class="about-story__num">01 — Where We Began</p>
					<h3 class="about-story__title">Closing a critical gap</h3>
					<p>Tristate Healthcare System was founded so that patients could access complex specialist care locally — without the delay, financial strain, and disruption of overseas medical travel.</p>
					<div class="landing-media about-story__media" data-reveal="clip-up" data-delay="0.2">
						<img src="<?php echo esc_url( $tristate_img . 'why-2.jpg' ); ?>" alt="A Tristate clinician checking an elderly patient's blood pressure" loading="lazy" data-parallax="0.04">
					</div>
				</article>

				<article class="about-story__col" data-reveal="up" data-delay="0.18">
					<p class="about-story__num">02 — Who We Are Today</p>
					<h3 class="about-story__title">An integrated specialist network</h3>
					<p>From our roots as Africa's premier cardiovascular center, we have grown into an integrated multi-specialist healthcare network. Today, THS gives patients, families, employers, and referring physicians access to world-class clinical expertise across:</p>
					<ul class="about-story__specs" data-reveal-stagger>
						<?php foreach ( $tristate_specialties as $tristate_name ) : ?>
							<li><?php echo esc_html( $tristate_name ); ?></li>
						<?php endforeach; ?>
					</ul>
					<div class="landing-media about-story__media" data-reveal="clip-up" data-delay="0.2">
						<img src="<?php echo esc_url( $tristate_img . 'intro-2.jpg' ); ?>" alt="A Tristate doctor in consultation at her desk" loading="lazy">
					</div>
				</article>
			</div>
		</div>
	</section>

	<!-- Manifesto ------------------------------------------------------------------- -->
	<section class="panel-wrap" aria-label="Who we exist for">
		<div class="panel manifesto">
			<div class="container manifesto__inner">
				<div class="manifesto__arrows" aria-hidden="true">
					<?php for ( $tristate_i = 0; $tristate_i < 4; $tristate_i++ ) : ?>
						<span style="--i: <?php echo (int) $tristate_i; ?>"><?php echo $tristate_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
					<?php endfor; ?>
				</div>
				<p class="manifesto__text" data-words-scrub>We exist for patients who need <em>more than a routine hospital visit.</em> They need clear answers, experienced clinicians, coordinated treatment, and continuity of care that <em>does not disappear after discharge.</em></p>

				<ul class="manifesto__chips" data-reveal-stagger>
					<li>+ Clear answers</li>
					<li>+ Experienced clinicians</li>
					<li>+ Coordinated treatment</li>
					<li>+ Continuity of care</li>
				</ul>

				<div class="manifesto__promise" data-reveal="scale">
					<p class="manifesto__promise-label">One clear promise</p>
					<p class="manifesto__promise-text">Expert attention, human support, and a pathway patients can understand.</p>
					<a class="btn btn--light" href="<?php echo esc_url( $tristate_appointment ); ?>">Book Appointment <span class="btn__arrow" aria-hidden="true">→</span></a>
				</div>
			</div>
		</div>
	</section>

	<!-- Clinical record --------------------------------------------------------------- -->
	<section class="landing-section record" id="record">
		<div class="container">
			<div class="landing-head">
				<div>
					<p class="eyebrow" data-reveal="up">Proven in Nigeria</p>
					<h2 class="section-title" style="--title-size: 46" data-split>Our Clinical Record <em>at a Glance</em></h2>
				</div>
				<p class="landing-lead" data-reveal="up" data-delay="0.25">Our credibility is anchored in proven clinical delivery in Nigeria.</p>
			</div>

			<div class="record__grid">
				<div class="record-stat record-stat--hero" data-reveal="up" data-observe>
					<span class="landing-tag">Cardiac Surgery</span>
					<svg class="ecg" viewBox="0 0 600 90" preserveAspectRatio="none" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path pathLength="1000" d="M0 50 H170 L190 50 L205 20 L222 80 L240 8 L258 70 L272 50 H360 L378 50 L392 30 L408 68 L420 50 H600"/></svg>
					<div>
						<p class="record-stat__num"><span data-count="600" data-suffix="+">600+</span></p>
						<p class="record-stat__label">Reported Open-Heart Surgeries Performed</p>
					</div>
				</div>

				<div class="record-stat" data-reveal="up" data-delay="0.09">
					<span class="landing-tag">Interventions</span>
					<div>
						<p class="record-stat__num"><span data-count="2000" data-suffix="+">2,000+</span></p>
						<p class="record-stat__label">Advanced Cardiac Interventions &amp; Catheterizations</p>
					</div>
				</div>

				<div class="record-stat record-stat--blush" data-reveal="up" data-delay="0.18">
					<span class="landing-tag">Expertise</span>
					<div>
						<p class="record-stat__num"><span data-count="90" data-suffix="+">90+</span> <small>Years</small></p>
						<p class="record-stat__label">Combined Expertise of World-Class, Internationally Trained Specialists</p>
					</div>
				</div>

				<div class="record-stat record-stat--ring" data-reveal="up" data-delay="0.27" data-observe>
					<div class="record-ring" aria-hidden="true">
						<svg viewBox="0 0 140 140"><circle class="record-ring__bg" cx="70" cy="70" r="60"/><circle class="record-ring__fg" cx="70" cy="70" r="60"/></svg>
						<p class="record-stat__num"><span data-count="70" data-suffix="%">70%</span></p>
					</div>
					<div>
						<span class="landing-tag">National Impact</span>
						<p class="record-stat__label record-stat__label--lg"><strong>70%</strong> Contribution to Nigeria’s Total Cardiac Surgical Activity</p>
					</div>
				</div>

				<div class="record-stat record-stat--wide" data-reveal="up" data-delay="0.09">
					<div>
						<span class="landing-tag">Vascular Surgery</span>
						<p class="record-stat__num"><span data-count="265" data-suffix="+">265+</span></p>
						<p class="record-stat__label">Successful Vascular Surgeries</p>
					</div>
					<div class="landing-media">
						<img src="<?php echo esc_url( $tristate_img . 'service-cardiovascular.jpg' ); ?>" alt="Tristate surgical team performing a procedure" loading="lazy">
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- T.R.E.A.T. ----------------------------------------------------------------------- -->
	<section class="landing-section treat" id="philosophy">
		<div class="container treat__grid">
			<div class="treat__aside">
				<p class="eyebrow" data-reveal="up">Our Care Philosophy</p>
				<h2 class="section-title" style="--title-size: 46" data-split>Healthcare is not only about treatment; it is about <em>trust.</em></h2>
				<p class="landing-lead" data-reveal="up" data-delay="0.25">At Tristate, our patient-centric model is anchored on the T.R.E.A.T. framework.</p>
				<div class="treat__word" role="img" aria-label="T.R.E.A.T." data-reveal="up" data-delay="0.35">
					<?php foreach ( $tristate_treat as $tristate_row ) : ?>
						<span aria-hidden="true"><?php echo esc_html( $tristate_row[0] ); ?></span>
					<?php endforeach; ?>
				</div>
			</div>

			<ol class="treat__list">
				<?php foreach ( $tristate_treat as $tristate_row ) : ?>
					<li class="treat__item" data-reveal="up">
						<span class="treat__letter" aria-hidden="true"><?php echo esc_html( $tristate_row[0] ); ?></span>
						<div>
							<h3 class="treat__title"><?php echo esc_html( $tristate_row[1] ); ?></h3>
							<p><?php echo esc_html( $tristate_row[2] ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</section>

	<!-- Patient pathway --------------------------------------------------------------------- -->
	<section class="landing-section pathway" id="pathway">
		<div class="container">
			<div class="landing-head landing-head--center">
				<p class="eyebrow eyebrow--center" data-reveal="up">Six clear steps</p>
				<h2 class="section-title" style="--title-size: 46" data-split>The Tristate <em>Patient Pathway</em></h2>
				<p class="landing-lead" data-reveal="up" data-delay="0.25">Good healthcare should never leave patients guessing. We guide every individual through a clear, 6-step journey.</p>
			</div>

			<ol class="pathway__timeline" data-timeline>
				<?php foreach ( $tristate_steps as $tristate_i => $tristate_step ) : ?>
					<li class="pathway-step">
						<span class="pathway-step__node"><?php echo esc_html( sprintf( '%02d', $tristate_i + 1 ) ); ?></span>
						<div class="pathway-step__card" data-reveal="<?php echo $tristate_i % 2 ? 'left' : 'right'; ?>">
							<span class="pathway-step__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><?php echo $tristate_step[2]; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></svg></span>
							<div>
								<p class="pathway-step__kicker">Step <?php echo (int) $tristate_i + 1; ?></p>
								<h3 class="pathway-step__title"><?php echo esc_html( $tristate_step[0] ); ?></h3>
								<p><?php echo esc_html( $tristate_step[1] ); ?></p>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</section>

	<!-- Closing ------------------------------------------------------------------------------- -->
	<section class="panel-wrap closing-wrap" aria-label="Book a consultation">
		<div class="panel closing">
			<div class="closing__bg" aria-hidden="true">
				<img src="<?php echo esc_url( $tristate_img . 'appointment.jpg' ); ?>" alt="" loading="lazy" data-parallax="0.06">
			</div>
			<div class="marquee" style="--speed: 40s">
				<?php for ( $tristate_copy = 0; $tristate_copy < 2; $tristate_copy++ ) : ?>
					<div class="marquee__track"<?php echo $tristate_copy ? ' aria-hidden="true"' : ''; ?>>
						<p class="closing__word">Expert attention. <em>Human support.</em> A pathway you can understand.</p>
					</div>
				<?php endfor; ?>
			</div>
			<div class="container closing__foot">
				<p data-reveal="up">Access world-class clinical expertise in Nigeria — with clear answers, coordinated treatment, and continuity of care that does not disappear after discharge.</p>
				<div class="btn-row" data-reveal="up" data-delay="0.18">
					<a class="btn btn--light" href="<?php echo esc_url( $tristate_appointment ); ?>">Book Appointment <span class="btn__arrow" aria-hidden="true">→</span></a>
					<a class="btn btn--outline-light" href="<?php echo esc_url( $tristate_services ); ?>">Our Services</a>
				</div>
			</div>
		</div>
	</section>

</div>
<?php
get_footer();
