<?php
/**
 * Services — bento layout from Design 1 (Figma 5:8), used in the Design 2 homepage.
 *
 * @package tristate
 */

$tristate_services = array(
	array(
		'title' => 'Cardiovascular Services',
		'tag'   => 'Flagship',
		'text'  => 'Foremost Cardiac Centre in Nigeria. Providing the best in class cardiovascular services that are affordable and accessible.',
		'image' => 'service-cardiovascular.jpg',
		'url'   => home_url( '/cardiovascular-services/' ),
	),
	array(
		'title' => 'Laboratory Services',
		'tag'   => 'Diagnostics',
		'text'  => 'Laboratory tests plays a crucial role in the detection, diagnosis and treatment of diseases in patients.',
		'image' => 'service-laboratory.jpg',
		'url'   => home_url( '/laboratory-services/' ),
	),
	array(
		'title' => 'Radiology',
		'tag'   => 'Imaging',
		'text'  => 'Cutting-edge digital imaging technology, highly trained technologists and certified radiologists.',
		'image' => 'service-radiology.jpg',
		'url'   => home_url( '/radiology/' ),
	),
	array(
		'title' => 'Emergency & Critical Care Services',
		'tag'   => '24/7',
		'text'  => 'Emergency medical services to efficiently handle life-threatening situations.',
		'image' => 'service-emergency-alt.jpg',
		'url'   => home_url( '/emergency-critical-services/' ),
	),
);

// Label => page slug on the live site.
$tristate_specialties = array(
	'Anaesthesiology'             => 'anaesthesiology',
	'Dialysis'                    => 'dialysis',
	'Obstetrics and Gynaecology'  => 'obstetrics-and-gynaecology',
	'Outpatient'                  => 'outpatient-department',
	'Pharmaceutical'              => 'pharmaceutical-services',
	'Respiratory & Critical Care' => 'respiratory-critical-care',
);
?>
<section class="services" aria-labelledby="services-title">
	<div class="container">
		<div class="services__header">
			<div class="services__heading" data-reveal-stagger>
				<p class="section-label"><?php esc_html_e( 'Our Services', 'tristate' ); ?></p>
				<h2 class="section-title" id="services-title">Super-specialty care, <em>all under one roof</em>.</h2>
			</div>
			<div class="services__aside" data-reveal-stagger>
				<p><?php esc_html_e( 'Best in class cardiovascular services that are affordable and accessible — backed by a full suite of diagnostic and critical care.', 'tristate' ); ?></p>
				<div>
					<a class="btn btn--outline-brand" href="<?php echo esc_url( home_url( '/our-services/' ) ); ?>">
						<?php esc_html_e( 'View All Services', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span>
					</a>
				</div>
			</div>
		</div>

		<ul class="services__bento" data-reveal-stagger>
			<?php foreach ( $tristate_services as $tristate_i => $tristate_service ) : ?>
				<li class="services__cell services__cell--<?php echo (int) $tristate_i + 1; ?>">
					<a class="service-tile<?php echo 0 === $tristate_i ? ' service-tile--large' : ''; ?>" href="<?php echo esc_url( $tristate_service['url'] ); ?>">
						<img class="service-tile__image" src="<?php echo esc_url( TRISTATE_URI . '/assets/images/' . $tristate_service['image'] ); ?>" alt="" loading="lazy">
						<span class="service-tile__tag"><?php echo esc_html( $tristate_service['tag'] ); ?></span>
						<div class="service-tile__content">
							<h3 class="service-tile__title"><?php echo esc_html( $tristate_service['title'] ); ?></h3>
							<p class="service-tile__text"><?php echo esc_html( $tristate_service['text'] ); ?></p>
						</div>
						<span class="service-tile__arrow" aria-hidden="true"><?php tristate_icon( 'arrow-up-right', 20 ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="services__also" data-reveal="up">
			<p class="services__also-title">Also at <br>Tristate</p>
			<ul class="services__chips" data-reveal-stagger>
				<?php foreach ( $tristate_specialties as $tristate_specialty => $tristate_slug ) : ?>
					<li>
						<a class="chip" href="<?php echo esc_url( home_url( "/$tristate_slug/" ) ); ?>">
							<?php tristate_icon( 'dot', 6 ); ?>
							<?php echo esc_html( $tristate_specialty ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
