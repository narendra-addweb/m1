<?php
/**
 * Custom VC's "Single Image" HTML output
 *
 * @author  8guild
 * @package Appica 2
 */

$output = $el_class = $image = $img_size = $img_link = $img_link_target = $img_link_large = $title = $alignment = $css_animation = $css = $link = $style = '';
$is_caption_used = $caption_line = $caption_align = $caption_class = '';

extract( shortcode_atts( array(
	'title'           => '',
	'image'           => $image,
	'img_size'        => 'thumbnail',
	'img_link_large'  => false,
	'img_link'        => '',
	'link'            => '',
	'img_link_target' => '_self',
	'alignment'       => 'left',
	'el_class'        => '',
	'css_animation'   => '',
	'style'           => '',
	'border_color'    => '',
	'css'             => '',
	'is_caption_used' => 'no',
	'caption_line'    => '',
	'caption_align'   => 'right',
	'caption_class'   => '',
), $atts ) );

$style = ( $style != '' ) ? $style : '';
$border_color = ( $border_color != '' ) ? 'vc_box_border_' . $border_color : '';

// Image ID
$img_id = preg_replace( '/[^\d]/', '', $image );

// Extra class to <img>
$img_class = array( 'vc_single_image-img' );

// Prepare <img> class
$img_class = appica_get_class_set( $img_class );

// Get <img> element
$img = wpb_getImageBySize( array( 'attach_id' => $img_id, 'thumb_size' => $img_size, 'class' => $img_class ) );
if ( $img == null ) {
	$img['thumbnail'] = '<img class="vc_single_image-img" src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
}

$el_class = $this->getExtraClass( $el_class );
$a_class = '';
if ( $el_class != '' ) {
	$tmp_class = explode( " ", strtolower( $el_class ) );
	$tmp_class = str_replace( ".", "", $tmp_class );
	if ( in_array( "prettyphoto", $tmp_class ) ) {
		wp_enqueue_script( 'prettyphoto' );
		wp_enqueue_style( 'prettyphoto' );
		$a_class  = ' class="prettyphoto"' . ' rel="prettyPhoto[rel-' . get_the_ID() . '-' . rand() . ']"';
		$el_class = str_ireplace( " prettyphoto", "", $el_class );
	}
}

$link_to = '';
if ( $img_link_large == true ) {
	$link_to = wp_get_attachment_image_src( $img_id, 'large' );
	$link_to = $link_to[0];
} else if ( strlen( $link ) > 0 ) {
	$link_to = $link;
} else if ( ! empty( $img_link ) ) {
	$link_to = $img_link;
	if ( ! preg_match( '/^(https?\:\/\/|\/\/)/', $link_to ) ) {
		$link_to = 'http://' . $link_to;
	}
}

// Tag, wrapped the <img> - <div> or <figure> in case of caption used
$img_wrapper_tag = 'div';
$img_wrapper_class = array( 'vc_single_image-wrapper', $style, $border_color );

$img_output = ( $style == 'vc_box_shadow_3d' ) ? '<span class="vc_box_shadow_3d_wrap">' . $img['thumbnail'] . '</span>' : $img['thumbnail'];

// Caption
if ( 'yes' === (string) $is_caption_used ) {
	// Get caption text
	$caption_line = ( ! empty( $caption_line ) ) ? esc_html( $caption_line ) : esc_html( get_post( $img_id )->post_excerpt );

	// Change tag to <figure>
	$img_wrapper_tag = 'figure';

	// Append extra class to <figure>
	$img_wrapper_class[] = $caption_class;

	$caption = "<figcaption>{$caption_line}</figcaption>";

	// Append <figcaption> to the img output
	$img_output .= $caption;
}

// Prepare the Wrapper class set
$img_wrapper_class = appica_get_class_set( $img_wrapper_class );

if ( ! empty( $link_to ) ) {
	// 1 - class, 2 - href, 3 - target, 4 - class set, 5 - img output, 6 - wrapper tag
	$_template = '<a%1$s href="%2$s" target="%3$s"><%6$s class="%4$s">%5$s</%6$s></a>';
	$image_string = sprintf( $_template, $a_class, $link_to, $img_link_target, $img_wrapper_class, $img_output, $img_wrapper_tag );
} else {
	// 1 - img output, 2 - class set, 3 - wrapper tag
	$_template = '<%3$s class="%2$s">%1$s</%3$s>';
	$image_string = sprintf( $_template, $img_output, $img_wrapper_class, $img_wrapper_tag );
}

// Delete unnecessary classes
unset( $_template, $img_wrapper_tag, $img_wrapper_class, $caption );

$css_class = array();
$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_single_image wpb_content_element' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
$css_class[] = $this->getCSSAnimation( $css_animation );
$css_class[] = 'vc_align_' . $alignment;

$css_class = appica_get_class_set( $css_class );

$output .= "\n\t" . '<div class="' . $css_class . '">';
$output .= "\n\t\t" . '<div class="wpb_wrapper">';
$output .= "\n\t\t\t" . wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_singleimage_heading' ) );
$output .= "\n\t\t\t" . $image_string;
$output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
$output .= "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_single_image' );

echo $output;