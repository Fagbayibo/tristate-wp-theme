<?php
/**
 * Category, tag, author and date archives.
 *
 * @package tristate
 */

get_header();

if ( is_category() ) {
	$tristate_eyebrow = __( 'Category', 'tristate' );
} elseif ( is_tag() ) {
	$tristate_eyebrow = __( 'Topic', 'tristate' );
} else {
	$tristate_eyebrow = __( 'Archive', 'tristate' );
}
$tristate_title = ( is_category() || is_tag() ) ? single_term_title( '', false ) : get_the_archive_title();
?>
<div class="landing landing--blog">
	<?php
	get_template_part( 'template-parts/blog-head', null, array(
		'eyebrow' => $tristate_eyebrow,
		'title'   => esc_html( $tristate_title ),
		'lead'    => wp_strip_all_tags( get_the_archive_description() ),
	) );

	get_template_part( 'template-parts/post-list', null, array( 'empty' => __( 'Nothing found here yet.', 'tristate' ) ) );

	?>
</div>
<?php
get_footer();
