<?php
/**
 * The filter that gives each primary-menu dropdown link its service icon
 * (Tabler icons, see inc/icons.php).
 *
 * @package tristate
 */

/** Pick a service icon from a menu label ("Laboratory Services" → flask). */
function tristate_service_icon_name( $label ) {
	$map = array(
		'emergency'   => 'ambulance',
		'anaesth'     => 'heart-rate-monitor',
		'cardio'      => 'heartbeat',
		'heart'       => 'heartbeat',
		'education'   => 'school',
		'research'    => 'school',
		'laborator'   => 'flask',
		'dialysis'    => 'droplet',
		'obstetric'   => 'gender-female',
		'gynae'       => 'gender-female',
		'outpatient'  => 'stethoscope',
		'pharma'      => 'pill',
		'respirat'    => 'lungs',
		'radiolog'    => 'scan',
		'imaging'     => 'scan',
	);
	$label = strtolower( $label );
	foreach ( $map as $needle => $icon ) {
		if ( false !== strpos( $label, $needle ) ) {
			return $icon;
		}
	}
	return 'plus';
}

/**
 * Primary menu dropdown links get an icon tile before the label, which the mega
 * dropdown (desktop) and the drawer accordion (mobile) both use.
 */
add_filter(
	'nav_menu_item_title',
	function ( $title, $item, $args, $depth ) {
		if ( 1 !== $depth || empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
			return $title;
		}
		return '<span class="sub-menu__icon">' . tristate_svg( tristate_service_icon_name( wp_strip_all_tags( $title ) ) ) . '</span><span class="sub-menu__label">' . $title . '</span>';
	},
	10,
	4
);

/**
 * Primary menu walker: wraps each top-level dropdown in a `.mega` panel with a
 * side card (emergency line + "View all" link), and adds the button that opens
 * it as an accordion in the mobile drawer. header.js morphs one shared white
 * backdrop between panels on desktop (the "Stripe" dropdown).
 */
class Tristate_Primary_Walker extends Walker_Nav_Menu {

	/** Top-level item whose dropdown is being written. */
	private $parent_item = null;

	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		if ( 0 === $depth ) {
			$this->parent_item = $data_object;
		}
		parent::start_el( $output, $data_object, $depth, $args, $current_object_id );
	}

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 !== $depth || ! $this->parent_item ) {
			parent::start_lvl( $output, $depth, $args );
			return;
		}
		$id     = 'mega-' . (int) $this->parent_item->ID;
		$title  = wp_strip_all_tags( $this->parent_item->title );
		$output .= sprintf(
			'<button class="submenu-toggle" type="button" aria-expanded="false" aria-controls="%1$s"><span class="screen-reader-text">%2$s</span></button>',
			esc_attr( $id ),
			/* translators: %s: menu item label, e.g. "Our Services" */
			esc_html( sprintf( __( 'Show %s links', 'tristate' ), $title ) )
		);
		$output .= '<div class="mega" id="' . esc_attr( $id ) . '" data-mega><div class="mega__inner"><ul class="sub-menu">';
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 !== $depth || ! $this->parent_item ) {
			parent::end_lvl( $output, $depth, $args );
			return;
		}
		$output .= '</ul>' . tristate_mega_feature( $this->parent_item ) . '</div></div>';
	}
}

/** Side card in a mega dropdown: the 24/7 line and "View all". */
function tristate_mega_feature( $parent_item ) {
	ob_start();
	?>
	<div class="mega-feature">
		<p class="mega-feature__label"><?php esc_html_e( '24/7 Emergency Line', 'tristate' ); ?></p>
		<a class="mega-feature__number" href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>"><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a>
		<a class="mega-feature__all" href="<?php echo esc_url( $parent_item->url ); ?>">
			<?php
			/* translators: %s: menu item label, e.g. "Our Services" */
			printf( esc_html__( 'View all %s', 'tristate' ), esc_html( wp_strip_all_tags( $parent_item->title ) ) );
			?>
			<span aria-hidden="true">→</span>
		</a>
	</div>
	<?php
	return ob_get_clean();
}
