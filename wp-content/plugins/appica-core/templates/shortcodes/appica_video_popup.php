<?php
/**
 * Shortcode "Video Popup" output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

$a = shortcode_atts( array(
	'text'        => '',
	'video'       => '',
	'extra_class' => ''
), $atts );

$text  = esc_html( $a['text'] );
$video = esc_url( $a['video'] );
$class = Appica_Helpers::get_class_set( array( 'video-popup', $a['extra_class'] ) );

printf( '<div class="video-block"><a class="%3$s" href="%1$s"><i class="flaticon-play33"></i>%2$s</a></div>', $video, $text, $class );