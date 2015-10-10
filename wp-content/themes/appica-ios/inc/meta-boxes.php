<?php
/**
 * Custom theme meta boxes
 *
 * @author 8guild
 * @package Appica
 */

/**
 * Add theme meta boxes.
 *
 * @since 1.0.0
 *
 * @param string $post_type Post Type
 */
function appica_meta_boxes( $post_type ) {
	// Allowed post types
	$screens = array( 'post', 'page' );
	if ( ! in_array( $post_type, $screens, true ) ) {
		return;
	}

	add_meta_box( 'appica-featured-video', __( 'Featured Video', 'appica' ), 'appica_render_video_meta_box', 'post', 'side', 'default' );
	add_meta_box( 'appica-post-settings', __( 'Post Settings', 'appica' ), 'appica_render_post_settings_meta_box', 'post', 'normal', 'high' );
	add_meta_box( 'appica-page-settings', __( 'Page Settings', 'appica' ), 'appica_render_page_settings_meta_box', 'page', 'normal', 'high' );
}

add_action( 'add_meta_boxes', 'appica_meta_boxes' );

/**
 * Render "Featured Video" meta box
 *
 * @since 1.0.0
 *
 * @param WP_Post $post Post object
 */
function appica_render_video_meta_box( $post ) {
	wp_nonce_field( 'appica_mb_nonce', 'appica_mb_nonce_field' );

	$meta_box_name = '_appica_featured_video';
	$meta_box_value = get_post_meta( $post->ID, $meta_box_name, true );

	$video = '';
	if ( ! empty( $meta_box_value ) ) {
		$video = wp_oembed_get( $meta_box_value );
	}

	printf( '<div class="appica-video-holder">%1$s</div>', $video );
	printf(
		'<input type="text" class="%2$s widefat" id="appica-featured-video" name="%2$s" value="%1$s" placeholder="%3$s">',
		esc_url_raw( $meta_box_value ), $meta_box_name, __( 'Video URL', 'appica' )
	);
}

/**
 * Render "Page Settings" meta box.
 *
 * @since 1.0.0
 *
 * @param WP_Post $post Post object
 */
function appica_render_page_settings_meta_box( $post ) {
	wp_nonce_field( 'appica_mb_nonce', 'appica_mb_nonce_field' );

	$defaults = array(
		'is_title' => 1,
		'layout'   => 'boxed'
	);

	$meta_box_name  = '_appica_page_settings';
	$meta_box_value = get_post_meta( $post->ID, $meta_box_name, true );
	$meta_box_value = wp_parse_args( $meta_box_value, $defaults );

	$is_title = (int) $meta_box_value['is_title'];
	$layout   = esc_html( $meta_box_value['layout'] );
	?>
	<div class="appica-single-settings">
		<label for="appica-page-layout"><?php _e( 'Choose the layout type', 'appica' ); ?></label>
		<select name="<?php echo $meta_box_name; ?>[layout]" id="appica-page-layout" class="widefat">
			<option value="boxed" <?php selected( 'boxed', $layout ); ?>><?php _e( 'Boxed', 'appica' ); ?></option>
			<option value="fluid" <?php selected( 'fluid', $layout ); ?>><?php _e( 'Fluid', 'appica' ); ?></option>
		</select>

		<label for="appica-page-title"><?php _e( 'Enable/Disable Page Title', 'appica' ); ?></label>
		<select name="<?php echo $meta_box_name; ?>[is_title]" id="appica-page-title" class="widefat">
			<option value="1" <?php selected( 1, $is_title ); ?>><?php _e( 'Enable', 'appica' ); ?></option>
			<option value="0" <?php selected( 0, $is_title ); ?>><?php _e( 'Disable', 'appica' ); ?></option>
		</select>
	</div><?php
}

/**
 * Render "Post Settings" meta box
 *
 * @since 1.0.0
 *
 * @param WP_Post $post Post object
 */
function appica_render_post_settings_meta_box( $post ) {
	wp_nonce_field( 'appica_mb_nonce', 'appica_mb_nonce_field' );

	$defaults = array(
		'sidebar'      => 'left',
		'search'       => 1,
		'is_wide'      => 0,
		'custom_title' => '',
		'overlay'      => 'primary'
	);

	$name = '_appica_post_settings';
	$settings = get_post_meta( $post->ID, $name, true );
	$settings = wp_parse_args( $settings, $defaults );

	$sidebar      = esc_attr( $settings['sidebar'] );
	$is_search    = (int) $settings['search'];
	$is_wide      = (int) $settings['is_wide'];
	$custom_title = esc_attr( $settings['custom_title'] );
	$overlay      = esc_attr( $settings['overlay'] );

	$_overlays = array(
		'primary' => 'Primary',
		'success' => 'Success',
		'info'    => 'Info',
		'warning' => 'Warning',
		'danger'  => 'Danger'
	);
	?>
	<div class="appica-single-settings">
		<label for="appica-post-sidebar-position"><?php _e( 'Sidebar Position', 'appica' ); ?></label>
		<select name="<?php echo $name; ?>[sidebar]" id="appica-post-sidebar-position" class="widefat">
			<option value="left" <?php selected( 'left', $sidebar ); ?>><?php _e( 'Left', 'appica' ); ?></option>
			<option value="right" <?php selected( 'right', $sidebar ); ?>><?php _e( 'Right', 'appica' ); ?></option>
			<option value="none" <?php selected( 'none', $sidebar ); ?>><?php _e( 'No sidebar', 'appica' ); ?></option>
		</select>
		<p class="description"><?php _e( 'Choose sidebar position, or disable it', 'appica' ); ?></p>

		<label for="appica-post-search"><?php _e( 'Search On/Off', 'appica' ); ?></label>
		<select name="<?php echo $name; ?>[search]" id="appica-post-search" class="widefat">
			<option value="1" <?php selected( 1, $is_search ); ?>><?php _e( 'On', 'appica' ); ?></option>
			<option value="0" <?php selected( 0, $is_search ); ?>><?php _e( 'Off', 'appica' ); ?></option>
		</select>

		<label for="appica-post-wide"><?php _e( 'Select Wide or Normal post', 'appica' ); ?></label>
		<select name="<?php echo $name; ?>[is_wide]" id="appica-post-wide" class="widefat">
			<option value="0" <?php selected( 0, $is_wide ); ?>><?php _e( 'Normal', 'appica' ); ?></option>
			<option value="1" <?php selected( 1, $is_wide ); ?>><?php _e( 'Wide', 'appica' ); ?></option>
		</select>
		<p class="description"><?php _e( 'This option affects only blog post tile', 'appica' ); ?></p>

		<label for="appica-post-custom-title"><?php _e( 'Custom Post Title', 'appica' ); ?></label>
		<input type="text" name="<?php echo $name; ?>[custom_title]" id="appica-post-custom-title" class="widefat"
	           value="<?php echo $custom_title; ?>">
		<p class="description"><?php _e( 'Title will be rendered above standard post title', 'appica' ); ?></p>

		<label for="appica-post-overlay"><?php _e( 'Choose post overlay color', 'appica' ); ?></label>
		<select name="<?php echo $name; ?>[overlay]" id="appica-post-overlay" class="widefat">
			<?php foreach( $_overlays as $_overlay => $_overlay_name ) : ?>
			<option value="<?php echo $_overlay; ?>" <?php selected( $overlay, $_overlay ); ?>><?php echo $_overlay_name; ?></option>
			<?php endforeach; ?>
		</select>
		<p class="description"><?php _e( 'Affects only "Recent Posts" widget', 'appica' ); ?></p>
	</div>
	<?php
}

/**
 * Save all custom theme meta boxes
 *
 * @since 1.0.0
 *
 * @param int $post_id Post ID
 */
function appica_save_meta_boxes( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! array_key_exists( 'appica_mb_nonce_field' , $_POST )
	     || ! wp_verify_nonce( $_POST['appica_mb_nonce_field'], 'appica_mb_nonce' )
	) {
		return;
	}

	$types = array( 'post', 'page' );
	$type  = $_POST['post_type'];
	// If current screen post or page and current user can edit this screen
	if ( ! in_array( $type, $types, true ) || ! current_user_can( "edit_{$type}", $post_id ) ) {
		return;
	}

	// Featured Video
	if ( array_key_exists( '_appica_featured_video', $_POST ) ) {
		$meta_box_name = '_appica_featured_video';
		$meta_box_value = esc_url_raw( $_POST[ $meta_box_name ] );
		update_post_meta( $post_id, $meta_box_name, $meta_box_value );
		unset( $meta_box_name, $meta_box_value );
	}

	// Post settings
	if ( array_key_exists( '_appica_post_settings', $_POST ) ) {
		$meta_box_name = '_appica_post_settings';
		$meta_box_value = array_map( 'appica_sanitize_settings_meta_box', $_POST[ $meta_box_name ] );
		update_post_meta( $post_id, $meta_box_name, $meta_box_value );
		unset( $meta_box_name, $meta_box_value );
	}

	// Page settings
	if ( array_key_exists( '_appica_page_settings', $_POST ) ) {
		$meta_box_name = '_appica_page_settings';
		$meta_box_value = array_map( 'appica_sanitize_settings_meta_box', $_POST[ $meta_box_name ] );
		update_post_meta( $post_id, $meta_box_name, $meta_box_value );
		unset( $meta_box_name, $meta_box_value );
	}
}

add_action( 'save_post', 'appica_save_meta_boxes' );

/**
 * Sanitize post or page settings meta boxes
 *
 * @since 1.0.0
 *
 * @param int|string $setting Single setting value
 *
 * @return int|null|string
 */
function appica_sanitize_settings_meta_box( $setting ) {
	$result = null;

	if ( is_numeric( $setting ) ) {
		$result = absint( $setting );
	} else {
		$result = sanitize_text_field( $setting );
	}

	return $result;
}