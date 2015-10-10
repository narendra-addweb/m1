<?php
/**
 * Shortcode "Button" output
 *
 * Mapped params are in {@path appica-core/inc/vc-map.php} {@see $appica_button}
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

$a = shortcode_atts( array(
	'text'             => '',
	'link'             => '',
	'type'             => 'default', // default | round | flat
	'waves'             => 'dark', // dark | light
	'style'            => 'standard', // standard | outlined
	'size'             => 'nl', // small (sm) | normal (nl) | large (lg)
	'color'            => 'default', // default | primary | success etc
	'icon_lib'         => 'fontawesome', // fontawesome | openiconic | typicons | entypo | linecons | flaticons
	'icon_fontawesome' => '',
	'icon_openiconic'  => '',
	'icon_typicons'    => '',
	'icon_entypo'      => '',
	'icon_linecons'    => '',
	'icon_flaticons'   => '',
	'icon_pos'         => 'left', // left | right
	'is_full'          => 'no', // yes | no
	'extra_class'      => ''
), $atts );

// Build link
$link   = vc_build_link( $a['link'] );
$href   = ( '' === $link['url'] ) ? '#' : esc_url( $link['url'] );
$target = ( '' === $link['target'] ) ? '' : sprintf( 'target="%s"', trim( $link['target'] ) );
$title  = ( '' === $link['title'] ) ? '' : sprintf( 'title="%s"', esc_attr( $link['title'] ) );

// Text
$content = ( 'round' === $a['type'] ) ? '' : esc_attr( $a['text'] );

// Icon
$icon     = '';
$icon_pos = ( 'right' === $a['icon_pos'] ) ? 'icon-right' : 'icon-left';
$library  = $a['icon_lib'];
if ( '' !== $a["icon_{$library}"] ) {
	vc_icon_element_fonts_enqueue( $library );
	$icon = sprintf( '<i class="%s"></i>', $a["icon_{$library}"] );
}

// Global classes
$class = Appica_Helpers::get_class_set( array(
	( 'default' === $a['type'] ) ? 'btn' : "btn-{$a['type']}",
	( 'standard' === $a['style'] ) ? '' : 'btn-ghost',
	( 'nl' === $a['size'] ) ? '' : "btn-{$a['size']}",
	( 'default' === $a['color'] ) ? 'btn-default' : "btn-{$a['color']}",
	( 'default' === $a['type'] && 'yes' === $a['is_full'] ) ? 'btn-block' : '',
	( 'default' === $a['type'] && '' !== $icon ) ? $icon_pos : '',
	$a['extra_class']
) );

// Template with icon position
$tpl = ( 'right' === $a['icon_pos'] )
	? '<a href="%1$s" class="%4$s" %5$s %6$s>%2$s %3$s</a>'
	: '<a href="%1$s" class="%4$s" %5$s %6$s>%3$s %2$s</a>';

// 1 - href, 2 - content, 3 - icon, 4 - class, 5 - target, 6 - title
printf( $tpl, $href, $content, $icon, $class, $target, $title );
