<?php
/**
 * Shortcode "Download Button" output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

if ( ! function_exists( 'vc_build_link' ) ) {
	return;
}

$a = shortcode_atts( array(
	'text'        => '',
	'link'        => '',
	'extra_class' => ''
), $atts );

$text = esc_html( $a['text'] );
$link = vc_build_link( $a['link'] );

$href   = esc_url( $link['url'] );
$target = $link['target'];

$classes = Appica_Helpers::get_class_set( array( 'btn', 'btn-default', 'btn-app-store', $a['extra_class'] ) );
// 1 - href, 2 - text, 3 - classes, 4 - target
printf( '<a href="%1$s" class="%3$s" target="%4$s"><i class="bi-apple"></i><div><span>%2$s</span>App Store</div></a>', $href, $text, $classes, $target );