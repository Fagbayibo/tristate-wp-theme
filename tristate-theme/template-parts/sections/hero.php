<?php
/**
 * Home hero: the multispecialty message, the two ways in (book or browse), and
 * the two hospitals and emergency line, visible without opening anything.
 *
 * @package tristate
 */

/** Wrap each word in a mask so it can rise into view; [text, accent] segments. */
function tristate_hero_headline( $segments ) {
	$out = '';
	foreach ( $segments as $segment ) {
		list( $text, $accent ) = $segment;
		foreach ( preg_split( '/\s+/', trim( $text ) ) as $word ) {
			$out .= ( $out ? ' ' : '' ) . sprintf(
				'<span class="hero__word%s"><span>%s</span></span>',
				$accent ? ' is-accent' : '',
				esc_html( $word )
			);
		}
	}
	return $out;
}
?>
<section class="hero" data-hero aria-labelledby="hero-title">
	<div class="hero__media" aria-hidden="true">
		<img class="hero__image" src="<?php echo esc_url( TRISTATE_URI . '/assets/images/hero-home.jpg' ); ?>" alt="" width="1600" height="1067" fetchpriority="high" data-hero-image>
		<div class="hero__overlay"></div>
		<div class="hero__overlay-top"></div>
	</div>

	<div class="container hero__inner">
		<div class="hero__copy">
			<p class="eyebrow hero__eyebrow" data-hero-intro><?php esc_html_e( 'Multispecialty care in Lekki and Ibadan', 'tristate' ); ?></p>
			<h1 class="hero__title" id="hero-title"><?php echo tristate_hero_headline( array( array( 'Care starts with', false ), array( 'listening to you', true ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped per word. ?></h1>
			<p class="hero__text" data-hero-intro><?php esc_html_e( 'Tell us what is worrying you. Whether you need help with a new symptom, an ongoing condition or specialist treatment, Tristate brings medical and surgical care together around your needs.', 'tristate' ); ?></p>

			<div class="hero__buttons" data-hero-intro>
				<a class="btn btn--primary" href="#appointment"><?php esc_html_e( 'Request an Appointment', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span></a>
				<a class="btn btn--outline-light" href="<?php echo esc_url( tristate_page_url( 'our-services' ) ); ?>"><?php esc_html_e( 'Explore Our Services', 'tristate' ); ?></a>
			</div>

			<p class="hero__help" data-hero-intro>
				<?php esc_html_e( 'Not sure which doctor to see?', 'tristate' ); ?>
				<a href="<?php echo esc_url( add_query_arg( 'service', 'help', home_url( '/' ) ) . '#appointment' ); ?>"><?php esc_html_e( 'Our team can help you choose a clinic.', 'tristate' ); ?></a>
			</p>
		</div>

		<ul class="hero__facts" data-hero-intro>
			<?php foreach ( tristate_locations() as $tristate_place ) : ?>
				<li class="hero__fact">
					<span class="hero__fact-label"><?php echo esc_html( $tristate_place['area'] ); ?></span>
					<a class="hero__fact-value" href="<?php echo esc_url( tristate_page_url( 'our-hospitals' ) ); ?>"><?php echo esc_html( $tristate_place['name'] ); ?></a>
				</li>
			<?php endforeach; ?>
			<li class="hero__fact hero__fact--urgent">
				<span class="hero__fact-label"><span class="pulse-dot" aria-hidden="true"></span><?php esc_html_e( 'Emergency line', 'tristate' ); ?></span>
				<a class="hero__fact-value" href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>"><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a>
			</li>
		</ul>
	</div>
</section>
