<?php
/**
 * Stats band — Figma 25:7.
 *
 * @package tristate
 */

$tristate_stats = array(
	array( 'value' => 400, 'suffix' => '+', 'label' => 'Open<br>Heart Surgeries' ),
	array( 'value' => 25, 'suffix' => '+', 'label' => 'Years Combined Experience' ),
	array( 'value' => 3, 'suffix' => '', 'label' => 'Locations' ),
	array( 'value' => 5000, 'suffix' => '+', 'label' => 'Success Stories' ),
);
?>
<section class="stats" aria-label="<?php esc_attr_e( 'Tristate in numbers', 'tristate' ); ?>">
	<div class="container">
		<ul class="stats__list" data-reveal-stagger>
			<?php foreach ( $tristate_stats as $tristate_stat ) : ?>
				<li class="stat">
					<span class="stat__value" data-count="<?php echo (int) $tristate_stat['value']; ?>" data-suffix="<?php echo esc_attr( $tristate_stat['suffix'] ); ?>"><?php echo esc_html( number_format( $tristate_stat['value'] ) . $tristate_stat['suffix'] ); ?></span>
					<span class="stat__label"><?php echo wp_kses( $tristate_stat['label'], array( 'br' => array() ) ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
