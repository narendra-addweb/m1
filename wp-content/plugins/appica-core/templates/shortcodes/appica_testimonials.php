<?php
/**
 * Shortcode "Testimonials" output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

$a = shortcode_atts( array(
	'extra_class' => ''
), $atts );

$classes = Appica_Helpers::get_class_set( $a['extra_class'] );

$query = new WP_Query( array(
	'post_type'           => 'appica_testimonials',
	'post_status'         => 'publish',
	'posts_per_page'      => -1,
	'ignore_sticky_posts' => true
) );

if ( $query->have_posts() ) :
	$i = 0;

	// extra classes
	if ( '' !== $classes ) {
		printf( '<div class="%s">', $classes );
	}

	?><div class="row"><?php

	while( $query->have_posts() ) : $query->the_post();

		// Each 3 entries wrap to .row, but except last
		if ( 0 === $i % 3 && 0 !== $i % $query->post_count ) {
			?></div><div class="row"><?php
		}

		$i++;

		?><div class="col-sm-4"><div class="press-review"><?php
			if ( has_post_thumbnail() ) :
				the_post_thumbnail( 'full' );
			endif;

			the_title( '<h3>', '</h3>' );

			the_excerpt();
		?></div></div><?php // end .press-review & .col-sm-4

	endwhile;

	?></div><?php // end .row

	if ( '' !== $classes ) {
		echo '</div>'; // end extra classes
	}

endif;
wp_reset_postdata();

unset( $per_page, $query );