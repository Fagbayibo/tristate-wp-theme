<?php
/**
 * Site footer: emergency CTA, brand + link columns, bottom bar.
 *
 * @package tristate
 */

$tristate_socials = array(
	'facebook'  => array( 'Facebook', 'https://web.facebook.com/Tristate-Healthcare-110376837308912', 18 ),
	'x'         => array( 'X', 'https://twitter.com/TristateHs', 18 ),
	'instagram' => array( 'Instagram', 'https://www.instagram.com/tristate_hs/', 18 ),
	'youtube'   => array( 'YouTube', 'https://www.youtube.com/channel/UCqVsmjdjxg9cSz5FDYG7yyw', 20 ),
	'linkedin'  => array( 'LinkedIn', 'https://www.linkedin.com/company/tristate-healthcare-system', 18 ),
);
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
			<a class="btn btn--primary" href="<?php echo esc_url( tristate_contact( 'appointment_url' ) ); ?>">
				<?php esc_html_e( 'Book Appointment', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span>
			</a>
		</div>

		<div class="footer-cols">
			<div class="footer-brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<img src="<?php echo esc_url( TRISTATE_URI . '/assets/images/logo-white.png' ); ?>" width="198" height="60" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				</a>
				<p><?php esc_html_e( 'TRISTATE HEALTHCARE SYSTEM is a conglomeration of world-class super-specialty healthcare providers born out of a compassionate move to provide patients with affordable, accessible, compassionate and exceptional care in a serene, learning and research environment.', 'tristate' ); ?></p>
			</div>

			<div class="footer-col">
				<h2 class="footer-col__title"><?php esc_html_e( 'Quick Links', 'tristate' ); ?></h2>
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'footer-links',
						'depth'          => 1,
					) );
				} else {
					?>
					<ul class="footer-links">
						<li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">About Us</a></li>
						<li><a href="<?php echo esc_url( home_url( '/our-services/' ) ); ?>">Services</a></li>
						<li><a href="<?php echo esc_url( tristate_contact( 'appointment_url' ) ); ?>">Appointments</a></li>
						<li><a href="<?php echo esc_url( get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/our-blog/' ) ); ?>">News</a></li>
					</ul>
					<?php
				}
				?>
			</div>

			<div class="footer-col">
				<h2 class="footer-col__title"><?php esc_html_e( 'Contact', 'tristate' ); ?></h2>
				<ul class="footer-links">
					<li><a href="tel:<?php echo esc_attr( tristate_contact( 'phone_alt' ) ); ?>"><?php echo esc_html( tristate_contact( 'phone_alt' ) ); ?></a></li>
					<li><a href="mailto:<?php echo esc_attr( tristate_contact( 'email' ) ); ?>"><?php echo esc_html( tristate_contact( 'email' ) ); ?></a></li>
				</ul>
			</div>

			<div class="footer-col">
				<h2 class="footer-col__title"><?php esc_html_e( 'Follow Us', 'tristate' ); ?></h2>
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
		</div>

		<div class="footer-bottom">
			<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php esc_html_e( 'TRISTATE HEALTHCARE SYSTEM. All rights reserved.', 'tristate' ); ?></p>
			<ul class="footer-legal">
				<li><a href="<?php echo esc_url( get_privacy_policy_url() ? get_privacy_policy_url() : home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'tristate' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/terms-of-use/' ) ); ?>"><?php esc_html_e( 'Terms of Use', 'tristate' ); ?></a></li>
			</ul>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
