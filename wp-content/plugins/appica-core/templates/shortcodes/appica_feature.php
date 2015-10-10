<?php
/**
 * Shortcode "Feature" output
 *
 * Mapped params are in {@path appica-core/inc/vc-map.php} {@see $appica_feature}
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

if( ! function_exists( 'vc_build_link' ) ) {
	return;
}

$a = shortcode_atts( array(
	'title'            => '',
	'description'      => '',
	'link'             => '',
	'link_text'        => '',
	'align'            => 'left', // left | center | right
	'icon_lib'         => 'fontawesome', // fontawesome | openiconic | typicons | entypo | linecons | flaticons
	'icon_fontawesome' => '',
	'icon_openiconic'  => '',
	'icon_typicons'    => '',
	'icon_entypo'      => '',
	'icon_linecons'    => '',
	'icon_flaticons'   => '',
	'icon_size'        => 'default', // default | large
	'icon_pos'         => 'left', // left | top | right
	'icon_va'          => 'top', // top | middle
	'extra_class'      => ''
), $atts );

// Title & description
$title = ( '' === $a['title'] ) ? '' : esc_html( $a['title'] );
$desc  = ( '' === $a['description'] ) ? '' : strip_tags( $a['description'], '<p>' );
$align = esc_attr( $a['align'] );

// Build a link
$_link   = vc_build_link( $a['link'] );
$_href   = ( '' === $_link['url'] ) ? '' : esc_url( $_link['url'] );
$_target = ( '' === $_link['target'] ) ? '' : sprintf( 'target="%s"', trim( $_link['target'] ) );
$_title  = ( '' === $_link['title'] ) ? '' : sprintf( 'title="%s"', esc_html( $_link['title'] ) );
$_text   = esc_html( $a['link_text'] );
$link    = ( '' === $_href || '' === $_text ) ? '' : sprintf( '<a href="%1$s" class="link" %3$s %4$s>%2$s</a>', $_href, $_text, $_title, $_target );

unset( $_link, $_href, $_target, $_title, $_text );

// Build text block
$block_text = sprintf( '<div class="text text-%4$s">%1$s%2$s%3$s</div>', "<h3>{$title}</h3>", "<p>{$desc}</p>", $link, $align );

unset( $text_class, $title, $desc, $link );

// Build icon
$block_icon = '';
$icon_pos   = $a['icon_pos'];
$icon_size  = $a['icon_size'];
$icon_va    = $a['icon_va'];
$library    = $a['icon_lib'];

if ( '' !== $a["icon_{$library}"] ) {
	// enqueue necessary css
	vc_icon_element_fonts_enqueue( $library );

	$icon_wrapper_class = Appica_Helpers::get_class_set( array(
		'icon',
		( 'large' === $icon_size ) ? 'icon-bigger' : '',
		( 'middle' === $icon_va ) ? 'va-middle' : ''
	) );

	$block_icon = sprintf( '<div class="%2$s"><i class="%1$s"></i></div>', $a["icon_{$library}"], $icon_wrapper_class );

	unset( $icon_wrapper_class, $library );
}

// Wrapper class
$wrapper_class = Appica_Helpers::get_class_set( array(
	'icon-block',
	( 'left' === $icon_pos || 'right' === $icon_pos ) ? 'icon-block-horizontal' : '',
	$a['extra_class']
) );

/*
 * If icon position is "left" or "top", icon have to be before .text block
 * But, if icon position is "right", icon have to be after .text block
 */

// 1 - wrapper class, 2 - text block, 3 - icon block
$tpl = ( 'left' === $icon_pos || 'top' === $icon_pos ) ? '<div class="%1$s">%3$s%2$s</div>' : '<div class="%1$s">%2$s%3$s</div>';

printf( $tpl, $wrapper_class, $block_text, $block_icon );