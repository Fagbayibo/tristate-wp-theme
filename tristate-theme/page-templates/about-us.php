<?php
/**
 * Template Name: About Us
 *
 * The About Us page gets this design from its slug (see functions.php). The
 * page's own editor content is not used: the copy lives here.
 *
 * @package tristate
 */

$tristate_treat = array(
	array( 'T', 'Transparency' ),
	array( 'R', 'Respect' ),
	array( 'E', 'Empathy' ),
	array( 'A', 'Accessibility' ),
	array( 'T', 'Trust' ),
);

// Where to go next: Tabler icon, label, note, URL.
$tristate_next = array(
	array( 'users', 'Our Team', 'Get to know your care team', tristate_page_url( 'our-team' ) ),
	array( 'building-hospital', 'Our Hospitals', 'Lekki, Lagos, and Idi-Ape, Ibadan', tristate_page_url( 'our-hospitals' ) ),
	array( 'shield-check', 'HMO Partners', 'Understand your cover before your visit', tristate_page_url( 'hmo-partners' ) ),
);

$tristate_arrow = tristate_svg( 'arrow-up-right', array( 'stroke' => 2 ) );

get_header();
?>
<div class="landing landing--about">

	<!-- Hero ------------------------------------------------------------------ -->
	<section class="about-hero">
		<div class="container about-hero__inner">
			<p class="eyebrow eyebrow--center" data-reveal="up">About Tristate</p>
			<h1 class="landing-title" data-split>Built on heart care and here for your <em>wider health</em></h1>
			<p class="landing-lead" data-reveal="up" data-delay="0.35">Tristate Hospitals is part of Tristate Healthcare System, with hospitals in Lekki and Ibadan. Advanced cardiovascular care is our signature programme, alongside a wider range of medical and surgical specialties.</p>
			<div class="btn-row about-hero__actions" data-reveal="up" data-delay="0.5">
				<a class="btn btn--brand" href="<?php echo esc_url( tristate_page_url( 'our-services' ) ); ?>">Explore Our Services <span class="btn__arrow" aria-hidden="true">→</span></a>
			</div>
		</div>

		<div class="panel-wrap about-hero__stage">
			<div class="about-hero__media" data-expand>
				<img src="<?php echo esc_url( TRISTATE_URI . '/assets/images/hero-home.jpg' ); ?>" alt="Tristate clinicians reviewing a patient's results together" fetchpriority="high">
				<div class="about-hero__orbit" aria-hidden="true"></div>
			</div>
		</div>
	</section>

	<!-- T.R.E.A.T. values ------------------------------------------------------------- -->
	<section class="landing-section treat" id="values">
		<div class="container treat__grid">
			<div class="treat__aside">
				<p class="eyebrow" data-reveal="up">Our values</p>
				<h2 class="treat__word" aria-label="T.R.E.A.T." data-reveal="up" data-delay="0.1">
					<?php foreach ( $tristate_treat as $tristate_row ) : ?>
						<span aria-hidden="true"><?php echo esc_html( $tristate_row[0] ); ?></span>
					<?php endforeach; ?>
				</h2>
				<p class="landing-lead" data-reveal="up" data-delay="0.25">Our approach to care is guided by transparency, respect, empathy, accessibility and trust, our T.R.E.A.T. values. For patients, that means a commitment to honest explanations, dignity and a voice in decisions about their care.</p>
			</div>

			<ol class="treat__list">
				<?php foreach ( $tristate_treat as $tristate_row ) : ?>
					<li class="treat__item" data-reveal="up">
						<span class="treat__letter" aria-hidden="true"><?php echo esc_html( $tristate_row[0] ); ?></span>
						<h3 class="treat__title"><?php echo esc_html( $tristate_row[1] ); ?></h3>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</section>

	<!-- Next steps -------------------------------------------------------------------- -->
	<section class="about-next" aria-label="More about Tristate">
		<div class="container">
			<div class="about-next__grid" data-reveal-stagger>
				<?php foreach ( $tristate_next as $tristate_item ) : ?>
					<a class="svc-link about-next__link" href="<?php echo esc_url( $tristate_item[3] ); ?>">
						<span class="svc-link__swatch"><?php echo tristate_svg( $tristate_item[0] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
						<span class="svc-link__text"><strong><?php echo esc_html( $tristate_item[1] ); ?></strong><small><?php echo esc_html( $tristate_item[2] ); ?></small></span>
						<span class="svc-link__go"><?php echo $tristate_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

</div>
<?php
get_footer();
