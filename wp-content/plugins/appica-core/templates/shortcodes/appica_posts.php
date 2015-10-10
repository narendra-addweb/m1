<?php
/**
 * Shortcode "Posts" output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

if ( ! function_exists( 'vc_path_dir' ) || ! function_exists( 'vc_build_loop_query' ) ) {
	return;
}

$a = shortcode_atts( array(
	'loop'        => '',
	'extra_class' => ''
), $atts );

$extra = Appica_Helpers::get_class_set( $a['extra_class'] );

if ( '' === $a['loop'] ) {
	return;
}

require_once vc_path_dir( 'PARAMS_DIR', 'loop/loop.php' );
list( $loop_args, $query ) = vc_build_loop_query( $a['loop'] );

if ( $query->have_posts() ) : ?>

	<div class="masonry-grid">
		<div class="grid-sizer"></div>
		<div class="gutter-sizer"></div>

		<?php while ( $query->have_posts() ) : $query->the_post(); ?>

			<div id="post-<?php the_ID(); ?>" <?php post_class( 'item' ); ?>>
				<div class="post-tile">
					<?php if ( Appica_Helpers::has_featured_video() ) : ?>
						<div class="embed-responsive embed-responsive-16by9">
							<?php Appica_Helpers::the_featured_video(); ?>
						</div>
					<?php elseif ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>" class="post-thumb">
							<?php the_post_thumbnail( 'appica-home-thumbnail' ); ?>
						</a>
					<?php endif; ?>
					<div class="post-body">
						<div class="post-title">
							<a href="<?php the_permalink(); ?>" rel="bookmark">
								<?php the_title( '<h3>', '</h3>' ); ?>
							</a>
							<?php the_excerpt(); ?>
						</div>

						<?php Appica_Helpers::entry_footer_wo_social(); ?>

					</div>
				</div>
			</div>

		<?php
		unset( $post_id, $is_wide );
		endwhile;
		?>

	</div>

<?php endif;
wp_reset_postdata();
