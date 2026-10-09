<?php
/**
 * Line icons shared by the Our Services template and the primary menu's mega
 * dropdown, plus the filter that gives each dropdown link its service icon.
 *
 * @package tristate
 */

/** Inline 24×24 line icon, stroked with currentColor. */
function tristate_line_icon( $name, $width = '1.7' ) {
	static $icons = array(
		'flask'   => '<path d="M9 3h6M10 3v6l-5.6 9.6A2 2 0 0 0 6.1 21.5h11.8a2 2 0 0 0 1.7-2.9L14 9V3"/><path d="M7.2 15h9.6"/>',
		'venus'   => '<circle cx="12" cy="9" r="5"/><path d="M12 14v7M9 18h6"/>',
		'capsule' => '<path d="M10.5 20.5 3.5 13.5a5 5 0 0 1 7-7l7 7a5 5 0 0 1-7 7Z"/><path d="m8.5 8.5 7 7"/>',
		'monitor' => '<rect x="3" y="4" width="18" height="13" rx="2"/><path d="M6 11h3l2-3 2 6 2-3h3M8 21h8M12 17v4"/>',
		'stetho'  => '<path d="M6 3v5a4 4 0 0 0 8 0V3"/><path d="M10 12v2.5a5 5 0 0 0 10 0V13"/><circle cx="20" cy="11" r="2"/>',
		'cap'     => '<path d="m2 9 10-5 10 5-10 5-10-5Z"/><path d="M6 11v5c3 2 9 2 12 0v-5M22 9v6"/>',
		'cross'   => '<path d="M10 3h4v7h7v4h-7v7h-4v-7H3v-4h7Z"/>',
		'doctor'  => '<circle cx="12" cy="7" r="4"/><path d="M4 21v-1a7 7 0 0 1 14 0v1"/><path d="M17 4.5 19 3M19.5 8H22"/>',
		'pathway' => '<circle cx="5" cy="6" r="2.5"/><circle cx="19" cy="18" r="2.5"/><path d="M7.5 6H15a3.5 3.5 0 0 1 0 7H9a3.5 3.5 0 0 0 0 7h7.5"/>',
		'heart'   => '<path d="M12 20.5s-8-4.6-8-10.3A4.4 4.4 0 0 1 12 7.6a4.4 4.4 0 0 1 8 2.6c0 5.7-8 10.3-8 10.3Z"/><path d="M7.5 12h2.2l1.4-2.4 1.8 4.4 1.4-2h2.2"/>',
		'drop'    => '<path d="M12 3.5s6 6.4 6 10.6a6 6 0 0 1-12 0C6 9.9 12 3.5 12 3.5Z"/><path d="M9.5 14.5a2.5 2.5 0 0 0 2.5 2.5"/>',
		'lungs'   => '<path d="M12 4v8M12 12l-2.5 2M12 12l2.5 2"/><path d="M8.5 7.5C6 8 4 11.5 4 16c0 2 1 3.5 3 3.5s2.5-1.5 2.5-3.5v-5"/><path d="M15.5 7.5C18 8 20 11.5 20 16c0 2-1 3.5-3 3.5s-2.5-1.5-2.5-3.5v-5"/>',
		'scan'    => '<path d="M4 8V5a1 1 0 0 1 1-1h3M16 4h3a1 1 0 0 1 1 1v3M20 16v3a1 1 0 0 1-1 1h-3M8 20H5a1 1 0 0 1-1-1v-3"/><circle cx="12" cy="12" r="3.5"/>',
		'plus'    => '<path d="M12 5v14M5 12h14"/>',
	);
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="' . esc_attr( $width ) . '" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ( $icons[ $name ] ?? $icons['plus'] ) . '</svg>';
}

/** Pick a service icon from a menu label ("Laboratory Services" → flask). */
function tristate_service_icon_name( $label ) {
	$map = array(
		'emergency'   => 'cross',
		'anaesth'     => 'monitor',
		'cardio'      => 'heart',
		'heart'       => 'heart',
		'education'   => 'cap',
		'research'    => 'cap',
		'laborator'   => 'flask',
		'dialysis'    => 'drop',
		'obstetric'   => 'venus',
		'gynae'       => 'venus',
		'outpatient'  => 'stetho',
		'pharma'      => 'capsule',
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
		return '<span class="sub-menu__icon">' . tristate_line_icon( tristate_service_icon_name( wp_strip_all_tags( $title ) ) ) . '</span><span class="sub-menu__label">' . $title . '</span>';
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

/** Side card in a mega dropdown: the 24/7 line, the booking button and "View all". */
function tristate_mega_feature( $parent_item ) {
	ob_start();
	?>
	<div class="mega-feature">
		<p class="mega-feature__label"><?php esc_html_e( '24/7 Emergency Line', 'tristate' ); ?></p>
		<a class="mega-feature__number" href="tel:<?php echo esc_attr( tristate_contact( 'emergency_tel' ) ); ?>"><?php echo esc_html( tristate_contact( 'emergency' ) ); ?></a>
		<p class="mega-feature__alt"><?php printf( esc_html__( 'or %s', 'tristate' ), esc_html( tristate_contact( 'phone_alt' ) ) ); ?></p>
		<a class="btn btn--light mega-feature__btn" href="<?php echo esc_url( tristate_contact( 'appointment_url' ) ); ?>"><?php esc_html_e( 'Book Appointment', 'tristate' ); ?></a>
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
