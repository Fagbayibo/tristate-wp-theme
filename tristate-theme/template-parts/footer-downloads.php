<?php
/**
 * Footer: brochures & flyers download band. Hidden when there are no downloads.
 *
 * @package tristate
 */

$tristate_downloads = tristate_get_downloads( 4 );
if ( ! $tristate_downloads ) {
	return;
}
?>
<div class="footer-downloads" id="downloads">
	<div class="footer-downloads__intro" data-reveal-stagger>
		<p class="section-label"><?php esc_html_e( 'Resources', 'tristate' ); ?></p>
		<h2 class="section-title" style="--title-size: 36">Brochures &amp; <em>flyers</em>.</h2>
		<p class="footer-downloads__text"><?php esc_html_e( 'Service guides and patient information, free to download, print and share.', 'tristate' ); ?></p>
	</div>

	<ul class="footer-downloads__list" data-reveal-stagger>
		<?php foreach ( $tristate_downloads as $tristate_download ) : ?>
			<li>
				<a class="download-card" href="<?php echo esc_url( $tristate_download['url'] ); ?>" download target="_blank" rel="noopener">
					<span class="download-card__cover" aria-hidden="true">
						<?php if ( $tristate_download['cover'] ) : ?>
							<img src="<?php echo esc_url( $tristate_download['cover'] ); ?>" alt="" loading="lazy">
						<?php endif; ?>
						<span class="download-card__format"><?php echo esc_html( $tristate_download['format'] ); ?></span>
					</span>
					<span class="download-card__body">
						<span class="download-card__type"><?php echo esc_html( $tristate_download['type'] ); ?></span>
						<span class="download-card__title"><?php echo esc_html( $tristate_download['title'] ); ?></span>
						<span class="download-card__meta"><?php echo esc_html( $tristate_download['format'] . ' · ' . $tristate_download['size'] ); ?></span>
					</span>
					<span class="download-card__button" aria-hidden="true"><span>↓</span></span>
					<span class="screen-reader-text"><?php esc_html_e( '(download)', 'tristate' ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
