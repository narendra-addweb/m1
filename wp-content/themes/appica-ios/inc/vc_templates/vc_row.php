<?php
/**
 * Shortcode vc_row custom output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Visual Composer
 */

// no extract(), please
$a = shortcode_atts( array(
	'el_class'         => '',
	'css'              => '',
	'content_color'    => 'dark', // dark | light
	'uniq_id'          => '', // ID for anchored navigation
	'is_container'     => 'yes', // yes | no
	'badge'            => 'hide', // show | hide
	'badge_align'      => 'left', // left | right
	'badge_title'      => '',
	'badge_pc'         => 'color1', // predefined color
	'badge_cc'         => '', // custom color
	'badge_btc'        => '', // badge border top color
	'badge_tc'         => '', // badge text color
	'badge_fs'         => '', // badge font size
	'badge_class'      => '', // extra class
	'icon_lib'         => 'fontawesome', // fontawesome | openiconic | typicons | entypo | linecons | flaticons
	'icon_fontawesome' => '',
	'icon_openiconic'  => '',
	'icon_typicons'    => '',
	'icon_entypo'      => '',
	'icon_linecons'    => '',
	'icon_flaticons'   => '',
	'overlay'          => 'disable', // enable | disable
	'overlay_type'     => 'gradient', // solid | gradient
	'overlay_partial'  => 'no', // yes | no
	'overlay_sc'       => '', // overlay solid color
	'overlay_gc_start' => '', // overlay gradient start color
	'overlay_gc_end'   => '', // overlay gradient end color
	'overlay_opacity'  => '',
	'overlay_class'    => ''
), $atts );

/*
 * Prepare attributes
 */
$output          = '';
$el_class        = $a['el_class'];
$css             = $a['css'];
$content_color   = $a['content_color'];
$uniq_id         = $a['uniq_id'];
$is_container    = ( 'yes' === $a['is_container'] );
$is_inner        = ( 'vc_row_inner' === $this->settings( 'base' ) );
$badge           = ( 'show' === $a['badge'] );
$overlay         = ( 'enable' === $a['overlay'] );
$is_overlay_part = ( 'yes' === $a['overlay_partial'] );

$overlay_style = '';
$badge_html    = '';

wp_enqueue_script( 'wpb_composer_front_js' );

/*
 * Custom css class for styling in head
 */
$row_custom_css_class = trim( vc_shortcode_custom_css_class( $css, ' ' ) );

/*
 * Overlay
 */
if ( $overlay ) {
	$overlay_css     = '';
	$overlay_type    = $a['overlay_type'];
	$overlay_opacity = ( '100' === $a['overlay_opacity'] ) ? 1 : sprintf( '0.%d', trim( $a['overlay_opacity'], '0.' ) );

	/**
	 * @var string Row selector
	 */
	$row_selector = ( '' === $row_custom_css_class ) ? "#{$uniq_id}" : ".{$row_custom_css_class}";

	switch ( $overlay_type ) {
		case 'solid':
			$overlay_sc  = $a['overlay_sc'];
			$overlay_css = appica_generate_css( array(
				'opacity'          => $overlay_opacity,
				'background-color' => $overlay_sc
			) );
			break;

		case 'gradient':
			$overlay_gc_start = $a['overlay_gc_start'];
			$overlay_gc_end   = $a['overlay_gc_end'];
			$overlay_css = appica_generate_css( array(
				'opacity'    => $overlay_opacity,
				'background' => array(
					"{$overlay_gc_start}",
					"-moz-linear-gradient(top, {$overlay_gc_start} 0%, {$overlay_gc_end} 100%)",
					"-webkit-gradient(left top, left bottom, color-stop(0%, {$overlay_gc_start}), color-stop(100%, {$overlay_gc_end}))",
					"-webkit-linear-gradient(top, {$overlay_gc_start} 0%, {$overlay_gc_end} 100%)",
					"-o-linear-gradient(top, {$overlay_gc_start} 0%, {$overlay_gc_end} 100%)",
					"-ms-linear-gradient(top, {$overlay_gc_start} 0%, {$overlay_gc_end} 100%)",
					"linear-gradient(to bottom, {$overlay_gc_start} 0%, {$overlay_gc_end} 100%)"
				)
			) );
			break;
	}

	if ( '' !== $overlay_css ) {
		// 1 - selector, 2 - css
		$overlay_style = sprintf( '%1$s:before{%2$s}', $row_selector, $overlay_css );

		// wp_add_inline_style() & wp_head action not working in shortcode

		// wp_add_inline_style( 'appica', $overlay_style );
		// add_action( 'wp_head', function() use ( $overlay_style ) {
		//   echo '<style type="text/css" id="appica-vc_row">', $overlay_style, '</style>';
		// } );
	}

	unset( $overlay_css, $overlay_type, $overlay_opacity, $overlay_sc, $overlay_gc_start, $overlay_gc_end, $row_selector );
}

/*
 * Badge
 */
if ( $badge ) {
	$badge_align = $a['badge_align'];
	$badge_title = $a['badge_title'];
	$badge_pc    = $a['badge_pc'];
	$badge_cc    = $a['badge_cc'];
	$badge_btc   = $a['badge_btc'];
	$badge_tc    = $a['badge_tc'];
	$badge_fs    = rtrim( $a['badge_fs'], 'px' );
	$badge_class = $a['badge_class'];
	$library     = $a['icon_lib'];
	$badge_icon  = 'flaticon-star51';

	if ( '' !== $a["icon_{$library}"] ) {
		// enqueue necessary css
		vc_icon_element_fonts_enqueue( $library );
		$badge_icon = $a["icon_{$library}"];
	}

	$badge_style_rules = array();
	if ( '' !== $badge_btc ) {
		$badge_style_rules['border-top'] = "1px solid {$badge_btc}";
	}
	if ( '' !== $badge_tc ) {
		$badge_style_rules['color'] = $badge_tc;
	}
	if ( '' !== $badge_fs ) {
		$badge_style_rules['font-size'] = "{$badge_fs}px";
	}

	$badge_style   = sprintf( 'style="%s"', appica_generate_css( $badge_style_rules ) );
	$badge_classes = appica_get_class_set( array(
		'badge',
		( 'right' === $badge_align ) ? 'badge-reverse' : '',
		( '' === $badge_cc && 'default' === $badge_pc ) ? '' : $badge_pc, // if not default and custom color used, set alt-* color
		$badge_class
	) );

	$badge_icon = ( '' === $badge_cc )
		? sprintf( '<span class="icon"><i class="%s"></i></span>', $badge_icon )
		: sprintf( '<span class="icon" style="background: %1$s !important;"><i class="%2$s"></i></span>', $badge_cc, $badge_icon );

	// 1 - title, 2 - icon, 3 - class set, 4 - inline css
	$badge_tpl = ( 'right' === $badge_align ) ? '<div class="%3$s" %4$s>%1$s%2$s</div>' : '<div class="%3$s" %4$s>%2$s%1$s</div>';
	$badge_html = sprintf( $badge_tpl, $badge_title, $badge_icon, $badge_classes, $badge_style );

	unset(
		$badge, $badge_align, $badge_title, $badge_cc, $badge_pc, $badge_class, $badge_classes, $badge_style,
		$badge_icon, $badge_tpl, $badge_btc, $badge_tc, $badge_style_rules
	);
}

/*
 * Prepare ROW class set
 */
$row_classes = appica_get_class_set( array(
	'fw-bg',
	'vc_row',
	//'vc_row-no-padding',
	get_row_css_class(),
	$is_inner ? 'vc_inner ' : '',
	$row_custom_css_class,
	$this->getExtraClass( $el_class ),
	( 'light' === $content_color ) ? 'light-color' : '',
	( $overlay ) ? 'overlay' : '',
	( $is_overlay_part ) ? 'partial-overlay' : ''
) );

$row_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $row_classes, $this->settings['base'], $atts );

/*
 * Start output .vc_row
 * 1 - ID, 2 - classes, 3 - overlay css, 4 - badge
 */
$output .= sprintf(
	'<div id="%1$s" class="%2$s" data-vc-full-width="true" data-vc-stretch-content="true" data-overlay="%3$s">',
	esc_attr( $uniq_id ), $row_classes, $overlay_style
);

// wrap to .container, if need
if ( false === $is_inner && $is_container ) {
	$output .= sprintf( '<div class="container">%s<div class="row">', $badge_html );
}

$output .= wpb_js_remove_wpautop( $content );

if ( false === $is_inner && $is_container ) {
	$output .= '</div></div>';
}

$output .= '</div><div class="vc_row-full-width"></div>';

echo $output;
