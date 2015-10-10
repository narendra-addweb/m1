<?php
/**
 * Single portfolio tile template
 *
 * @package Appica
 * @since   1.3.0
 */

$post_id  = get_the_ID();
$thumb_id = (int) get_post_thumbnail_id();

$categories  = appica_get_post_terms( $post_id, 'appica_portfolio_category' );
$image_full  = wp_get_attachment_image_src( $thumb_id, 'full' );
$image_large = wp_get_attachment_image_src( $thumb_id, 'large' );

$image_full_url  = esc_url( $image_full[0] );
$image_large_url = esc_url( $image_large[0] );
unset( $image_full, $image_large );

// Tile meta box
$tile = get_post_meta( $post_id, '_appica_portfolio_tile', true );

$cat_classes  = appica_get_class_set( $categories );
$tile_classes = array( 'grid-item', $cat_classes );
unset( $cat_classes );

/**
 * Associated tile formats and classes
 * @var array
 */
$formats = array(
	'default'   => '',
	'wide'      => 'w2',
	'king-size' => 'w2 h2',
);

// format extra classes
$format = 'default';
if ( array_key_exists( 'format', $tile )
     && array_key_exists( $tile['format'], $formats )
) {
	$format = (string) $tile['format'];
	$tile_classes[] = $formats[ $format ];
}

$tile_classes = appica_get_class_set( $tile_classes );

?><div class="<?php echo $tile_classes; ?>">
	<div class="portfolio-tile">
		<div class="tile-thumb" style="background-image: url(<?php echo $image_large_url; ?>);">
			<div class="overlay">
				<?php if ( 'default' === $format ) : ?>
					<div class="btns">
						<a href="<?php echo $image_full_url; ?>" class="popup-img"><i class="pe-7s-search"></i></a>
						<a href="<?php the_permalink(); ?>"><i class="pe-7s-link"></i></a>
					</div>
				<?php else: ?>
					<div class="inner">
						<div class="btns">
							<a href="<?php echo $image_full_url; ?>" class="popup-img"><i class="pe-7s-search"></i></a>
							<a href="<?php the_permalink(); ?>"><i class="pe-7s-link"></i></a>
						</div>
						<?php the_title( '<h2>', '</h2>' ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div><?php

unset( $post_id, $thumb_id, $categories, $image_full_url, $image_large_url, $tile, $tile_classes, $format );