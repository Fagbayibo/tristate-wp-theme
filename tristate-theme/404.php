<?php
/**
 * Page not found.
 *
 * @package tristate
 */

get_header();

get_template_part( 'template-parts/page-hero', null, array(
	'title'   => __( 'Page not found.', 'tristate' ),
	'eyebrow' => __( 'Error 404', 'tristate' ),
	'meta'    => __( 'The page you are looking for may have moved or no longer exists.', 'tristate' ),
) );
?>

<div class="container page-content page-content--narrow">
	<div class="not-found">
		<div class="search-panel">
			<?php get_search_form(); ?>
		</div>

		<div class="not-found__links">
			<a class="btn btn--brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php esc_html_e( 'Back to homepage', 'tristate' ); ?> <span class="btn__arrow" aria-hidden="true">→</span>
			</a>
			<a class="btn btn--outline-brand" href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>">
				<?php printf( esc_html__( 'Call %s', 'tristate' ), esc_html( tristate_contact( 'emergency' ) ) ); ?>
			</a>
		</div>
	</div>
</div>

<?php
get_footer();
