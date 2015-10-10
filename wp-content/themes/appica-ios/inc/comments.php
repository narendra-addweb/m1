<?php
/**
 * Actions and filters for comments list and comment form
 *
 * @author 8guild
 * @package Appica 2
 */

/**
 * Output an Appica 2 theme specific comment template.
 *
 * @author 8guild
 * @since 1.0.0
 * @see wp_list_comments()
 *
 * @param object $comment Comment to display.
 * @param array  $args    An array of arguments.
 * @param int    $depth   Depth of comment.
 */
function appica_comment( $comment, $args, $depth ) {
	// Open tag
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
	// Extra comment wrap class
	$extra_class = appica_get_class_set( array(
		$args['has_children'] ? 'parent' : '',
	) );
	?>
	<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $extra_class ); ?>>
	<div class="comment-meta">
		<div class="column">
			<div class="author vcard">
				<?php if ( 0 != $args['avatar_size'] ) : ?>
				<span class="ava"><?php echo get_avatar( $comment, $args['avatar_size'] ); ?></span>
				<?php endif; ?>
				<span><?php _e( 'by', 'appica' ); ?></span> <span class="fake-link"><?php comment_author(); ?></span>
			</div>
		</div>
		<div class="column text-right">
			<span><?php echo human_time_diff( get_comment_time('U'), current_time('timestamp') ), ' '; _e( 'ago', 'appica' ); ?></span>
			<?php
			comment_reply_link( array_merge( $args, array(
				'add_below' => 'comment',
				'depth'     => $depth,
				'max_depth' => $args['max_depth'],
				'before'    => '',
				'after'     => ''
			) ) );
			?>
		</div>
	</div>

	<?php if ( '0' == $comment->comment_approved ) : ?>
		<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'appica' ); ?></p>
	<?php endif; ?>

	<?php comment_text();
}

/**
 * Appica theme specific comment ending.
 *
 * @see Walker::end_el()
 * @see wp_list_comments()
 *
 * @param object $comment The comment object. Default current comment.
 * @param array  $args    An array of arguments.
 * @param int    $depth   Depth of comment.
 */
function appica_comment_end( $comment, $args, $depth ) {
	echo '</div>'; // close opening div#comment-%d
}

/**
 * Filter the comment reply link: add extra class .reply-btn
 *
 * @author 8guild
 * @since 1.0.0
 *
 * @param string $link Reply link markup
 *
 * @return string
 */
function appica_reply_link_class( $link ) {
	$link = str_replace( "class='comment-reply-link", "class='comment-reply-link reply-btn", $link );

	return $link;
}

add_filter( 'comment_reply_link', 'appica_reply_link_class' );

/**
 * Wrap comment form fields (author, email) with div.row
 *
 * @see comment_form()
 *
 * @author 8guild
 * @since 1.0.0
 */
function appica_comment_form_before_fields() {
	echo '<div class="row">';
}

add_action( 'comment_form_before_fields', 'appica_comment_form_before_fields' );

/**
 * Filter the default comment form fields, such as 'author', 'email', 'url'
 *
 * @param array $fields Default fields
 *
 * @return array
 */
function appica_comment_form_default_fields( $fields ) {
	// Remove URL field
	unset( $fields['url'] );

	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? "aria-required='true' required" : '' );

	$author = '<div class="col-sm-6"><div class="form-group"><label for="cf_name" class="sr-only">%1$s</label>'
	          . '<input type="text" class="form-control" name="author" id="cf_name" value="%2$s" placeholder="%1$s" %3$s>'
	          . '<span class="error-label"></span><span class="valid-label"></span></div></div>';

	$email = '<div class="col-sm-6"><div class="form-group"><label for="cf_email" class="sr-only">%1$s</label>'
	         . '<input type="email" class="form-control" name="email" id="cf_email" value="%2$s" placeholder="%1$s" aria-describedby="email-notes" %3$s>'
	         . '<span class="error-label"></span><span class="valid-label"></span></div></div>';

	$fields = array(
		'author' => sprintf( $author, __( 'Name', 'appica' ), esc_attr( $commenter['comment_author'] ), $aria_req ),
		'email'  => sprintf( $email, __( 'Email', 'appica' ), esc_attr( $commenter['comment_author_email'] ), $aria_req ),
	);

	return $fields;
}

add_filter( 'comment_form_default_fields', 'appica_comment_form_default_fields' );

/**
 * Close div.row wrapper after comment form fields (author, email)
 *
 * @see comment_form()
 *
 * @author 8guild
 * @since 1.0.0
 */
function appica_comment_form_after_fields() {
	echo '</div>'; // close div.row
}

add_action( 'comment_form_after_fields', 'appica_comment_form_after_fields' );

/**
 * Filter the comment form default arguments.
 *
 * @param array $args The default comment form arguments.
 *
 * @return array
 */
function appica_comment_form_defaults( $args ) {
	// Remove comment notes before and after
	$args['comment_notes_before'] = $args['comment_notes_after'] = '';

	$comment_field = '<div class="form-group">'
	                 . '<label for="cf_comment" class="sr-only">%1$s</label>'
	                 . '<textarea name="comment" id="cf_comment" class="form-control" rows="7" placeholder="%2$s" aria-required="true" required></textarea>'
	                 . '<span class="error-label"></span><span class="valid-label"></span></div>';

	$_args = array(
		'title_reply'   => sprintf( '<h3 class="text-gray text-right">%s</h3>', _x( 'Leave a comment', 'comments form title', 'appica' ) ),
		'id_form'       => 'comment-form',
		'class_submit'  => 'btn btn-ghost btn-sm btn-primary',
		'label_submit'  => _x( 'Comment', 'comment form submit', 'appica' ),
		'comment_field' => sprintf( $comment_field, _x( 'Comment', 'noun', 'appica' ), __( 'Enter your comment', 'appica' ) ),
	);

	return array_merge( $args, $_args );
}

add_filter( 'comment_form_defaults', 'appica_comment_form_defaults' );

/**
 * Add new hidden field with comments list order. Required for AJAX.
 *
 * @since    1.0.0
 *
 * @param string $fields    The HTML-formatted hidden id field comment elements.
 * @param int    $post_id   The post ID.
 * @param int    $replytoid The id of the comment being replied to.
 *
 * @return string
 */
function appica_comment_form_hidden_fields( $fields, $post_id, $replytoid ) {
	$order = get_option( 'comment_order' );
	$fields .= "<input type='hidden' name='comments_order' id='comments_order' value='{$order}' />\n";

	return $fields;
}

add_filter( 'comment_id_fields', 'appica_comment_form_hidden_fields', 10, 3 );

/**
 * Comment form AJAX callback
 *
 * @param int $comment_ID     Comment ID
 * @param int $comment_status Comment status: 0 - not approved, 1 - approved
 */
function appica_ajax_comment_callback( $comment_ID, $comment_status ) {
	if ( empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) || 'xmlhttprequest' !== strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) {
		die;
	}

	// Get the comment data
	$comment = get_comment( $comment_ID );
	// Allow the email to the author to be sent
	wp_notify_postauthor( $comment_ID );

	if ( 0 == $comment->comment_approved ) {
		/**
		 * Remove comment content, if not approved.
		 * @see /appica/comments.php
		 */
		$comment->comment_content = '';
	}

	$depth = appica_comments_nesting_level();
	$args = array(
		'style'        => 'div',
		'callback'     => 'appica_comment',
		'end-callback' => 'appica_comment_end',
		'max_depth'    => $depth,
		'per_page'     => -1,
		'type'         => 'comment',
		'reply_text'   => __( 'Reply', 'appica' ),
		'avatar_size'  => 48,
		'short_ping'   => true,
		'echo'         => false,
	);

	$comments[0] = $comment;
	$response = wp_list_comments( $args, $comments );

	/*
	 * As we pass only one comment to wp_list_comments(), function returns html with .depth-1 class.
	 * For top-level comments it is not a problem, but for replies (Appica 2 supports 2-level nested comments)
	 * class have to be .depth-2 or higher
	 *
	 * Remove the Reply link, too!
	 */
	if ( ! empty( $depth ) && (int) $comment->comment_parent > 0 ) {
		$response = str_replace( 'depth-1', "depth-{$depth}", $response );
		$response = preg_replace( '/<a class=[\'|"]comment-reply-link \\b[^>]*>.*?<\\/a>/iu', '', $response, 1 );
	}

	// Kill the script, returning the comment HTML
	wp_send_json_success( array( 'comment_id' => $comment_ID, 'comment' => $response ) );
}

add_action( 'comment_post', 'appica_ajax_comment_callback', 20, 2 );