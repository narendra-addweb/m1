<?php
/**
 * Shortcode "Team" output
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
	'post_type'           => 'appica_team',
	'post_status'         => 'publish',
	'posts_per_page'      => -1,
	'ignore_sticky_posts' => true
) );

if ( $query->have_posts() ) :

	$networks = Appica_Helpers::get_social_networks_list();

	// extra classes
	if ( '' !== $classes ) {
		printf( '<div class="%s">', $classes );
	}

	?><div class="row"><?php

	$i = 0;
	while( $query->have_posts() ) :
		$query->the_post();

		// Each 3 entries wrap to .row, but except last
		if ( 0 === $i % 3 && 0 !== $i % $query->post_count ) {
			?></div><div class="row"><?php
		}

		$i++;

		// Meta boxes
		$subtitle = get_post_meta( get_the_ID(), '_appica_team_subtitle', true );
		$socials  = get_post_meta( get_the_ID(), '_appica_team_social', true );

		?><div class="col-sm-4 team-member"><?php
		the_title( '<h3>', '</h3>' );

		if ( '' !== $subtitle ) {
			echo '<span>', $subtitle, '</span>';
		}

		// socials
		if ( ! empty( $socials ) ) : ?><div class="social-buttons"><?php

			foreach ( (array) $socials as $network => $url ) :
				// 1 - url, 2 - icon class, 3 - helper class
				printf( '<a href="%1$s" class="%3$s" target="_blank"><i class="%2$s"></i></a>', esc_url( $url ), $networks[ $network ]['icon'], $networks[ $network ]['helper'] );
			endforeach;

		?></div><?php endif;

		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'medium' );
		}

		?></div><?php // end .col-sm-4.team-member

	endwhile;

	?></div><?php // end .row

	if ( '' !== $classes ) {
		echo '</div>'; // end extra classes
	}

endif;
wp_reset_postdata();

unset( $per_page, $query );