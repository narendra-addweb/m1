<?php
/**
 * Shortcode "Timeline" output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

$a = shortcode_atts( array(
	'title'       => '',
	'description' => '',
	'extra_class' => ''
), $atts );

$title = esc_html( $a['title'] );
$desc  = strip_tags( $a['description'], '<p></p>' );

$classes = Appica_Helpers::get_class_set( array( 'timeline', 'space-top-2x', 'space-bottom-3x', $a['extra_class'] ) );

// Print title & description
echo "<h1>{$title}</h1>", "<div class=\"text-light\"><p>{$desc}</p></div>";

// Get timeline
$query = new WP_Query( array(
	'post_type'           => 'appica_timeline',
	'post_status'         => 'publish',
	'posts_per_page'      => -1,
	'ignore_sticky_posts' => true
) );

if( $query->have_posts() ) :
	// 1 - date, 2 - title
	$tpl = '<div class="timeline-row"><div class="date">%1$s</div><div class="event"><p>%2$s</p></div></div>';
	// start .timeline
	echo "<div class=\"{$classes}\">";
	while( $query->have_posts() ): $query->the_post();

		$date = get_post_meta( get_the_ID(), '_appica_timeline_date', true );
		printf( $tpl, $date, get_the_title() );

	endwhile;
	// end .timeline
	echo '</div>';
	unset( $tpl, $date );
endif;
wp_reset_postdata();