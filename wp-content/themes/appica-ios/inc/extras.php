<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Appica
 */

/**
 * Filter the meta boxes, shown by default for New Post screen
 *
 * @param array     $hidden An array of meta boxes hidden by default
 * @param WP_Screen $screen object of the current screen
 *
 * @return array
 */
function appica_default_hidden_meta_boxes( $hidden, $screen ) {
	// Filters only post screen and if "postexcerpt" present in $hidden
	$key = array_search( 'postexcerpt', $hidden );
	if ( 'post' == $screen->post_type && $key !== false ) {
		unset( $hidden[ $key ] );
	}

	return $hidden;
}

add_filter( 'default_hidden_meta_boxes', 'appica_default_hidden_meta_boxes', 10, 2 );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function appica_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}

add_filter( 'body_class', 'appica_body_classes' );

/**
 * Filter the post tags output
 *
 * @param array $tags Post tags
 *
 * @return string
 */
function appica_post_tags_filter( $tags ) {
	if ( empty( $tags ) ) {
		return $tags;
	}

	$_tags = array();
	foreach ( $tags as $tag ) {
		// Note, added # before tag name
		$_tags[] = sprintf( '<a href="%1$s" rel="tag">#%2$s</a>', get_term_link( $tag, $tag->taxonomy ), $tag->name );
	}
	unset( $tag );

	return implode( ', ', $_tags );
}

add_filter( 'appica_get_the_tags', 'appica_post_tags_filter' );

/**
 * Filter shortcodes param "iconpicker": add new icon pack "Flaticons"
 *
 * @since 1.0.0
 *
 * @param array $icons Icons
 *
 * @return array New icons
 */
function appica_vc_iconpicker_flaticons( $icons ) {
	$_flaticons = appica_get_icons();
	$flaticons  = array();

	foreach ( (array) $_flaticons as $n ) {
		$flaticons[] = array( $n => $n );
	}
	unset( $n, $_flaticons );

	return array_merge( $icons, $flaticons );
}

add_filter( 'vc_iconpicker-type-flaticons', 'appica_vc_iconpicker_flaticons' );

/**
 * Change excerpt "more" string to "...'
 *
 * @param string $excerpt Current excerpt line
 *
 * @since 1.0.0
 *
 * @return string
 */
function appica_excerpt_more( $excerpt ) {
	return str_replace( '[&hellip;]', '...', $excerpt );
}

add_filter( 'excerpt_more', 'appica_excerpt_more' );

if ( ! function_exists( 'appica_admin_scripts' ) ) :
	/**
	 * Enqueue scripts and styles on admin pages
	 *
	 * @since 1.0.0
	 */
	function appica_admin_scripts() {
		$template_directory_uri = get_template_directory_uri();

		wp_enqueue_style( 'appica-flaticons', $template_directory_uri . '/css/vendor/flaticon.css', array(), null );
		wp_enqueue_script( 'appica', $template_directory_uri . '/js/admin-custom.js', array( 'jquery' ), null, true );

		wp_localize_script( 'appica', 'appica', array(
			'nonce' => wp_create_nonce( 'appica-ajax' ),
			'icon'  => array(
				'preview' => '',
				'value'   => ''
			)
		) );
	}

	add_action( 'admin_enqueue_scripts', 'appica_admin_scripts' );

endif; // appica_admin_scripts

if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 *
	 * @return string The filtered title.
	 */
	function appica_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( __( 'Page %s', 'appica' ), max( $paged, $page ) );
		}

		return $title;
	}
	add_filter( 'wp_title', 'appica_wp_title', 10, 2 );

	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function appica_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}
	add_action( 'wp_head', 'appica_render_title' );

endif;

/**
 * Modify TinyMCE
 *
 * @param array $init_array
 *
 * @return mixed
 */
function appica_mce_before_init( $init_array ) {
	$style_formats = array(
		array(
			'title'   => 'Muted text',
			'inline'  => 'span',
			'classes' => 'text-muted'
		),
		array(
			'title'   => 'Gray text',
			'inline'  => 'span',
			'classes' => 'text-gray'
		),
		array(
			'title'   => 'Primary text',
			'inline'  => 'span',
			'classes' => 'text-primary'
		),
		array(
			'title'   => 'Success text',
			'inline'  => 'span',
			'classes' => 'text-success'
		),
		array(
			'title'   => 'Info text',
			'inline'  => 'span',
			'classes' => 'text-info'
		),
		array(
			'title'   => 'Warning text',
			'inline'  => 'span',
			'classes' => 'text-warning'
		),
		array(
			'title'   => 'Danger text',
			'inline'  => 'span',
			'classes' => 'text-danger'
		),
		array(
			'title'   => 'UPPERCASE text',
			'inline'  => 'span',
			'classes' => 'text-uppercase'
		),
		array(
			'title'   => 'Smaller text',
			'inline'  => 'span',
			'classes' => 'text-smaller'
		),
		array(
			'title'    => 'Lead text',
			'selector' => 'p',
			'classes'  => 'lead'
		),
		array(
			'title'    => 'Fancy Link',
			'selector' => 'a',
			'classes'  => 'link'
		)
	);

	$init_array['style_formats'] = json_encode( $style_formats );

	return $init_array;

}

add_filter( 'tiny_mce_before_init', 'appica_mce_before_init' );

/**
 * Add "styleselect" button to TinyMCE second row
 *
 * @param array $buttons TinyMCE Buttons
 *
 * @return mixed
 */
function appica_mce_buttons_2( $buttons ) {
	array_unshift( $buttons, 'styleselect' );

	return $buttons;
}

add_filter( 'mce_buttons_2', 'appica_mce_buttons_2' );

/**
 * Flush out the transients used in appica_categorized_blog.
 */
function appica_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'appica_categories' );
}

add_action( 'edit_category', 'appica_category_transient_flusher' );
add_action( 'save_post',     'appica_category_transient_flusher' );

/**
 * Some additional mime types
 *
 * @since 1.0.0
 *
 * @param array $mime_types
 *
 * @return array
 */
function appica_extended_mime_types( $mime_types ) {
	$extended = array(
		'ico' => 'image/vnd.microsoft.icon'
	);

	foreach ( $extended as $ext => $mime ) {
		$mime_types[ $ext ] = $mime;
	}

	return $mime_types;
}

add_filter( 'upload_mimes', 'appica_extended_mime_types' );