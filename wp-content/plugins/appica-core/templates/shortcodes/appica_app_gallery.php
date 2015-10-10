<?php
/**
 * Shortcode "App Gallery" output
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

$class = Appica_Helpers::get_class_set( array( 'scroller', 'app-gallery', $a['extra_class'] ) );
$query = new WP_Query( array(
	'post_type'           => 'appica_app_gallery',
	'post_status'         => 'publish',
	'posts_per_page'      => -1,
	'ignore_sticky_posts' => true
) );

if ( $query->have_posts() ) :
	?><div class="<?php echo $class; ?>"><?php

	$tpl = '<a href="%2$s">%1$s</a>';

	$i = 1;
	while( $query->have_posts() ) :
		$query->the_post();
		$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

		$r = $i % 3;

		/*
		 * Show large & small previews
		 *
		 * First item wrap to div.item, each second and third item combine and wrap to div.item together
		 */
		if ( 1 === $r || 2 === $r ) {
			echo "<div class=\"item\">";
		}

		if ( 1 === $r ) {
			// Show large preview
			printf( $tpl, get_the_post_thumbnail( null, 'appica-app-gallery-large' ), $image[0] );
		} else {
			// show small preview
			printf( $tpl, get_the_post_thumbnail( null, 'appica-app-gallery' ), $image[0] );
		}

		if ( 0 === $r || 1 === $r || 0 === $i % $query->post_count ) {
			echo '</div>';
		}

		$i++;
	endwhile;

	?></div><?php
endif;
wp_reset_postdata();