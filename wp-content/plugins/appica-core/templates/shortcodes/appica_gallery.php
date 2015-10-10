<?php
/**
 * Shortcode "Gallery" output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

$a = shortcode_atts( array(
	'title'       => '',
	'subtitle'    => '',
	'is_cat'      => 'yes', // yes | no
	'extra_class' => ''
), $atts );

$title    = ( '' === $a['title'] ) ? '' : "<h2>{$a['title']}</h2>";
$subtitle = ( '' === $a['subtitle'] ) ? '' : "<p>{$a['subtitle']}</p>";
$is_cat   = ( 'yes' === $a['is_cat'] );
$heading  = '';
$filters  = '';
$tax      = 'appica_gallery_category';
$classes  = Appica_Helpers::get_class_set( $a['extra_class'] );

// Block heading
if ( '' !== $title || '' !== $subtitle ) {
	$heading = sprintf( '<div class="col-lg-4 col-lg-push-8 col-md-5 col-md-push-7 col-sm-6 col-sm-push-6"><div class="block-heading">%1$s%2$s</div></div>', $title, $subtitle );
}

// Filters
if ( $is_cat ) {
	$categories = get_terms( $tax, array( 'hierarchical' => false ) );
	if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
		$filters .= '<div class="col-lg-8 col-lg-pull-4 col-md-7 col-md-pull-5 col-sm-6 col-sm-pull-6"><ul class="nav-filters text-right space-top-3x"><li class="active"><a data-filter="*" href="#">All</a></li>';
		foreach ( (array) $categories as $category ) {
			$filters .= sprintf( '<li><a data-filter=".%1$s" href="#">%2$s</a></li>', $category->slug, $category->name );
		}
		$filters .= '</ul></div>';
	endif;
}

$query = new WP_Query( array(
	'post_type'           => 'appica_gallery',
	'post_status'         => 'publish',
	'posts_per_page'      => -1,
	'ignore_sticky_posts' => true
) );

// wrapper
if ( '' !== $classes ) {
	echo "<div class=\"{$classes}\">";
}

// heading
if ( '' !== $heading || '' !== $filters ) {
	echo '<div class="row">', $heading, $filters, '</div>';
}
unset( $heading, $filters );

// grid
if ( $query->have_posts() ) : ?>

<div class="masonry-grid filter-grid space-top-2x">
	<div class="grid-sizer"></div>
	<div class="gutter-sizer"></div>

	<?php while( $query->have_posts() ):
		$query->the_post();

		if ( ! has_post_thumbnail() ) {
			continue;
		}

		$categories = Appica_Helpers::get_post_terms( get_the_ID(), $tax );

		$figure = sprintf(
			'<figure>%2$s<figcaption class="title-only"><h3>%1$s</h3></figcaption></figure>',
			get_the_title(), get_the_post_thumbnail( null, 'medium' )
		);

		$video = get_post_meta( get_the_ID(), '_appica_gallery_video', true );
		$image = '';
		$item  = '<a href="%1$s" class="gallery-item %3$s">%2$s</a>';

		printf( '<div class="item %s">', Appica_Helpers::get_class_set( $categories ) );
		if ( '' !== $video ) {
			printf( $item, $video, $figure, 'video-item' );
		} else {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			printf( $item, $image[0], $figure, 'image-item' );
		}

		echo '</div>';
		unset( $categories, $figure, $video, $image, $item );

	endwhile; ?></div><?php

endif;
wp_reset_postdata();

// .end wrapper
if ( '' !== $classes ) {
	echo '</div>';
}