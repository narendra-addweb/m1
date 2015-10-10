<?php
/**
 * Shortcode "News" output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

$a = shortcode_atts( array(
	'more'        => '',
	'extra_class' => ''
), $atts );

$extra = Appica_Helpers::get_class_set( $a['extra_class'] );
$more  = esc_html( $a['more'] );
$query = new WP_Query( array(
	'post_type'           => 'appica_news',
	'post_status'         => 'publish',
	'posts_per_page'      => -1,
	'ignore_sticky_posts' => true
) );

// Extra class wrapper
if ( '' !== $extra ) {
	echo "<div class=\"{$extra}\">";
}

if ( $query->have_posts() ) :

	echo '<div class="row">';
	$i = 0;
	while ( $query->have_posts() ): $query->the_post();

		if ( 0 === $i % 2 ) :
			echo '</div><div class="row">';
		endif; ?>
		<div class="col-sm-6">
			<div class="news-block">
				<span><?php the_time( get_option( 'date_format' ) ); ?></span>
				<a href="<?php the_permalink(); ?>"><?php the_title( '<h3>', '</h3>' ); ?></a>
				<div class="content">
					<?php the_excerpt(); ?>
				</div>
				<a href="<?php the_permalink(); ?>"><?php echo $more; ?></a>
			</div>
		</div>
	<?php
	$i++;
	endwhile;
	echo '</div>'; // close .row

endif;
wp_reset_postdata();

if ( '' !== $extra ) {
	echo '</div>';
}