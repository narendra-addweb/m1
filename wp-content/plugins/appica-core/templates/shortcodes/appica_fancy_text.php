<?php
/**
 * Shortcode "Fancy text" output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

$a = shortcode_atts( array(
	'text'        => '',
	'color'       => 'primary', // default | primary | success | etc
	'extra_class' => ''
), $atts );

$text    = esc_html( $a['text'] );
$classes = Appica_Helpers::get_class_set( array( 'text-extra-big', 'color-gradient', $a['extra_class'] ) );
// 1 - text, 2 - classes
printf( '<h3 class="%2$s">%1$s</h3>', $text, $classes );