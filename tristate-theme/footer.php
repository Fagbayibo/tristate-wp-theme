<?php
/**
 * Site footer: downloads band, emergency line, brand + link columns, bottom bar.
 *
 * @package tristate
 */

$tristate_socials = tristate_socials();
$tristate_blog    = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/our-blog/' );
?>
</main>

<footer class="site-footer">
	<div class="container">
		<?php get_template_part( 'template-parts/footer-downloads' ); ?>

		<div class="footer-cta">
			<div class="footer-cta__line">
				<p class="footer-cta__label"><?php esc_html_e( 'In an emergency, call', 'tristate' ); ?></p>
				<a class="footer-cta__number" href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>"><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a>
			</div>
			<p class="footer-cta__text"><?php esc_html_e( 'For a medical emergency, seek immediate care at the nearest emergency department. Do not wait for an online appointment or message reply.', 'tristate' ); ?></p>
		</div>

		<div class="footer-cols">
			<div class="footer-brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<img src="<?php echo esc_url( TRISTATE_URI . '/assets/images/logo-white.png' ); ?>" width="198" height="60" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				</a>
				<p><?php esc_html_e( 'Tristate Hospitals is part of Tristate Healthcare System, with hospitals in Lekki and Ibadan. Advanced cardiovascular care is our signature programme, alongside a wider range of medical and surgical specialties.', 'tristate' ); ?></p>
				<ul class="footer-socials">
					<?php foreach ( $tristate_socials as $tristate_slug => $tristate_social ) : ?>
						<li>
							<a href="<?php echo esc_url( $tristate_social[1] ); ?>" aria-label="<?php echo esc_attr( $tristate_social[0] ); ?>" target="_blank" rel="noopener">
								<?php tristate_icon( $tristate_slug, $tristate_social[2] ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="footer-col">
				<h2 class="footer-col__title"><?php esc_html_e( 'Our Services', 'tristate' ); ?></h2>
				<ul class="footer-links">
					<?php foreach ( tristate_service_groups() as $tristate_key => $tristate_group ) : ?>
						<li><a href="<?php echo esc_url( tristate_page_url( 'our-services', $tristate_key ) ); ?>"><?php echo esc_html( $tristate_group['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="footer-col">
				<h2 class="footer-col__title"><?php esc_html_e( 'Patients', 'tristate' ); ?></h2>
				<ul class="footer-links">
					<li><a href="<?php echo esc_url( tristate_contact( 'appointment_url' ) ); ?>"><?php esc_html_e( 'Request an Appointment', 'tristate' ); ?></a></li>
					<?php foreach ( tristate_nav_items() as $tristate_label => $tristate_slug ) : ?>
						<?php
						if ( 'our-services' === $tristate_slug ) {
							continue;
						}
						?>
						<li><a href="<?php echo esc_url( tristate_page_url( $tristate_slug ) ); ?>"><?php echo esc_html( $tristate_label ); ?></a></li>
					<?php endforeach; ?>
					<li><a href="<?php echo esc_url( $tristate_blog ); ?>"><?php esc_html_e( 'News', 'tristate' ); ?></a></li>
				</ul>
			</div>

			<div class="footer-col footer-col--hospitals">
				<h2 class="footer-col__title"><?php esc_html_e( 'Our Hospitals', 'tristate' ); ?></h2>
				<ul class="footer-places">
					<?php foreach ( tristate_locations() as $tristate_place ) : ?>
						<li>
							<strong><?php echo esc_html( $tristate_place['name'] ); ?></strong>
							<span><?php echo esc_html( $tristate_place['address'] ); ?></span>
						</li>
					<?php endforeach; ?>
					<li>
						<strong><?php esc_html_e( 'General enquiries', 'tristate' ); ?></strong>
						<a href="mailto:<?php echo esc_attr( tristate_contact( 'email' ) ); ?>"><?php echo esc_html( tristate_contact( 'email' ) ); ?></a>
					</li>
				</ul>
			</div>
		</div>

		<div class="footer-bottom">
			<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php esc_html_e( 'Tristate Healthcare System. All rights reserved.', 'tristate' ); ?></p>
			<ul class="footer-legal">
				<li><a href="<?php echo esc_url( get_privacy_policy_url() ? get_privacy_policy_url() : home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Notice', 'tristate' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/terms-of-use/' ) ); ?>"><?php esc_html_e( 'Terms of Use', 'tristate' ); ?></a></li>
			</ul>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
