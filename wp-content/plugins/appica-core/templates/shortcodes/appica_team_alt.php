<?php
/**
 * Shortcode "Team 2" output
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

$classes = Appica_Helpers::get_class_set( array( 'team-grid', 'space-top-2x', $a['extra_class'] ) );

$query = new WP_Query( array(
	'post_type'           => 'appica_team_alt',
	'post_status'         => 'publish',
	'posts_per_page'      => -1,
	'ignore_sticky_posts' => true
) );

if ( $query->have_posts() ) :

	$networks = Appica_Helpers::get_social_networks_list();

	/*
	 * .team-grid
	 */
	printf( '<div class="%s">', $classes );

	while( $query->have_posts() ) :
		$query->the_post();

		// Meta boxes
		$subtitle = get_post_meta( get_the_ID(), '_appica_team_alt_subtitle', true );
		$socials  = get_post_meta( get_the_ID(), '_appica_team_alt_social', true );

		?><div class="item"><?php
		if ( has_post_thumbnail() ) :
			the_post_thumbnail( 'full' );
		endif;
		?><div class="overlay"><?php

		the_title( '<h3>', '</h3>' );
		if ( '' !== $subtitle ) {
			echo "<span>{$subtitle}</span>";
		}

		// socials
		if ( ! empty( $socials ) ) : ?><div class="social-buttons"><?php

			foreach ( (array) $socials as $network => $url ) :
				// 1 - url, 2 - icon class, 3 - helper class
				printf( '<a href="%1$s" class="%3$s" target="_blank"><i class="%2$s"></i></a>', esc_url( $url ), $networks[ $network ]['icon'], $networks[ $network ]['helper'] );
			endforeach;

		?></div><?php
		endif;

		?></div></div><?php
	endwhile;

	echo '</div>'; // end .team-grid

endif;
wp_reset_postdata();

unset( $per_page, $query );