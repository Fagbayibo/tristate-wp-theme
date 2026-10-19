<?php
/**
 * Template Name: Our Services
 *
 * The Our Services page gets this design from its slug (see functions.php).
 * Areas of care and services come from inc/services.php.
 *
 * @package tristate
 */

$tristate_img      = TRISTATE_URI . '/assets/images/';
$tristate_arrow    = tristate_svg( 'arrow-up-right', array( 'stroke' => 2 ) );
$tristate_services = tristate_services();
$tristate_groups   = tristate_service_groups();
$tristate_total    = count( $tristate_groups );
$tristate_help_url = add_query_arg( 'service', 'help', home_url( '/' ) ) . '#appointment';

// Swatch colours per area, in order.
$tristate_swatches = array( array( '#8c101b', '#5e0d17' ), array( '#c2283a', '#8c101b' ), array( '#a3343f', '#5e0d17' ), array( '#e8c9cd', '#b86a75' ), array( '#d79aa3', '#8c101b' ), array( '#3a1a20', '#8c101b' ), array( '#5e0d17', '#2a0a10' ), array( '#b86a75', '#5e0d17' ) );

// Floating hero tiles: position, size, rotation, float timing, pointer depth, image or icon, phone top.
$tristate_tiles = array(
	array( 'left:6%;top:24%', 128, -8, '7s', '0s', 0.9, 'why-2.jpg', '', '72px' ),
	array( 'left:15%;top:62%', 96, 6, '8s', '-2s', 0.5, '', 'heartbeat', '' ),
	array( 'left:22%;top:13%', 78, 10, '6s', '-1s', 0.3, '', 'stethoscope blush', '8px' ),
	array( 'left:24%;top:80%', 92, -4, '9s', '-4s', 0.45, 'service-obgyn.jpg', '', '' ),
	array( 'right:6%;top:20%', 136, 8, '7.5s', '-3s', 0.85, 'service-imaging.jpg', '', '58px' ),
	array( 'right:20%;top:11%', 76, -10, '6.5s', '-2.5s', 0.35, '', 'brain blush', '6px' ),
	array( 'right:13%;top:62%', 120, -6, '8.5s', '-1.5s', 0.7, 'service-outpatient.jpg', '', '' ),
	array( 'right:29%;top:82%', 70, 12, '7s', '-5s', 0.4, '', 'bone', '' ),
);

// A–Z: entries grouped by first letter.
$tristate_az = array();
foreach ( tristate_az_entries() as $tristate_entry ) {
	$tristate_az[ strtoupper( mb_substr( $tristate_entry[0], 0, 1 ) ) ][] = $tristate_entry;
}

get_header();
?>
<div class="landing landing--services">

	<!-- Hero with floating tiles -------------------------------------------------- -->
	<section class="svc-hero" data-tiles>
		<div class="svc-hero__rings" aria-hidden="true"><span></span><span></span><span></span></div>

		<?php foreach ( $tristate_tiles as $tristate_i => $tristate_tile ) : ?>
			<?php
			list( $tristate_pos, $tristate_size, $tristate_rot, $tristate_dur, $tristate_delay, $tristate_depth, $tristate_photo, $tristate_glyph, $tristate_mobile_top ) = $tristate_tile;
			$tristate_glyph = explode( ' ', $tristate_glyph );
			$tristate_class = 'svc-tile' . ( $tristate_mobile_top ? '' : ' svc-tile--desktop' );
			$tristate_inner = 'svc-tile__in' . ( $tristate_photo ? '' : ' is-icon' ) . ( in_array( 'blush', $tristate_glyph, true ) ? ' is-blush' : '' );
			?>
			<div class="<?php echo esc_attr( $tristate_class ); ?>" style="<?php echo esc_attr( $tristate_pos . ( $tristate_mobile_top ? ';--mt:' . $tristate_mobile_top : '' ) ); ?>" data-depth="<?php echo esc_attr( $tristate_depth ); ?>" aria-hidden="true">
				<div class="svc-tile__float" style="<?php echo esc_attr( "--dur:$tristate_dur;--delay:$tristate_delay" ); ?>">
					<div class="<?php echo esc_attr( $tristate_inner ); ?>" style="<?php echo esc_attr( "--s:{$tristate_size}px;--r:{$tristate_rot}deg;--i:$tristate_i" ); ?>">
						<?php if ( $tristate_photo ) : ?>
							<img src="<?php echo esc_url( $tristate_img . $tristate_photo ); ?>" alt="">
						<?php else : ?>
							<?php echo tristate_svg( $tristate_glyph[0], array( 'stroke' => 1.5 ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>

		<div class="svc-hero__center">
			<p class="eyebrow eyebrow--center" data-reveal="up">Our Services</p>
			<h1 class="landing-title" data-split data-delay="0.3">Find care for <em>what matters to you</em></h1>
			<p class="landing-lead" data-reveal="up" data-delay="0.45">Choose an area of care below. If you are unsure where to begin, ask our team for guidance. Services and clinic schedules vary by hospital.</p>
			<div class="btn-row svc-hero__actions" data-reveal="up" data-delay="0.6">
				<a class="btn btn--brand" href="<?php echo esc_url( $tristate_help_url ); ?>">Help Me Find Care <span class="btn__arrow" aria-hidden="true">→</span></a>
				<a class="btn btn--outline-brand" href="#a-z">A–Z directory</a>
			</div>
		</div>

		<div class="svc-hero__cue" aria-hidden="true"><i></i>Scroll</div>
	</section>

	<!-- Areas of care at a glance --------------------------------------------------------- -->
	<section class="svc-index" id="services" aria-label="Areas of care">
		<div class="container">
			<div class="svc-index__grid" data-reveal-stagger>
				<?php foreach ( array_keys( $tristate_groups ) as $tristate_i => $tristate_key ) : ?>
					<?php
					$tristate_group = $tristate_groups[ $tristate_key ];
					$tristate_names = array();
					foreach ( $tristate_group['services'] as $tristate_slug ) {
						if ( $tristate_services[ $tristate_slug ]['title'] !== $tristate_group['label'] ) {
							$tristate_names[] = $tristate_services[ $tristate_slug ]['title'];
						}
					}
					$tristate_note = $tristate_names ? implode( ' · ', $tristate_names ) : ( 'virtual' === $tristate_key ? $tristate_group['summary'] : '' );
					?>
					<a class="svc-link" href="#<?php echo esc_attr( $tristate_key ); ?>">
						<span class="svc-link__swatch" style="<?php echo esc_attr( "--c1:{$tristate_swatches[$tristate_i][0]};--c2:{$tristate_swatches[$tristate_i][1]}" ); ?>"><?php echo tristate_svg( $tristate_group['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
						<span class="svc-link__text"><strong><?php echo esc_html( $tristate_group['label'] ); ?></strong><?php if ( $tristate_note ) : ?><small><?php echo esc_html( $tristate_note ); ?></small><?php endif; ?></span>
						<span class="svc-link__go"><?php echo $tristate_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Stacked area cards ----------------------------------------------------------------- -->
	<section class="svc-stack" aria-label="Areas of care in detail">
		<div class="container svc-stack__inner">
			<?php foreach ( array_keys( $tristate_groups ) as $tristate_i => $tristate_key ) : ?>
				<?php
				$tristate_group   = $tristate_groups[ $tristate_key ];
				$tristate_variant = array( '', ' svc-card--blush', ' svc-card--dark' )[ $tristate_i % 3 ];
				$tristate_list    = array_merge( $tristate_group['services'], isset( $tristate_group['also'] ) ? $tristate_group['also'] : array() );
				?>
				<article class="svc-card<?php echo esc_attr( $tristate_variant ); ?>" id="<?php echo esc_attr( $tristate_key ); ?>" style="--i: <?php echo (int) $tristate_i; ?>" data-observe>
					<div class="svc-card__inner">
						<div class="svc-card__body">
							<div class="svc-card__meta">
								<span class="svc-card__count"><?php echo esc_html( sprintf( '%02d', $tristate_i + 1 ) ); ?> <span>/ <?php echo esc_html( sprintf( '%02d', $tristate_total ) ); ?></span></span>
							</div>
							<span class="svc-card__icon"><?php echo tristate_svg( $tristate_group['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
							<h2 class="svc-card__title"><?php echo esc_html( $tristate_group['label'] ); ?></h2>
							<p class="svc-card__text"><?php echo esc_html( $tristate_group['summary'] ); ?></p>
							<ul class="svc-card__services">
								<?php foreach ( $tristate_list as $tristate_slug ) : ?>
									<li>
										<a class="svc-card__service" href="<?php echo esc_url( tristate_service_url( $tristate_slug ) ); ?>">
											<span><?php echo esc_html( $tristate_services[ $tristate_slug ]['title'] ); ?></span>
											<span class="svc-card__link-circle" aria-hidden="true"><?php echo $tristate_arrow; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
						<div class="svc-card__media">
							<img src="<?php echo esc_url( $tristate_img . $tristate_group['image'] ); ?>" alt="<?php echo esc_attr( $tristate_group['alt'] ); ?>" loading="lazy">
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>

	<!-- A–Z directory ------------------------------------------------------------------------ -->
	<section class="svc-az" id="a-z" aria-labelledby="az-title">
		<div class="container">
			<div class="landing-head">
				<div>
					<p class="eyebrow" data-reveal="up">A–Z</p>
					<h2 class="section-title" id="az-title" style="--title-size: 46" data-split>Services, tests and <em>procedures</em></h2>
				</div>
				<nav class="svc-az__letters" aria-label="Jump to a letter" data-reveal="up" data-delay="0.2">
					<?php foreach ( array_keys( $tristate_az ) as $tristate_letter ) : ?>
						<a href="#az-<?php echo esc_attr( strtolower( $tristate_letter ) ); ?>"><?php echo esc_html( $tristate_letter ); ?></a>
					<?php endforeach; ?>
				</nav>
			</div>

			<div class="svc-az__grid">
				<?php foreach ( $tristate_az as $tristate_letter => $tristate_entries ) : ?>
					<div class="svc-az__group" id="az-<?php echo esc_attr( strtolower( $tristate_letter ) ); ?>">
						<p class="svc-az__letter" aria-hidden="true"><?php echo esc_html( $tristate_letter ); ?></p>
						<ul class="svc-az__list">
							<?php foreach ( $tristate_entries as $tristate_entry ) : ?>
								<li><a href="<?php echo esc_url( $tristate_entry[1] ); ?>"><?php echo esc_html( $tristate_entry[0] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="svc-az__help" data-reveal="up">
				<p>If you are unsure where to begin, ask our team for guidance.</p>
				<a class="btn btn--brand" href="<?php echo esc_url( $tristate_help_url ); ?>">Help Me Find Care <span class="btn__arrow" aria-hidden="true">→</span></a>
			</div>
		</div>
	</section>

</div>
<?php
get_footer();
