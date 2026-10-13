<?php
/**
 * A single service page (Dialysis, Radiology, …).
 *
 * Not a selectable template: functions.php routes every page whose slug has
 * copy in inc/services.php here, whatever its Template setting says. The
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
$tristate_all_url  = tristate_template_url( 'page-templates/our-services.php', '/our-services/' );
$tristate_img      = TRISTATE_URI . '/assets/images/';
$tristate_arrow    = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9"/></svg>';
$tristate_is_er    = ! empty( $tristate_service['emergency'] );

get_header();
?>
<div class="landing landing--service">

	<!-- Hero ------------------------------------------------------------------ -->
	<section class="sd-hero">
		<div class="sd-hero__glow" aria-hidden="true"></div>
		<div class="container sd-hero__grid">
			<div class="sd-hero__copy">
				<nav class="sd-crumbs" aria-label="Breadcrumb" data-reveal="fade">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
					<span aria-hidden="true">/</span>
					<a href="<?php echo esc_url( $tristate_all_url ); ?>">Our Services</a>
					<span aria-hidden="true">/</span>
					<span aria-current="page"><?php echo wp_kses_post( $tristate_service['title'] ); ?></span>
				</nav>
				<p class="eyebrow" data-reveal="up"><?php echo esc_html( $tristate_service['tag'] ); ?></p>
				<h1 class="landing-title" data-split data-delay="0.15"><?php echo wp_kses_post( $tristate_service['heading'] ); ?></h1>
				<p class="landing-lead" data-reveal="up" data-delay="0.35"><?php echo esc_html( $tristate_service['lead'] ); ?></p>
				<div class="btn-row sd-hero__actions" data-reveal="up" data-delay="0.5">
					<a class="btn btn--brand" href="<?php echo esc_url( tristate_contact( 'appointment_url' ) ); ?>">Book Appointment <span class="btn__arrow" aria-hidden="true">→</span></a>
					<?php if ( $tristate_is_er ) : ?>
						<a class="btn btn--outline-brand" href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>">Call <?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a>
					<?php else : ?>
						<a class="btn btn--outline-brand" href="<?php echo esc_url( $tristate_all_url ); ?>">All Services</a>
					<?php endif; ?>
				</div>
			</div>

			<div class="sd-hero__visual">
				<div class="sd-hero__media" data-reveal="clip-up">
					<img src="<?php echo esc_url( $tristate_img . $tristate_service['image'] ); ?>" alt="<?php echo esc_attr( $tristate_service['alt'] ); ?>" fetchpriority="high" data-parallax="0.05">
				</div>
				<span class="sd-hero__icon" data-reveal="pop" data-delay="0.6"><?php echo tristate_line_icon( $tristate_service['icon'], '1.6' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
				<span class="sd-hero__badge" data-reveal="up" data-delay="0.8"><span class="pulse-dot" aria-hidden="true"></span><?php echo wp_kses_post( $tristate_service['badge'] ); ?></span>
			</div>
		</div>
	</section>

	<!-- Points ---------------------------------------------------------------- -->
	<section class="sd-points">
		<div class="container sd-points__grid">
			<div class="sd-points__intro">
				<p class="eyebrow" data-reveal="up">What we offer</p>
				<h2 class="section-title" style="--title-size: 46" data-split><?php echo wp_kses_post( $tristate_service['short'] ); ?> <em>at Tristate</em></h2>
				<p class="sd-points__note" data-reveal="up" data-delay="0.2">Have a question about your care? Our team will guide you to the right specialist.</p>
				<a class="sd-link" href="<?php echo esc_url( tristate_contact( 'appointment_url' ) ); ?>" data-reveal="up" data-delay="0.3">
					Book a consultation
					<span class="sd-link__circle" aria-hidden="true"><?php echo $tristate_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
				</a>
			</div>

			<ol class="sd-points__list">
				<?php foreach ( $tristate_service['points'] as $tristate_i => $tristate_point ) : ?>
					<li class="sd-point" data-observe>
						<span class="sd-point__num"><?php echo esc_html( sprintf( '%02d', $tristate_i + 1 ) ); ?></span>
						<div>
							<h3 class="sd-point__title"><?php echo wp_kses_post( $tristate_point[0] ); ?></h3>
							<p class="sd-point__text"><?php echo esc_html( $tristate_point[1] ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</section>

	<?php foreach ( isset( $tristate_service['lists'] ) ? $tristate_service['lists'] : array() as $tristate_list ) : ?>
		<?php if ( 'chips' === $tristate_list['style'] ) : ?>
			<!-- Chip list on a dark panel ------------------------------------------- -->
			<section class="sd-chips panel-wrap">
				<div class="panel sd-chips__panel">
					<div class="sd-chips__ecg" aria-hidden="true" data-observe>
						<svg class="ecg" viewBox="0 0 600 90" preserveAspectRatio="none" fill="none" stroke="currentColor" stroke-width="2"><path pathLength="1000" d="M0 50 H170 L190 50 L205 20 L222 80 L240 8 L258 70 L272 50 H360 L378 50 L392 30 L408 68 L420 50 H600"/></svg>
					</div>
					<div class="container">
						<div class="landing-head">
							<div>
								<p class="eyebrow" data-reveal="up"><?php echo wp_kses_post( $tristate_service['short'] ); ?></p>
								<h2 class="section-title" style="--title-size: 46" data-split><?php echo wp_kses_post( $tristate_list['title'] ); ?></h2>
							</div>
							<p class="landing-lead" data-reveal="up" data-delay="0.25"><?php echo esc_html( $tristate_list['intro'] ); ?></p>
						</div>
						<ul class="sd-chips__list" data-reveal-stagger>
							<?php foreach ( $tristate_list['items'] as $tristate_item ) : ?>
								<li class="sd-chip"><i aria-hidden="true"></i><?php echo wp_kses_post( $tristate_item ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</section>
		<?php else : ?>
			<!-- Numbered cards ------------------------------------------------------- -->
			<section class="sd-tests">
				<div class="container">
					<div class="landing-head">
						<div>
							<p class="eyebrow" data-reveal="up">Diagnostics</p>
							<h2 class="section-title" style="--title-size: 46" data-split><?php echo wp_kses_post( $tristate_list['title'] ); ?></h2>
						</div>
						<p class="landing-lead" data-reveal="up" data-delay="0.25"><?php echo esc_html( $tristate_list['intro'] ); ?></p>
					</div>
					<ol class="sd-tests__grid" data-reveal-stagger>
						<?php foreach ( $tristate_list['items'] as $tristate_i => $tristate_item ) : ?>
							<li class="sd-test">
								<span class="sd-test__num"><?php echo esc_html( sprintf( '%02d', $tristate_i + 1 ) ); ?></span>
								<span class="sd-test__icon"><?php echo tristate_line_icon( $tristate_service['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
								<strong class="sd-test__name"><?php echo esc_html( $tristate_item[0] ); ?></strong>
								<?php if ( $tristate_item[1] ) : ?>
									<span class="sd-test__note"><?php echo esc_html( $tristate_item[1] ); ?></span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
						<li class="sd-test sd-test--cta">
							<a href="<?php echo esc_url( tristate_contact( 'appointment_url' ) ); ?>">
								<strong class="sd-test__name">Book a test</strong>
								<span class="sd-test__note">Our team will schedule it and explain how to prepare.</span>
								<span class="sd-link__circle" aria-hidden="true"><?php echo $tristate_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
							</a>
						</li>
					</ol>
				</div>
			</section>
		<?php endif; ?>
	<?php endforeach; ?>

	<!-- Other services ----------------------------------------------------------- -->
	<section class="sd-more">
		<div class="container">
			<div class="landing-head">
				<div>
					<p class="eyebrow" data-reveal="up">Explore more</p>
					<h2 class="section-title" style="--title-size: 46" data-split>Other specialist <em>services</em></h2>
				</div>
				<p class="landing-lead" data-reveal="up" data-delay="0.25">Every service shares one coordinated platform, so moving between specialists never means starting over.</p>
			</div>
			<div class="sd-more__grid" data-reveal-stagger>
				<?php foreach ( $tristate_services as $tristate_other_slug => $tristate_other ) : ?>
					<?php
					if ( $tristate_other_slug === $tristate_slug ) {
						continue;
					}
					?>
					<a class="svc-link" href="<?php echo esc_url( home_url( "/$tristate_other_slug/" ) ); ?>">
						<span class="svc-link__swatch"><?php echo tristate_line_icon( $tristate_other['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
						<span class="svc-link__text"><strong><?php echo wp_kses_post( $tristate_other['title'] ); ?></strong><small><?php echo esc_html( $tristate_other['tag'] ); ?></small></span>
						<span class="svc-link__go"><?php echo $tristate_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Closing ---------------------------------------------------------------- -->
	<?php
	get_template_part( 'template-parts/landing-cta', null, array(
		'words'     => wp_list_pluck( $tristate_services, 'short' ),
		'eyebrow'   => $tristate_is_er ? 'Every minute counts' : 'Ready when you are',
		'title'     => $tristate_is_er ? 'In an emergency, <em>call us now</em>' : 'Talk to our <em>' . $tristate_service['short'] . '</em> team',
		'text'      => $tristate_is_er
			? 'Our emergency unit and standby ambulances are ready around the clock. For anything that is not urgent, book an appointment instead.'
			: 'Book an appointment and we will connect you with the right specialist, with clear next steps from your very first visit.',
		'secondary' => array( 'All Services', $tristate_all_url ),
	) );
	?>

</div>
<?php
get_footer();
