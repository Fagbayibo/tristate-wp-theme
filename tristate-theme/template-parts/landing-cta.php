<?php
/**
 * Closing call to action for the designed pages: a marquee of service names
 * over a card with the booking buttons and the 24/7 emergency line.
 *
 * Args: words (marquee, HTML-escaped), eyebrow, title (may hold <em>), text,
 * secondary [ label, url ].
 *
 * @package tristate
 */

$tristate_cta = wp_parse_args( $args, array(
	'words'     => array(),
	'eyebrow'   => '',
	'title'     => '',
	'text'      => '',
	'secondary' => array(),
) );
?>
<section class="svc-cta" aria-label="Get started">
	<?php if ( $tristate_cta['words'] ) : ?>
		<div class="marquee" style="--speed: 50s">
			<?php for ( $tristate_copy = 0; $tristate_copy < 2; $tristate_copy++ ) : ?>
				<div class="marquee__track"<?php echo $tristate_copy ? ' aria-hidden="true"' : ''; ?>>
					<?php foreach ( $tristate_cta['words'] as $tristate_word ) : ?>
						<span class="svc-cta__word"><?php echo wp_kses_post( $tristate_word ); ?> <i aria-hidden="true"></i></span>
					<?php endforeach; ?>
				</div>
			<?php endfor; ?>
		</div>
	<?php endif; ?>

	<div class="container">
		<div class="svc-cta__card" data-reveal="up">
			<div class="svc-cta__main">
				<p class="eyebrow"><?php echo esc_html( $tristate_cta['eyebrow'] ); ?></p>
				<h2 class="section-title" style="--title-size: 40" data-split><?php echo wp_kses_post( $tristate_cta['title'] ); ?></h2>
				<p class="svc-cta__text"><?php echo esc_html( $tristate_cta['text'] ); ?></p>
				<div class="btn-row">
					<a class="btn btn--brand" href="<?php echo esc_url( tristate_contact( 'appointment_url' ) ); ?>">Book Appointment <span class="btn__arrow" aria-hidden="true">→</span></a>
					<?php if ( $tristate_cta['secondary'] ) : ?>
						<a class="btn btn--outline-brand" href="<?php echo esc_url( $tristate_cta['secondary'][1] ); ?>"><?php echo esc_html( $tristate_cta['secondary'][0] ); ?></a>
					<?php endif; ?>
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
