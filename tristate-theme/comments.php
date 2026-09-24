<?php
/**
 * Comments on blog posts.
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section class="comments" id="comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments__title">
			<?php
			$tristate_count = get_comments_number();
			printf(
				/* translators: %s: comment count */
				esc_html( _n( '%s comment', '%s comments', $tristate_count, 'tristate' ) ),
				esc_html( number_format_i18n( $tristate_count ) )
			);
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 48,
			) );
			?>
		</ol>

		<?php
		the_comments_pagination( array(
			'prev_text' => '&larr; ' . __( 'Older', 'tristate' ),
			'next_text' => __( 'Newer', 'tristate' ) . ' &rarr;',
		) );
		?>
	<?php endif; ?>

	<?php
	comment_form( array(
		'class_submit'  => 'btn btn--brand',
		'title_reply'   => __( 'Leave a comment', 'tristate' ),
		'comment_field' => sprintf(
			'<p class="comment-form-comment"><label for="comment">%s</label><textarea class="field__control" id="comment" name="comment" rows="6" required></textarea></p>',
			esc_html__( 'Comment', 'tristate' )
		),
	) );
	?>
</section>
