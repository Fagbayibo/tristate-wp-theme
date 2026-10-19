<?php
/**
 * A single service page (Family Medicine, Radiology, …).
 *
 * Not a selectable template: functions.php routes every page whose slug is a
 * service in inc/services.php here, whatever its Template setting says. The
 * page's own editor content is not used.
 *
 * @package tristate
 */

$tristate_slug    = get_post_field( 'post_name', get_queried_object_id() );
$tristate_service = tristate_service( $tristate_slug );

if ( ! $tristate_service ) { // only reachable if routing and data disagree
	locate_template( 'page.php', true );
	return;
}

$tristate_services = tristate_services();
$tristate_groups   = tristate_service_groups();
list( $tristate_group_key, $tristate_group ) = tristate_service_group( $tristate_slug );

$tristate_img      = TRISTATE_URI . '/assets/images/';
$tristate_arrow    = tristate_svg( 'arrow-up-right', array( 'stroke' => 2 ) );
$tristate_is_er    = ! empty( $tristate_service['emergency'] );
$tristate_cta      = $tristate_service['cta'];
$tristate_cta_url  = tristate_cta_url( $tristate_cta[1], $tristate_slug );
$tristate_sections = isset( $tristate_service['sections'] ) ? $tristate_service['sections'] : array();
$tristate_intro    = $tristate_service['intro'];
$tristate_heading  = isset( $tristate_service['heading'] ) ? $tristate_service['heading'] : esc_html( $tristate_service['title'] );
$tristate_eyebrow  = isset( $tristate_service['eyebrow'] ) ? $tristate_service['eyebrow'] : $tristate_group['label'];

/** Button to a section's or the page's call to action. */
$tristate_cta_button = static function ( $cta, $class = 'btn btn--brand' ) use ( $tristate_slug ) {
	printf(
		'<a class="%s" href="%s">%s <span class="btn__arrow" aria-hidden="true">→</span></a>',
		esc_attr( $class ),
		esc_url( tristate_cta_url( $cta[1], $tristate_slug ) ),
		esc_html( $cta[0] )
	);
};

// Related: the rest of this area of care, plus areas that cross-link this service
// (Cardiothoracic Surgery appears under both Heart and Chest Care and Surgery).
$tristate_related = array_diff( $tristate_group['services'], array( $tristate_slug ) );
foreach ( isset( $tristate_group['also'] ) ? $tristate_group['also'] : array() as $tristate_also ) {
	$tristate_related[] = $tristate_also;
}
foreach ( $tristate_groups as $tristate_other ) {
	if ( isset( $tristate_other['also'] ) && in_array( $tristate_slug, $tristate_other['also'], true ) ) {
		$tristate_related = array_merge( $tristate_related, $tristate_other['services'] );
	}
}
$tristate_related = array_values( array_unique( array_diff( $tristate_related, array( $tristate_slug ) ) ) );

get_header();
?>
<div class="landing landing--service<?php echo $tristate_is_er ? ' landing--emergency' : ''; ?>">

	<!-- Hero ------------------------------------------------------------------ -->
	<section class="sd-hero">
		<div class="sd-hero__glow" aria-hidden="true"></div>
		<div class="container sd-hero__grid">
			<div class="sd-hero__copy">
				<nav class="sd-crumbs" aria-label="Breadcrumb" data-reveal="fade">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
					<span aria-hidden="true">/</span>
					<a href="<?php echo esc_url( tristate_page_url( 'our-services' ) ); ?>">Our Services</a>
					<span aria-hidden="true">/</span>
					<a href="<?php echo esc_url( tristate_page_url( 'our-services', $tristate_group_key ) ); ?>"><?php echo esc_html( $tristate_group['label'] ); ?></a>
					<?php if ( $tristate_group['label'] !== $tristate_service['title'] ) : ?>
						<span aria-hidden="true">/</span>
						<span aria-current="page"><?php echo esc_html( $tristate_service['title'] ); ?></span>
					<?php endif; ?>
				</nav>
				<p class="eyebrow" data-reveal="up"><?php echo esc_html( $tristate_eyebrow ); ?></p>
				<h1 class="landing-title" data-split data-delay="0.15"><?php echo wp_kses( $tristate_heading, array( 'em' => array() ) ); ?></h1>
				<p class="landing-lead" data-reveal="up" data-delay="0.3"><?php echo esc_html( $tristate_intro[0] ); ?></p>
				<?php foreach ( array_slice( $tristate_intro, 1 ) as $tristate_para ) : ?>
					<p class="sd-hero__text" data-reveal="up" data-delay="0.35"><?php echo esc_html( $tristate_para ); ?></p>
				<?php endforeach; ?>
				<div class="btn-row sd-hero__actions" data-reveal="up" data-delay="0.45">
					<?php $tristate_cta_button( $tristate_cta ); ?>
					<?php if ( $tristate_is_er ) : ?>
						<a class="btn btn--outline-brand" href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>">Call <?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a>
					<?php else : ?>
						<a class="btn btn--outline-brand" href="<?php echo esc_url( tristate_page_url( 'our-services' ) ); ?>">All Services</a>
					<?php endif; ?>
				</div>
			</div>

			<div class="sd-hero__visual">
				<div class="sd-hero__media" data-reveal="clip-up">
					<img src="<?php echo esc_url( $tristate_img . $tristate_service['image'] ); ?>" alt="<?php echo esc_attr( $tristate_service['alt'] ); ?>" fetchpriority="high" data-parallax="0.05">
				</div>
				<span class="sd-hero__icon" data-reveal="pop" data-delay="0.6"><?php echo tristate_svg( $tristate_service['icon'], array( 'stroke' => 1.5 ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
				<?php if ( $tristate_is_er ) : ?>
					<a class="sd-hero__badge" href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>" data-reveal="up" data-delay="0.8"><span class="pulse-dot" aria-hidden="true"></span><?php echo esc_html( sprintf( 'Emergency line: %s', tristate_contact( 'emergency' ) ) ); ?></a>
				<?php else : ?>
					<span class="sd-hero__badge" data-reveal="up" data-delay="0.8"><?php echo esc_html( $tristate_group['label'] ); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php if ( $tristate_sections ) : ?>
		<!-- Sections, with an "On this page" list beside them ------------------------ -->
		<section class="sd-body">
			<div class="container sd-body__grid">
				<nav class="sd-toc" aria-label="On this page">
					<p class="sd-toc__title">On this page</p>
					<ol class="sd-toc__list">
						<?php foreach ( $tristate_sections as $tristate_section ) : ?>
							<li><a href="#<?php echo esc_attr( $tristate_section['id'] ); ?>"><?php echo esc_html( $tristate_section['title'] ); ?></a></li>
						<?php endforeach; ?>
					</ol>
					<?php $tristate_cta_button( $tristate_cta, 'btn btn--brand sd-toc__cta' ); ?>
				</nav>

				<div class="sd-sections">
					<?php foreach ( $tristate_sections as $tristate_section ) : ?>
						<section class="sd-section<?php echo isset( $tristate_section['kicker'] ) ? ' sd-section--chapter' : ''; ?>" id="<?php echo esc_attr( $tristate_section['id'] ); ?>" aria-labelledby="<?php echo esc_attr( $tristate_section['id'] ); ?>-title">
							<?php if ( isset( $tristate_section['kicker'] ) ) : ?>
								<p class="eyebrow" data-reveal="up"><?php echo esc_html( $tristate_section['kicker'] ); ?></p>
							<?php endif; ?>
							<h2 class="sd-section__title" id="<?php echo esc_attr( $tristate_section['id'] ); ?>-title" data-reveal="up"><?php echo esc_html( $tristate_section['title'] ); ?></h2>

							<?php foreach ( isset( $tristate_section['paras'] ) ? $tristate_section['paras'] : array() as $tristate_para ) : ?>
								<p class="sd-section__text" data-reveal="up"><?php echo esc_html( $tristate_para ); ?></p>
							<?php endforeach; ?>

							<?php if ( ! empty( $tristate_section['tests'] ) ) : ?>
								<ul class="sd-tests" data-reveal-stagger>
									<?php foreach ( $tristate_section['tests'] as $tristate_test ) : ?>
										<li class="sd-test" id="<?php echo esc_attr( $tristate_test[2] ); ?>">
											<h3 class="sd-test__name"><?php echo esc_html( $tristate_test[0] ); ?></h3>
											<p class="sd-test__text"><?php echo esc_html( $tristate_test[1] ); ?></p>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php if ( ! empty( $tristate_section['defs'] ) ) : ?>
								<ul class="sd-defs" data-reveal-stagger>
									<?php foreach ( $tristate_section['defs'] as $tristate_def ) : ?>
										<li class="sd-def" id="<?php echo esc_attr( $tristate_def[2] ); ?>"><strong><?php echo esc_html( $tristate_def[0] ); ?>:</strong> <?php echo esc_html( $tristate_def[1] ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php
							if ( ! empty( $tristate_section['link'] ) ) :
								$tristate_linked = tristate_service( $tristate_section['link'] );
								?>
								<a class="sd-crosslink" href="<?php echo esc_url( tristate_service_url( $tristate_section['link'] ) ); ?>" data-reveal="up">
									<span class="sd-crosslink__icon" aria-hidden="true"><?php echo tristate_svg( $tristate_linked['icon'], array( 'stroke' => 1.5 ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
									<span class="sd-crosslink__text"><?php echo esc_html( strtok( $tristate_linked['intro'][0], '.' ) . '.' ); // first sentence: the page itself has the rest ?></span>
									<span class="sd-crosslink__go"><span class="screen-reader-text"><?php echo esc_html( $tristate_linked['title'] ); ?></span><span class="sd-link__circle" aria-hidden="true"><?php echo $tristate_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span></span>
								</a>
							<?php endif; ?>

							<?php if ( ! empty( $tristate_section['cta'] ) ) : ?>
								<div class="btn-row sd-section__cta" data-reveal="up"><?php $tristate_cta_button( $tristate_section['cta'] ); ?></div>
							<?php endif; ?>
						</section>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- Enquiry details ---------------------------------------------------------- -->
	<section class="sd-enquire" aria-label="Contact details">
		<div class="container">
			<ul class="sd-enquire__list" data-reveal-stagger>
				<li class="sd-enquire__item">
					<span class="sd-enquire__icon" aria-hidden="true"><?php echo tristate_svg( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
					<span class="sd-enquire__label">General enquiries</span>
					<a class="sd-enquire__value" href="mailto:<?php echo esc_attr( tristate_contact( 'email' ) ); ?>"><?php echo esc_html( tristate_contact( 'email' ) ); ?></a>
				</li>
				<li class="sd-enquire__item">
					<span class="sd-enquire__icon" aria-hidden="true"><?php echo tristate_svg( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
					<span class="sd-enquire__label">Emergency line</span>
					<a class="sd-enquire__value" href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>"><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a>
				</li>
				<li class="sd-enquire__item">
					<span class="sd-enquire__icon" aria-hidden="true"><?php echo tristate_svg( 'map-pin' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
					<span class="sd-enquire__label">Lekki and Ibadan</span>
					<a class="sd-enquire__value" href="<?php echo esc_url( tristate_page_url( 'our-hospitals' ) ); ?>">Locations and Directions</a>
				</li>
			</ul>
		</div>
	</section>

	<!-- Related and all services ---------------------------------------------------------- -->
	<section class="sd-more">
		<div class="container">
			<?php if ( $tristate_related ) : ?>
				<div class="sd-more__block">
					<p class="eyebrow" data-reveal="up">Related services</p>
					<div class="sd-more__grid" data-reveal-stagger>
						<?php foreach ( $tristate_related as $tristate_other_slug ) : ?>
							<?php list( , $tristate_other_group ) = tristate_service_group( $tristate_other_slug ); ?>
							<a class="svc-link" href="<?php echo esc_url( tristate_service_url( $tristate_other_slug ) ); ?>">
								<span class="svc-link__swatch"><?php echo tristate_svg( $tristate_services[ $tristate_other_slug ]['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
								<span class="svc-link__text"><strong><?php echo esc_html( $tristate_services[ $tristate_other_slug ]['title'] ); ?></strong><small><?php echo esc_html( $tristate_other_group['label'] ); ?></small></span>
								<span class="svc-link__go"><?php echo $tristate_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="landing-head">
				<div>
					<p class="eyebrow" data-reveal="up">Our Services</p>
					<h2 class="section-title" style="--title-size: 46" data-split>Find care for <em>what matters to you</em></h2>
				</div>
				<p class="landing-lead" data-reveal="up" data-delay="0.2">Choose an area of care below. If you are unsure where to begin, ask our team for guidance. Services and clinic schedules vary by hospital.</p>
			</div>
			<div class="sd-more__grid" data-reveal-stagger>
				<?php foreach ( $tristate_groups as $tristate_key => $tristate_other ) : ?>
					<?php
					if ( $tristate_key === $tristate_group_key ) {
						continue;
					}
					?>
					<a class="svc-link" href="<?php echo esc_url( tristate_page_url( 'our-services', $tristate_key ) ); ?>">
						<span class="svc-link__swatch"><?php echo tristate_svg( $tristate_other['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
						<span class="svc-link__text"><strong><?php echo esc_html( $tristate_other['label'] ); ?></strong><small><?php echo esc_html( $tristate_other['summary'] ); ?></small></span>
						<span class="svc-link__go"><?php echo $tristate_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

</div>
<?php
get_footer();
