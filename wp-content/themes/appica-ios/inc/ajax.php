<?php
/**
 * Theme AJAX handlers
 *
 * @author 8guild
 * @package Appica 2
 * @since 1.0.0
 */

if ( is_admin() ) {
	// Load more posts
	add_action( 'wp_ajax_appica_load_more_posts', 'appica_load_more_posts' );
	add_action( 'wp_ajax_nopriv_appica_load_more_posts', 'appica_load_more_posts' );

	// Icons Popup
	add_action( 'wp_ajax_appica_icons', 'appica_icons_callback' );
	// Featured Video
	add_action( 'wp_ajax_appica_featured_video', 'appica_ajax_featured_video' );
}

/**
 * Appica AJAX handler for "Load More" button at home page
 *
 * Outputs HTML
 *
 * @since 1.0.0
 */
function appica_load_more_posts() {
	// Check nonce.
	if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'appica-ajax' ) ) {
		wp_send_json_error( 'Wrong nonce' ); // die();
	}

	$per_page = (int) get_option( 'posts_per_page' );
	$cur_page = (int) $_POST['page'];

	$query = new WP_Query( array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'paged'               => $cur_page,
		'posts_per_page'      => $per_page,
		'ignore_sticky_posts' => true
	) );

	if ( $query->have_posts() ) {

		$posts = array();

		while( $query->have_posts() ) {
			$query->the_post();
			ob_start();
			get_template_part( 'content', 'home' );
			$posts[] = ob_get_clean();
		}
		wp_reset_postdata();

		wp_send_json_success( $posts );
	}

	wp_send_json_error();
}

/**
 * AJAX callback for rendering icons popup.
 *
 * Outputs HTML
 *
 * @since 1.0.0
 */
function appica_icons_callback() {
	if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'appica-ajax' ) ) {
		die();
	}

	// Current icon, for repeated opening popup
	$current = sanitize_text_field( $_POST['current'] );

	$icons = appica_get_icons();
	$html = '';

	if ( 0 !== count( $icons ) ) {
		$_el = array();

		/**
		 * @var string Template for single filterable icon
		 */
		$tpl = '<li data-filtertext="%1$s" %2$s><a href="#" class="pios-icon" data-icon="%1$s"><i class="%1$s"></i></a></li>';

		$html .= '<form class="ui-filterable"><input type="text" id="pios-icons-filterable-input" class="widefat" data-type="search"></form>';
		$html .= '<ul class="pios-filterable-icons clearfix" data-role="listview" data-filter="true" data-input="#pios-icons-filterable-input">';

		foreach ( (array) $icons as $icon ) {
			$active = ( $icon === $current ) ? 'class="active"' : '';
			$_el[] = sprintf( $tpl, $icon, $active );
		}

		$html .= implode( '', $_el );
		$html .= '</ul>';
	} else {
		$html .= '<p>' . __( 'No icons found', 'appica' ) . '</p>';
	}

	print $html;

	die();
}

/**
 * AJAX callback for Featured Video
 *
 * Outputs embed video or error string
 *
 * @since 1.0.0
 */
function appica_ajax_featured_video() {
	// Verify nonce
	if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'appica-ajax' ) ) {
		wp_send_json_error( 'Nonce is not valid' );
	}

	$url = esc_url_raw( $_POST['url'] );
	// Just die in silence
	if ( empty( $url ) ) {
		wp_send_json_error();
	}

	// Else get oEmbed code
	$embed = wp_oembed_get( $url, array( 'width' => 510 ) );

	if ( false === $embed ) {
		wp_send_json_error( __( 'URL is not valid or provider do not support oEmbed protocol', 'appica' ) );
	}

	wp_send_json_success( $embed );
}