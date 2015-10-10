<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package Appica2
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

?>
<hr>

<div class="space-top-3x comments-title" id="comments">
	<?php printf( '<h3 class="text-gray text-right" id="comments-count">%1$s (%2$s)</h3>', _x( 'Comments', 'comments title', 'appica' ), number_format_i18n( get_comments_number() ) ); ?>
</div>

<!-- Comments -->
<div class="space-top-2x space-bottom-2x" id="comments-list">
	<?php
	if ( have_comments() ) :

		wp_list_comments( array(
			'style'        => 'div',
			'callback'     => 'appica_comment',
			'end-callback' => 'appica_comment_end',
			'max_depth'    => appica_comments_nesting_level(),
			'per_page'     => -1,
			'type'         => 'comment',
			'reply_text'   => __( 'Reply', 'appica' ),
			'avatar_size'  => 48,
			'short_ping'   => true,
		) );

	endif; // have_comments()
	?>
</div>

<?php
// If comments are closed and there are comments, let's leave a little note, shall we?
if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
	<p class="no-comments"><?php _e( 'Comments are closed.', 'appica' ); ?></p>
<?php endif; ?>

<?php comment_form(); ?>