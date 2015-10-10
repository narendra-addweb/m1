<?php
/**
 * Shortcode vc_tabs custom output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Visual Composer
 */

$a = shortcode_atts( array(
	'title'       => '',
	'subtitle'    => '',
	'description' => '',
	'text_align'  => 'left', // left | center | right
	'position'    => 'left', // left | right
	'el_class'    => ''
), $atts );

$tabs_column = $tabs_content = '';

$el_class = appica_get_class_set( $a['el_class'] );
$is_tour  = ( 'vc_tour' === $this->shortcode );
$align    = $a['text_align'];
/**
 * @var bool If content has "left" position
 */
$is_left = ( 'left' === $a['position'] );

/*
 * Global shortcode wrapper
 */
if ( '' !== $el_class ) {
	echo "<div class=\"{$el_class}\">";
}

/**
 * Classes for tab navigation column.
 * Only for vc_tour: Add offset to tabs column, if content has "left" position
 *
 * @var array
 */
$tabs_column_set_class = array( 'col-sm-6', "text-{$align}" );
if ( $is_tour ) {
	$tabs_column_set_class[0] = 'col-md-3';
	$tabs_column_set_class[] = 'col-sm-4';
	// if content has left position, we have to add ofset to tabs column
	$tabs_column_set_class[] = ( $is_left ) ? 'col-lg-offset-1' : '';
}

$tabs_column_col_class = appica_get_class_set( $tabs_column_set_class );
$tabs_column .= sprintf( '<div class="%s">', $tabs_column_col_class );

/*
 * Add block heading
 */
if ( '' !== $a['title'] || '' !== $a['subtitle'] ) {
	$tabs_column .= '<div class="block-heading">';
	$tabs_column .= ( '' === $a['title'] ) ? '' : sprintf( '<h2>%s</h2>', esc_html( $a['title'] ) );
	$tabs_column .= ( '' === $a['subtitle'] ) ? '' : sprintf( '<span>%s</span>', esc_html( $a['subtitle'] ) );
	$tabs_column .= '</div>';
}

/*
 * Add description
 */
if ( '' !== $a['description'] ) {
	$description = esc_html( $a['description'] );
	$tabs_column .= "<p class=\"space-bottom\">{$description}</p>";

	unset( $description );
}

/**
 * @var array Classes for ul.nav-tabs
 */
$tabs_column_nav_set = array( 'nav-tabs' );
if ( $is_tour ) {
	$tabs_column_nav_set[] = 'nav-vertical';
	$tabs_column_nav_set[] = 'space-top-2x';
}

/*
 * Tabs navigation
 */
$tabs_column .= appica_get_tabs_nav( $content, appica_get_class_set( $tabs_column_nav_set ) );
$tabs_column .= '</div>'; // end tabs column, close .col-*

unset( $tabs_column_set_class, $tabs_column_col_class, $tabs_column_nav_set );

/**
 * Classes for tabs content column.
 * Only for vc_tour: add offset to content column, if content has "right" position
 *
 * @var array
 */
$tabs_content_set_class = array( 'col-sm-6' );
if ( $is_tour ) {
	$tabs_content_set_class[0] = 'col-lg-8';
	$tabs_content_set_class[]  = 'col-md-9';
	$tabs_content_set_class[]  = 'col-sm-8';
	// add offset for "right" content position
	$tabs_content_set_class[]  = ( $is_left ) ? '' : 'col-lg-offset-1';
}
/**
 * Class set for content column.
 * Add offset if content has "right" position.
 *
 * @var string
 */
$tabs_content_col_class = appica_get_class_set( $tabs_content_set_class );
$tabs_content .= sprintf( '<div class="%s">', $tabs_content_col_class );
$tabs_content .= '<div class="tab-content">';
$tabs_content .= wpb_js_remove_wpautop( $content );
$tabs_content .= '</div>';
$tabs_content .= '</div>'; // end tabs content column, close .col-*

if ( $is_left ) {
	echo $tabs_content, $tabs_column;
} else {
	echo $tabs_column, $tabs_content;
}

/*
 * Close wrapper for extra class
 */
if ( '' !== $el_class ) {
	echo '</div>';
}