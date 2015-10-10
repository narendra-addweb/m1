<?php
/**
 * Shortcode "Half Block Image" output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

/*
 * This shortcode uses $content
 */

if ( ! function_exists( 'wpb_js_remove_wpautop' ) ) {
	return;
}

$a = shortcode_atts( array(
	'title'       => '',
	'subtitle'    => '',
	'image'       => '',
	'align'       => 'left', // left | right
	'extra_class' => ''
), $atts );

$title    = esc_html( $a['title'] );
$subtitle = esc_html( $a['subtitle'] );
$image    = absint( $a['image'] );
$is_right = ( 'right' === $a['align'] );

$classes = Appica_Helpers::get_class_set( array(
	'split-block',
	( $is_right ) ? 'img-right' : 'img-left',
	$a['extra_class']
) );

// Image
$_image = wp_get_attachment_image( $image, 'full' );
if ( '' !== $_image ) {
	$_image = "<div class=\"column\">{$_image}</div>";
}

// Content
$_heading = '';
if ( '' !== $title || '' !== $subtitle ) {
	$_heading = sprintf( '<div class="block-heading">%1$s%2$s</div>', "<h2>{$title}</h2>", "<span>{$subtitle}</span>" );
}
// 1 - heading, 2 - content
$_content = sprintf( '<div class="column">%1$s%2$s</div>', $_heading, wpb_js_remove_wpautop( $content, true ) );
// 1 - image, 2 - content, 3 - classes
$_tpl = ( $is_right ) ? '<div class="%3$s">%2$s%1$s</div>' : '<div class="%3$s">%1$s%2$s</div>';

printf( $_tpl, $_image, $_content, $classes );

unset( $_tpl, $_heading, $_image, $_content, $classes, $title, $subtitle, $image, $is_right, $a );