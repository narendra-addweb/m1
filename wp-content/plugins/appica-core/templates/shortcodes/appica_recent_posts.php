<?php
/**
 * Shortcode "Recent Posts" output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

$a = shortcode_atts( array(
	'per_page'    => '',
	'is_excerpt'  => '', // yes | no
	'img_size'    => '',
	'extra_class' => ''
), $atts );

$per_page   = ( 'all' === $a['per_page'] ) ? -1 : absint( $a['per_page'] );
$is_excerpt = ( 'yes' === $a['is_excerpt'] );
$img_size   = Appica_Helpers::get_image_size( $a['img_size'] );
$classes    = Appica_Helpers::get_class_set( array( 'scroller', 'posts', $a['extra_class'] ) );

$query = new WP_Query( array(
	'posts_per_page'      => $per_page,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true
) );

/*
 * Start output
 */
if ( $query->have_posts() ) :
	printf( '<div class="%1$s">', $classes );

	/**
	 * Add the temporary filter to change the excerpt length.
	 * Not need in other places, so filter will be removed after WP_Query Loop.
	 *
	 * @since 1.0.0
	 */
	add_filter( 'excerpt_length', array( 'Appica_Filters', 'excerpt_length' ) );
	/**
	 * Temporary filter to trim the length of excerpt, if custom excerpt specified.
	 * Remove after WP_Query Loop
	 *
	 * @since 1.0.0
	 */
	add_filter( 'wp_trim_excerpt', array( 'Appica_Filters', 'trim_excerpt' ) );

	while ( $query->have_posts() ) : $query->the_post(); ?>
		<div class="item">
			<div class="post-tile">
				<?php if( has_post_thumbnail() ) : ?>
					<a href="<?php the_permalink(); ?>" class="post-thumb colored">
						<?php the_post_thumbnail( $img_size ); ?>
					</a>
				<?php endif; ?>

				<div class="post-body">
					<div class="post-title">
						<a href="<?php the_permalink(); ?>"><?php the_title( '<h3>', '</h3>' ); ?></a>
						<?php if ( $is_excerpt ) : the_excerpt(); endif; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endwhile;
	wp_reset_postdata();

	remove_filter( 'excerpt_length', array( 'Appica_Filters', 'excerpt_length' ) );
	remove_filter( 'wp_trim_excerpt', array( 'Appica_Filters', 'trim_excerpt' ) );

	echo '</div>';
endif;