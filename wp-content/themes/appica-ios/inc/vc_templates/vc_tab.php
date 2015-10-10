<?php
/**
 * Shortcode vc_tab custom output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Visual Composer
 */

$a = shortcode_atts( array(
	'tab_id'     => '',
	'title'      => '',
	'transition' => ''
), $atts );

$title   = esc_html( $a['title'] );
$tab_id  = ( '' === $a['tab_id'] ) ? sanitize_title( $title ) : $a['tab_id'];
// transition effect
$transit = trim( $a['transition'], '_' );
$transit = ( '' === $transit || 'fade' === $transit ) ? '' : esc_html( $transit );
$content = ( '' === trim( $content ) ) ? __( 'Empty tab. Edit page to add content here.', 'appica' ) : wpb_js_remove_wpautop( $content );
$class   = appica_get_class_set( array( 'tab-pane', 'transition', 'fade', $transit ) );

printf( '<div class="%3$s" id="tab-%1$s">%2$s</div>', $tab_id, $content, $class );