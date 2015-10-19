<?php
/**
 * Helpers functions library
 *
 * @author 8guild
 */

/**
 * Generate CSS rules
 *
 * @param array  $atts   Array of css rules where key is property name itself and value is a property value
 * @param string $key    CSS property name
 * @param string $return Return string or array
 *
 * @return string
 */
function appica_generate_css( $atts = array(), $key = '', $return = 'string' ) {
	$css = array();

	foreach ( (array) array_filter( $atts ) as $a => $v ) {
		$a = ( '' === $key ) ? $a : $key;
		if ( is_array( $v ) ) {
			$css = array_merge( $css, appica_generate_css( $v, $a, 'array' ) ); // combine two arrays
		} elseif ( is_scalar( $v ) ) {
			$css[] = "{$a}: {$v};";
		}
	}
	unset( $a, $v );

	return ( 'array' === $return ) ? $css : implode( ' ', $css );
}

/**
 * Prepare CSS Rules
 *
 * @since 1.0.0
 *
 * @param string|array $classes Set of class or tags, to which properties will be applied
 * @param string|array $props   Array of css rules where key is property name itself and value is a property value
 *
 * @return string
 */
function appica_generate_css_rules( $classes, $props ) {
	// Convert to string
	if ( is_array( $classes ) ) {
		$classes = implode( ', ', $classes );
	}

	// convert to string, too
	if ( is_array( $props ) ) {
		$props = appica_generate_css( $props );
	}

	return sprintf( '%1$s {%2$s}', $classes, $props );
}

/**
 * Returns supported level of nesting for comments list.
 *
 * Depends on threaded_comment option and CSS support.
 *
 * @param int $level [optional] Default supported level
 *
 * @return int|string
 */
function appica_comments_nesting_level( $level = 2 ) {
	$is_threaded_comments = (bool) get_option( 'thread_comments' );

	return ( $is_threaded_comments ) ? $level : '';
}

/**
 * Prepare the class set
 * E.g. [ 'my', 'cool', 'class' ] or 'my cool class' will be sanitized and converted to "my cool class"
 *
 * If $classes is a string - just explode. But if an array - we have to iterate through array to find "extra class".
 * "Extra class" is a string, which contains whitespaces.
 *
 * @since 1.0.0
 *
 * @param array|string $classes
 *
 * @return string
 */
function appica_get_class_set( $classes ) {
	$_classes = array();

	if ( '' === $classes ) {
		return '';
	} elseif ( is_array( $classes ) ) {
		// remove empty element before loop
		$classes = array_filter( $classes );
		// add classes to array if more than one per element
		foreach( $classes as $key => $class ) {
			$class = trim( $class );
			if ( false !== strpos( $class, ' ' ) ) {
				$_classes = array_merge( $_classes, explode( ' ', $class ) );
				unset( $classes[ $key ] );
			}

			continue;
		}
		unset( $key, $class );

		// combine two arrays
		$_classes = array_merge( $classes, $_classes );

	} else {
		$_classes = explode( ' ', $classes );
	}

	// do not duplicate
	$_classes = array_unique( $_classes );
	// sanitize
	$_classes = array_filter( $_classes );
	$_classes = array_map( 'sanitize_html_class', $_classes );
	$_classes = array_filter( $_classes );

	return implode( ' ', $_classes );
}

/**
 * Get nav tabs for vc_tabs and vc_tour
 *
 * @param string $content Shortcode tabs
 * @param string $class Class set, e.g. 'my cool class'
 *
 * @return string
 */
function appica_get_tabs_nav( $content, $class ) {
	preg_match_all( '/vc_tab([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
	$tab_titles = array();
	if ( isset( $matches[1] ) ) {
		$tab_titles = $matches[1];
	}

	$uniq_id  = '';
	$tabs_nav = '';
	$tabs_nav .= sprintf( '<ul class="%s">', $class );
	foreach ( $tab_titles as $k => $tab ) {
		$tab_atts = shortcode_parse_atts( $tab[0] );
		if ( ! array_key_exists( 'title', $tab_atts ) ) {
			continue;
		}

		$active = ( 0 === $k ) ? ' class="active"' : '';
		$uniq_id = ( array_key_exists( 'tab_id', $tab_atts ) ) ? $tab_atts['tab_id'] : sanitize_title( $tab_atts['title'] );
		$tabs_nav .= sprintf( '<li%3$s><a href="#tab-%1$s" data-toggle="tab">%2$s</a></li>', $uniq_id, $tab_atts['title'], $active );
	}
	$tabs_nav .= '</ul>';

	return $tabs_nav;
}

/**
 * Return icons from current theme pack
 *
 * @since 1.0.0
 *
 * @return array
 */
function appica_get_icons() {
	$icons = array();
	$file  = get_template_directory() . '/inc/icons.php';

	if ( is_readable( $file ) ) {
		require( $file );
	}

	if ( ! is_array( $icons ) || 0 === count( $icons ) ) {
		return array();
	}

	/**
	 * Filter the feature icons
	 *
	 * @param array $icons Icons pack. See /inc/icons.php
	 *
	 * @since 1.2.0
	 */
	return apply_filters( 'appica_feature_icons', $icons );
}

/**
 * Get post tile class set, based on if post is wide.
 *
 * @param bool $is_wide Check if post is wide
 *
 * @return string Class set for post tile wrapper
 */
function appica_get_post_class_set( $is_wide = false ) {
	$class = appica_get_class_set( array(
		'item',
		( $is_wide ) ? 'w2' : ''
	) );

	return $class;
}

/**
 * Check if post tile is wide
 *
 * @param int $post_id Post ID
 *
 * @return bool
 */
function appica_is_wide_post( $post_id ) {
	$meta_box_value = get_post_meta( $post_id, '_appica_post_settings', true );

	$is_wide = 0;
	if ( is_array( $meta_box_value ) && array_key_exists( 'is_wide', $meta_box_value ) ) {
		$is_wide = (int) $meta_box_value['is_wide'];
	}

	return ( 1 === $is_wide );
}

/**
 * Get option value by it's name
 *
 * @param string $option  Option name
 * @param mixed  $default Default option value
 *
 * @return mixed
 */
function appica_get_option( $option, $default = '' ) {
	global $appica_options;

	$value = $default;

	if ( null !== $appica_options && is_array( $appica_options ) && array_key_exists( $option, $appica_options ) ) {
		$value = $appica_options[ $option ];
	}

	return $value;
}

/**
 * Return slug name for %post_type% settings meta box.
 *
 * According to my naming conventions, all meta boxes has template _appica_%post_type%_%slug%
 * so add prefix "appica_" only for "post" or "page" post types
 *
 * @param string $post_type Post type
 *
 * @return string Slug without underscore prefix!
 */
function appica_get_settings_meta_box_slug( $post_type = 'post' ) {
	/**
	 * @var array Post types, required for "appica_" prefix
	 */
	$prefix_required_post_types = array( 'post', 'page' );
	if ( in_array( $post_type, $prefix_required_post_types, true ) ) {
		$post_type = "appica_{$post_type}";
	}

	return $post_type;
}

/**
 * Get social networks list.
 *
 * @return array
 */
function appica_get_social_networks_list() {
	$ini      = wp_normalize_path( get_template_directory() . '/js/social-networks.ini' );
	$networks = parse_ini_file( $ini, true );

	return $networks;
}

/**
 * Convert input array of user social networks to more suitable format.
 * Process networks before render, because of Redux does not support sanitize field before save to DB.
 *
 * @param array $socials Expected multidimensional array with two keys [networks] and [urls], both contains equal number of elements.
 * <code>
 * [
 *   networks => array( facebook, twitter ),
 *   urls     => array( url1, url2 ),
 * ];
 * </code>
 *
 * @return array New format of input array
 * <code>
 * [
 *   network  => url,
 *   facebook => url,
 *   twitter  => url
 * ];
 * </code>
 */
function appica_process_social_networks( $socials ) {
	if ( empty( $socials ) ) {
		return array();
	}

	// Return empty if networks or url not provided.
	if ( empty( $socials['networks'] ) || empty( $socials['urls'] ) ) {
		return array();
	}

	$result = array();
	// Network is network slug / options group from social-networks.ini
	array_map( function ( $network, $url ) use ( &$result ) {

		// Just skip iteration if network or url not set
		if ( '' === $network || '' === $url ) {
			return;
		}

		$result[ $network ] = esc_url( $url );

	}, $socials['networks'], $socials['urls'] );

	return $result;
}

/**
 * Return compiled css for <head>
 * Contains styles of Typography, Global Colors and other styles
 *
 * @since 1.0.0
 *
 * @return string
 */
function appica_get_head_css() {
	$css = array();

	/*
	 * Body typography
	 */
	$body_font_family = appica_get_option( 'typography_font_family' );
	$body_font_size   = appica_get_option( 'typography_body_font_size', 16 );

	$css[] = appica_generate_css_rules( 'body', array(
		'font-family' => stripslashes( $body_font_family ),
		'font-size'   => "{$body_font_size}px"
	) );

	/*
	 * .text-smaller Font size
	 */
	$smaller_font_size = appica_get_option( 'typography_smaller_font_size', 14 );

	$css[] = appica_generate_css_rules( '.text-smaller', array( 'font-size' => "{$smaller_font_size}px" ) );

	/*
	 * <h1> typography
	 */
	$h1_font_size   = appica_get_option( 'typography_h1_font_size', 48 );
	$h1_font_weight = appica_get_option( 'typography_h1_font_weight', 300 );
	$h1_text_transf = appica_get_option( 'typography_h1_text_transform', 'none' );

	$css[] = appica_generate_css_rules( 'h1, .h1', array(
		'font-size'      => "{$h1_font_size}px",
		'font-weight'    => $h1_font_weight,
		'text-transform' => $h1_text_transf
	) );

	/*
	 * <h2> typography
	 */
	$h2_font_size   = appica_get_option( 'typography_h2_font_size', 36 );
	$h2_font_weight = appica_get_option( 'typography_h2_font_weight', 300 );
	$h2_text_transf = appica_get_option( 'typography_h2_text_transform', 'none' );

	$css[] = appica_generate_css_rules( 'h2, .h2', array(
		'font-size'      => "{$h2_font_size}px",
		'font-weight'    => $h2_font_weight,
		'text-transform' => $h2_text_transf
	) );

	/*
	 * <h3> typography
	 */
	$h3_font_size   = appica_get_option( 'typography_h3_font_size', 24 );
	$h3_font_weight = appica_get_option( 'typography_h3_font_weight', 300 );
	$h3_text_transf = appica_get_option( 'typography_h3_text_transform', 'none' );

	$css[] = appica_generate_css_rules( 'h3, .h3', array(
		'font-size'      => "{$h3_font_size}px",
		'font-weight'    => $h3_font_weight,
		'text-transform' => $h3_text_transf
	) );

	/*
	 * <h4> typography
	 */
	$h4_font_size   = appica_get_option( 'typography_h4_font_size', 18 );
	$h4_font_weight = appica_get_option( 'typography_h4_font_weight', 400 );
	$h4_text_transf = appica_get_option( 'typography_h4_text_transform', 'none' );

	$css[] = appica_generate_css_rules( 'h4, .h4', array(
		'font-size'      => "{$h4_font_size}px",
		'font-weight'    => $h4_font_weight,
		'text-transform' => $h4_text_transf
	) );

	/*
	 * <h5> typography
	 */
	$h5_font_size   = appica_get_option( 'typography_h5_font_size', 16 );
	$h5_font_weight = appica_get_option( 'typography_h5_font_weight', 600 );
	$h5_text_transf = appica_get_option( 'typography_h5_text_transform', 'none' );

	$css[] = appica_generate_css_rules( 'h5, .h5', array(
		'font-size'      => "{$h5_font_size}px",
		'font-weight'    => $h5_font_weight,
		'text-transform' => $h5_text_transf
	) );

	/*
	 * <h6> typography
	 */
	$h6_font_size   = appica_get_option( 'typography_h6_font_size', 14 );
	$h6_font_weight = appica_get_option( 'typography_h6_font_weight', 700 );
	$h6_text_transf = appica_get_option( 'typography_h6_text_transform', 'none' );

	$css[] = appica_generate_css_rules( 'h6, .h6', array(
		'font-size'      => "{$h6_font_size}px",
		'font-weight'    => $h6_font_weight,
		'text-transform' => $h6_text_transf
	) );

	/*
	 * .badge typography
	 */
	$badge_font_size   = appica_get_option( 'typography_badge_font_size', 14 );
	$badge_font_weight = appica_get_option( 'typography_badge_font_weight', 400 );
	$badge_text_transf = appica_get_option( 'typography_badge_text_transform', 'none' );

	$css[] = appica_generate_css_rules( '.badge', array(
		'font-size'      => "{$badge_font_size}px",
		'font-weight'    => $badge_font_weight,
		'text-transform' => $badge_text_transf
	) );

	/*
	 * Body font color
	 */
	$body_font_color = appica_get_option( 'color_body_font', '#3a3a3a' );

	$css[] = appica_generate_css_rules( array(
		'body',
		'.btn',
		'.btn:hover',
		'.btn-round:hover',
		'.post-title a',
		'.post-meta',
		'.comment-count'
	), array( 'color' => $body_font_color ) );

	$css[] = appica_generate_css_rules( '.btn', array( 'border-color' => $body_font_color ) );

	/*
	 * Primary color
	 */
	$primary_color = appica_get_option( 'color_primary', '#007aff' );

	$css[] = appica_generate_css_rules( array(
		'a',
		'a.link-invert:hover',
		'a.link-invert:focus',
		'.navbar .nav-toggle',
		'.nav-tabs > li > a:hover',
		'.nav-tabs > li > a:focus',
		'.nav-tabs > li.active > a',
		'.nav-tabs > li.active > a:hover',
		'.nav-tabs > li.active > a:focus',
		'.nav-filters > li > a:hover',
		'.nav-filters > li > a:focus',
		'.nav-filters > li.active > a',
		'.nav-filters > li.active > a:hover',
		'.nav-filters > li.active > a:focus',
		'.twitter-feed .tweet a:hover',
		'.post-title a:hover',
		'.post-meta .comment-count:hover',
		'.post-meta .comment-count:hover i',
		'.comment .comment-meta .fake-link',
		'.copyright a:hover'
	), array( 'color' => $primary_color ) );

	$css[] = appica_generate_css_rules( '.text-primary', array( 'color' => "{$primary_color} !important" ) );
	$css[] = appica_generate_css_rules( array(
		'.form-control',
		'.icheckbox',
		'.iradio',
		'.load-more .shape',
		'.load-more .shape:before',
		'.load-more .shape:after',
		'.search-icon'
	), array( 'border-color' => $primary_color ) );

	$css[] = appica_generate_css_rules( '.form-control::-moz-placeholder', array( 'color' => $primary_color ) );
	$css[] = appica_generate_css_rules( '.form-control:-ms-input-placeholder', array( 'color' => $primary_color ) );
	$css[] = appica_generate_css_rules( '.form-control::-webkit-input-placeholder', array( 'color' => $primary_color ) );
	$css[] = appica_generate_css_rules( '.btn-primary', array(
		'background-color' => $primary_color,
		'border-color'     => $primary_color
	) );

	$css[] = appica_generate_css_rules( '.btn-ghost.btn-primary', array(
		'border-color' => $primary_color,
		'color'        => "{$primary_color} !important"
	) );

	$css[] = appica_generate_css_rules( array(
		'.icheckbox.checked',
		'.iradio.checked',
		'.btn-ghost.btn-primary:hover',
		'.search-icon:before',
		'.nav-tabs > li > a:after',
		'.nav-filters > li > a:after',
		'.app-gallery .item a',
		'.app-gallery .item a:before',
		'.facebook-tile',
		'.post-thumb:before',
		'.tile-solid-bg.bg-primary',
		'.featured-post.bg-primary:before',
		'.bar-charts .chart.chart-primary .bar'
	), array( 'background-color' => $primary_color ) );

	$css[] = appica_generate_css_rules( '.btn-light.btn-primary', array( 'color' => "{$primary_color} !important" ) );

	$css[] = appica_generate_css_rules( array(
		'.grid-btn span:before',
		'.grid-btn span:after'
	), array( 'border' => "1px solid {$primary_color}" ) );

	$css[] = appica_generate_css_rules( '.pace .pace-progress', array( 'background' => $primary_color ) );

	/*
	 * Primary Hover/Focus color
	 */
	$primary_hover = appica_get_option( 'color_primary_hover', '#3899ff' );

	$css[] = appica_generate_css_rules( array(
		'a:hover',
		'a:focus',
		'.navbar .nav-toggle:hover'
	), array( 'color' => $primary_hover ) );

	$css[] = appica_generate_css_rules( '.btn-primary:hover', array(
		'background-color' => $primary_hover,
		'border-color'     => $primary_hover
	) );

	$css[] = appica_generate_css_rules( '.btn-ghost.btn-primary', array( 'background-color' => 'transparent' ) );
	$css[] = appica_generate_css_rules( '.btn-ghost.btn-primary:hover', array( 'background-color' => $primary_color, 'border-color' => $primary_color ) );
	$css[] = appica_generate_css_rules( '.mCS-dark.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar', array( 'background-color' => $primary_color ) );

	/*
	 * Success color
	 */
	$success_color = appica_get_option( 'color_success', '#4cd964' );

	$css[] = appica_generate_css_rules( '.text-success', array( 'color' => "{$success_color} !important" ) );
	$css[] = appica_generate_css_rules( '.btn-success', array(
		'background-color' => $success_color,
		'border-color'     => $success_color
	) );

	$css[] = appica_generate_css_rules( '.btn-ghost.btn-success', array(
		'border-color' => $success_color,
		'color'        => "{$success_color} !important"
	) );

	$css[] = appica_generate_css_rules( array(
		'.btn-ghost.btn-success:hover',
		'.tile-solid-bg.bg-success',
		'.featured-post.bg-success:before',
		'.bar-charts .chart.chart-success .bar'
	), array( 'background-color' => $success_color ) );

	$css[] = appica_generate_css_rules( '.btn-light.btn-success', array( 'color' => "{$success_color} !important" ) );
	$css[] = appica_generate_css_rules( '.news-block', array( 'border-right-color' => $success_color ) );

	/*
	 * Success Hover/Focus color
	 */
	$success_hover = appica_get_option( 'color_success_hover', '#74e286' );

	$css[] = appica_generate_css_rules( '.btn-success:hover', array(
		'background-color' => $success_hover,
		'border-color'     => $success_hover
	) );

	$css[] = appica_generate_css_rules( '.btn-ghost.btn-success', array( 'background-color' => 'transparent' ) );
	$css[] = appica_generate_css_rules( '.btn-ghost.btn-success:hover', array( 'background-color' => $success_color, 'border-color' => $success_color ) );

	/*
	 * Info color
	 */
	$info_color = appica_get_option( 'color_info', '#5ac8fa' );

	$css[] = appica_generate_css_rules( array(
		'.text-info',
		'.facebook-tile h3 span'
	), array( 'color' => "{$info_color} !important" ) );
	$css[] = appica_generate_css_rules( '.btn-info', array( 'background-color' => $info_color, 'border-color' => $info_color ) );
	$css[] = appica_generate_css_rules( '.btn-ghost.btn-info', array( 'border-color' => $info_color, 'color' => "{$info_color} !important" ) );
	$css[] = appica_generate_css_rules( '.btn-light.btn-info', array( 'color' => "{$info_color} !important" ) );
	$css[] = appica_generate_css_rules( array(
		'.twitter-tile',
		'.post-thumb.colored',
		'.tile-solid-bg.bg-info',
		'.featured-post.bg-info:before',
		'.bar-charts .chart.chart-info .bar'
	), array( 'background-color' => $info_color ) );

	/*
	 * Info Hover/Focus color
	 */
	$info_hover = appica_get_option( 'color_info_hover', '#8dd9fb' );

	$css[] = appica_generate_css_rules( '.btn-info:hover', array( 'background-color' => $info_hover, 'border-color' => $info_hover ) );
	$css[] = appica_generate_css_rules( '.btn-ghost.btn-info', array( 'background-color' => 'transparent' ) );
	$css[] = appica_generate_css_rules( '.btn-ghost.btn-info:hover', array( 'background-color' => $info_color, 'border-color' => $info_color ) );

	/*
	 * Warning color
	 */
	$warning_color = appica_get_option( 'color_warning', '#ffcc00' );

	$css[] = appica_generate_css_rules( '.text-warning', array( 'color' => "{$warning_color} !important" ) );
	$css[] = appica_generate_css_rules( '.btn-warning', array( 'background-color' => $warning_color, 'border-color' => $warning_color ) );
	$css[] = appica_generate_css_rules( '.btn-ghost.btn-warning', array( 'border-color' => $warning_color, 'color' => "{$warning_color} !important" ) );
	$css[] = appica_generate_css_rules( '.btn-light.btn-warning', array( 'color' => "{$warning_color} !important" ) );
	$css[] = appica_generate_css_rules( array(
		'.tile-solid-bg.bg-warning',
		'.timeline .date:before',
		'.featured-post.bg-warning:before',
		'.bar-charts .chart.chart-warning .bar'
	), array( 'background-color' => $warning_color ) );

	/*
	 * Warning Hover/Focus color
	 */
	$warning_hover = appica_get_option( 'color_warning_hover', '#ffd633' );

	$css[] = appica_generate_css_rules( '.btn-warning:hover', array( 'background-color' => $warning_hover, 'border-color' => $warning_hover ) );
	$css[] = appica_generate_css_rules( '.btn-ghost.btn-warning', array( 'background-color' => 'transparent' ) );
	$css[] = appica_generate_css_rules( '.btn-ghost.btn-warning:hover', array( 'background-color' => $warning_color, 'border-color' => $warning_color ) );

	/*
	 * Danger color
	 */
	$danger_color = appica_get_option( 'color_danger', '#ff2d55' );
	
	$css[] = appica_generate_css_rules( '.text-danger', array( 'color' => $danger_color ) );
	$css[] = appica_generate_css_rules( '.btn-danger', array( 'background-color' => $danger_color, 'border-color' => $danger_color ) );
	$css[] = appica_generate_css_rules( '.btn-ghost.btn-danger', array( 'border-color' => $danger_color, 'color' => "{$danger_color} !important" ) );
	$css[] = appica_generate_css_rules( '.btn-light.btn-danger', array( 'color' => "{$danger_color} !important" ) );
	$css[] = appica_generate_css_rules( array(
		'.tile-solid-bg.bg-danger',
		'.featured-post.bg-danger:before',
		'.bar-charts .chart.chart-danger .bar'
	), array( 'background-color' => $danger_color ) );
	
	/*
	 * Danger Hover/Focus color
	 */
	$danger_hover = appica_get_option( 'color_danger_hover', '#ff617e' );

	$css[] = appica_generate_css_rules( '.btn-danger:hover', array( 'background-color' => $danger_hover, 'border-color' => $danger_hover ) );
	$css[] = appica_generate_css_rules( '.btn-ghost.btn-danger', array( 'background-color' => 'transparent' ) );
	$css[] = appica_generate_css_rules( '.btn-ghost.btn-danger:hover', array( 'background-color' => $danger_color, 'border-color' => $danger_color ) );

	/*
	 * Light color
	 */
	$light_color = appica_get_option( 'color_text_light', '#c4c4c4' );

	$css[] = appica_generate_css_rules( '.text-muted', array( 'color' => "{$light_color} !important" ) );
	$css[] = appica_generate_css_rules( '.unordered-list li:before', array( 'background-color' => $light_color ) );
	$css[] = appica_generate_css_rules( array(
		'.load-more span',
		'.modal-title',
		'.twitter-feed .tweet .author',
		'.post-title span',
		'.post-title p',
		'.post-meta',
		'.page-slider .min-val',
		'.page-slider .max-val',
		'.pagination .page-numbers.current',
		'.comment .comment-meta span',
		'.timeline .date',
		'.contact-info .nav-tabs li.active a',
		'.widget_recent_comments li',
		'.widget_archive li'
	), array( 'color' => $light_color ) );

	/*
	 * Dark color
	 */
	$dark_color = appica_get_option( 'color_text_dark', '#8e8e93' );

	$css[] = appica_generate_css_rules( array(
		'dl dt span',
		'figure figcaption',
		'.block-heading span',
		'.checkbox',
		'.radio',
		'.checkbox-inline',
		'.radio-inline',
		'.downloadable p',
		'.navbar .logo',
		'.navbar .toolbar span',
		'.widget-title',
		'.news-block span',
		'.twitter-feed .tweet a',
		'.twitter-feed .tweet p',
		'.team-member span',
		'.team-grid .item span',
		'.post-meta .comment-count i',
		'.page-slider .tooltip-inner',
		'.footer-head span',
		'.footer .rating span',
		'.copyright p',
		'.copyright a',
		'#preloader .logo span',
		'.icons-demo'
	), array( 'color' => $dark_color ) );

	$css[] = appica_generate_css_rules( '.text-gray', array( 'color' => "{$dark_color} !important" ) );

	/*
	 * Dark gray color fix
	 */
	$css[] = appica_generate_css_rules( '.light-color .block-heading span', array( 'color' => '#fff', 'opacity' => '0.6' ) );

	/*
	 * Intro overlay
	 */
	if ( true === (bool) appica_get_option( 'intro_is_overlay', true ) ) {
		$css[] = appica_get_intro_overaly_css();
	}

	/*
	 * Navbar mobile @media
	 */
	$css[] = appica_get_navbar_mobile_css();

	/**
	 * Device colors
	 *
	 * @since 1.2.0
	 */
	$intro_phone  = appica_get_option( 'intro_device_color', 'gold' );
	$footer_phone = appica_get_option( 'footer_device_color', 'gold' );

	$css[] = appica_generate_css_rules( '.intro .phone', array(
		'background-image' => sprintf( 'url(%s)', appica_image_uri( "img/intro/phone-{$intro_phone}.png", false ) )
	) );

	$css[] = appica_generate_css_rules( '.footer .gadget', array(
		'background-image' => sprintf( 'url(%s)', appica_image_uri( "img/footer/ipad-{$footer_phone}.png", false ) )
	) );

	return implode( PHP_EOL, array_filter( $css ) );
}

/**
 * Generate CSS rules for Intro screen overlay
 *
 * @since 1.0.0
 *
 * @return string
 */
function appica_get_intro_overaly_css() {
	$css = '';

	$intro_overlay_opacity = appica_get_option( 'intro_overlay_opacity' );
	$intro_overlay_opacity = rtrim( $intro_overlay_opacity, '.00' );
	$intro_overlay_type    = appica_get_option( 'intro_overlay_type', 'gradient' );

	if ( 'solid' === $intro_overlay_type ) {
		$intro_overlay_solid = appica_get_option( 'intro_overlay_color' );

		// intro_overlay_solid_rgba
		$iosr = ( is_array( $intro_overlay_solid ) && array_key_exists( 'rgba', $intro_overlay_solid ) )
			? $intro_overlay_solid['rgba']
			: '#3a1cff';

		$css .= appica_generate_css_rules( '.intro .gradient', array(
			'opacity'          => $intro_overlay_opacity,
			'background-color' => $iosr
		) );
	} else {
		$intro_overlay_gradient = appica_get_option( 'intro_overlay_gradient' );

		// intro_overlay_gradient_from
		$iogf = ( is_array( $intro_overlay_gradient ) && array_key_exists( 'from', $intro_overlay_gradient ) )
			? $intro_overlay_gradient['from']
			: '#3a1cff';

		// intro_overlay_gradient_to
		$iogt = ( is_array( $intro_overlay_gradient ) && array_key_exists( 'to', $intro_overlay_gradient ) )
			? $intro_overlay_gradient['to']
			: '#ff3a30';

		$css .= appica_generate_css_rules( '.intro .gradient', array(
			'opacity'    => $intro_overlay_opacity,
			'background' => array(
				$iogf,
				"-moz-linear-gradient(top, {$iogf} 0%, {$iogt} 100%)",
				"-webkit-gradient(left top, left bottom, color-stop(0%, {$iogf}), color-stop(100%, {$iogt}))",
				"-webkit-linear-gradient(top, {$iogf} 0%, {$iogt} 100%)",
				"-o-linear-gradient(top, {$iogf} 0%, {$iogt} 100%)",
				"-ms-linear-gradient(top, {$iogt} 0%, {$iogt} 100%)",
				"linear-gradient(to bottom, {$iogf} 0%, {$iogt} 100%)"
			),
			'filter'     => "progid:DXImageTransform.Microsoft.gradient( startColorstr='{$iogf}', endColorstr='{$iogt}', GradientType=0 )"
		) );
	}

	return $css;
}

/**
 * Generate CSS rules for Navbar mobile
 *
 * @since 1.0.0
 *
 * @return string
 */
function appica_get_navbar_mobile_css() {
	$width = appica_get_option( 'navbar_width', 991 );
	if ( '' === $width || 0 === $width ) {
		return '';
	}

	$css = '.navbar {height: 80px;}';
	$css .= '.navbar.navbar-fixed-top + * {padding-top: 80px;}';
	$css .= '.navbar.navbar-fixed-top + .page-heading {padding-top: 120px;}';
	$css .= '.navbar. .container {width: 100%; padding: 0 20px;}';
	$css .= '.navbar .logo {line-height: 78px;}';
	$css .= '.navbar .social-buttons, .navbar .toolbar span, .navbar .toolbar .btn, .navbar .toolbar .action-btn {display: none;}';
	$css .= '.navbar .nav-toggle {margin-top: 15px;}';
	$css .= '.navbar  + .page > .vc_row, .navbar.navbar-fixed-top + .page > .vc_row, .navbar.navbar-sticky.stuck  + .page > .vc_row {padding-top: 50px !important;}';

	$media = appica_generate_css_rules( "@media screen and (max-width: {$width}px)", $css );

	return $media;
}

/**
 * Return terms, assigned for specified Post ID, depending on {@see $context} param: "slug" or "name".
 *
 * @param integer $post_id  Post ID.
 * @param string  $taxonomy The taxonomy for which to retrieve terms.
 * @param string  $context  [optional] Term slug or name, depending on what is required. Default is "slug".
 *
 * @return array <code>[ term, term, ... ]</code>
 *
 * @since 1.3.0 added to iOS version
 */
function appica_get_post_terms( $post_id, $taxonomy, $context = 'slug' ) {
	$terms      = array();
	$post_terms = wp_get_post_terms( $post_id, $taxonomy );
	// Catch the WP_Error or if any terms was not assigned to post
	if ( is_wp_error( $post_terms ) || 0 === count( $post_terms ) ) {
		return $terms;
	}

	foreach ( $post_terms as $term ) {
		$terms[] = $term->$context;
	}
	unset( $term, $post_terms );

	return $terms;
}