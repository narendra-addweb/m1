<?php
/**
 * Shortcode "Appica Custom Title" output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

$a = shortcode_atts( array(
	'title'       => '',
	'subtitle'    => '',
	'align'       => 'left', // left | center | right
	'extra_class' => ''
), $atts );

/*
 * Prepare attributes
 */
$title    = esc_html( $a['title'] );
$subtitle = esc_attr( $a['subtitle'] );
$align    = $a['align'];

$classes = Appica_Helpers::get_class_set( array( 'block-heading', "text-{$align}", $a['extra_class'] ) );

// 1 - title, 2 - subtitle, 3 - class set
printf( '<div class="%3$s"><h2>%1$s</h2><span>%2$s</span></div>', $title, $subtitle, $classes );