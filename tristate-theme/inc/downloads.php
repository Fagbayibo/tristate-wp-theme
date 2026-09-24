<?php
/**
 * Downloadable brochures & flyers (Dashboard → Downloads), shown in the footer.
 *
 * Each download: title, cover image (Featured image), a file from the Media Library,
 * and a type. Drag order is controlled by the "Order" field (Page Attributes).
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

/** Types an editor can choose from. */
function tristate_download_types() {
	return array(
		'brochure' => __( 'Brochure', 'tristate' ),
		'flyer'    => __( 'Flyer', 'tristate' ),
		'guide'    => __( 'Patient Guide', 'tristate' ),
	);
}

add_action( 'init', function () {
	register_post_type( 'tristate_download', array(
		'labels'        => array(
			'name'               => __( 'Downloads', 'tristate' ),
			'singular_name'      => __( 'Download', 'tristate' ),
			'add_new_item'       => __( 'Add brochure or flyer', 'tristate' ),
			'edit_item'          => __( 'Edit download', 'tristate' ),
			'featured_image'     => __( 'Cover image', 'tristate' ),
			'set_featured_image' => __( 'Set cover image', 'tristate' ),
		),
		'public'        => false,
		'show_ui'       => true,
		'menu_icon'     => 'dashicons-download',
		'menu_position' => 26,
		'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
	) );
} );

/* Admin: file + type meta box ------------------------------------------------ */

add_action( 'add_meta_boxes_tristate_download', function () {
	add_meta_box( 'tristate_download_file', __( 'File', 'tristate' ), 'tristate_download_meta_box', 'tristate_download', 'normal', 'high' );
} );

function tristate_download_meta_box( $post ) {
	wp_nonce_field( 'tristate_download_save', 'tristate_download_nonce' );
	$file_id = (int) get_post_meta( $post->ID, '_download_file_id', true );
	$type    = get_post_meta( $post->ID, '_download_type', true );
	$file    = $file_id ? get_attached_file( $file_id ) : '';
	?>
	<p>
		<input type="hidden" id="tristate-download-file-id" name="tristate_download_file_id" value="<?php echo esc_attr( $file_id ); ?>">
		<button type="button" class="button" id="tristate-download-pick"><?php esc_html_e( 'Choose file', 'tristate' ); ?></button>
		<span id="tristate-download-file-name" style="margin-left:8px;"><?php echo $file ? esc_html( wp_basename( $file ) ) : esc_html__( 'No file selected', 'tristate' ); ?></span>
	</p>
	<p>
		<label for="tristate-download-type"><strong><?php esc_html_e( 'Type', 'tristate' ); ?></strong></label><br>
		<select id="tristate-download-type" name="tristate_download_type">
			<?php foreach ( tristate_download_types() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $type, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p class="description"><?php esc_html_e( 'Upload a PDF (or image). Set a cover image in the “Cover image” box — usually the front page of the brochure.', 'tristate' ); ?></p>
	<?php
}

add_action( 'save_post_tristate_download', function ( $post_id ) {
	if ( ! isset( $_POST['tristate_download_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['tristate_download_nonce'] ), 'tristate_download_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$file_id = isset( $_POST['tristate_download_file_id'] ) ? absint( $_POST['tristate_download_file_id'] ) : 0;
	update_post_meta( $post_id, '_download_file_id', $file_id );

	$type = isset( $_POST['tristate_download_type'] ) ? sanitize_key( $_POST['tristate_download_type'] ) : 'brochure';
	update_post_meta( $post_id, '_download_type', array_key_exists( $type, tristate_download_types() ) ? $type : 'brochure' );
} );

add_action( 'admin_enqueue_scripts', function ( $hook ) {
	$screen = get_current_screen();
	if ( ! $screen || 'tristate_download' !== $screen->post_type || ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	wp_enqueue_media();
	wp_add_inline_script( 'media-editor', "
		jQuery(function ($) {
			var frame;
			$('#tristate-download-pick').on('click', function (e) {
				e.preventDefault();
				frame = frame || wp.media({ title: 'Choose brochure or flyer', button: { text: 'Use this file' }, multiple: false });
				frame.off('select').on('select', function () {
					var file = frame.state().get('selection').first().toJSON();
					$('#tristate-download-file-id').val(file.id);
					$('#tristate-download-file-name').text(file.filename);
				});
				frame.open();
			});
		});
	" );
} );

/* Front end ---------------------------------------------------------------- */

/**
 * Published downloads that have a file attached, ready for templates.
 *
 * @return array[] { title, url, type, format, size, cover }
 */
function tristate_get_downloads( $limit = 4 ) {
	$posts = get_posts( array(
		'post_type'      => 'tristate_download',
		'posts_per_page' => $limit,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
		'no_found_rows'  => true,
	) );

	$types     = tristate_download_types();
	$downloads = array();

	foreach ( $posts as $post ) {
		$file_id = (int) get_post_meta( $post->ID, '_download_file_id', true );
		$path    = $file_id ? get_attached_file( $file_id ) : '';
		if ( ! $path || ! file_exists( $path ) ) {
			continue;
		}

		$type        = get_post_meta( $post->ID, '_download_type', true );
		$bytes       = filesize( $path );
		$downloads[] = array(
			'title'  => get_the_title( $post ),
			'url'    => wp_get_attachment_url( $file_id ),
			'type'   => isset( $types[ $type ] ) ? $types[ $type ] : $types['brochure'],
			'format' => strtoupper( pathinfo( $path, PATHINFO_EXTENSION ) ),
			'size'   => size_format( $bytes, $bytes < MB_IN_BYTES ? 0 : 1 ), // "840 KB", "2.4 MB"
			'cover'  => get_the_post_thumbnail_url( $post, 'medium' ),
		);
	}

	return $downloads;
}
