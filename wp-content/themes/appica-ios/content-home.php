<?php
/**
 * Single post template on Blog Home (Posts index) page
 *
 * @package Appica
 */

$post_id = get_the_ID();
$is_wide = appica_is_wide_post( $post_id ); ?>
<div id="post-<?php echo $post_id; ?>" <?php post_class( appica_get_post_class_set( $is_wide ) ); ?>>
	<div class="post-tile">
		<?php if ( appica_has_featured_video() ) : ?>
			<div class="embed-responsive embed-responsive-16by9">
				<?php appica_the_featured_video(); ?>
			</div>
		<?php elseif ( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>" class="post-thumb">
				<?php the_post_thumbnail( ( $is_wide ) ? 'appica-home-thumbnail-double' : 'appica-home-thumbnail' ); ?>
			</a>
		<?php endif; ?>
		<div class="post-body">
			<div class="post-title">
				<a href="<?php the_permalink(); ?>" rel="bookmark">
					<?php the_title( '<h3>', '</h3>' ); ?>
				</a>
				<?php the_excerpt(); ?>
			</div>

			<?php appica_entry_footer_wo_social(); ?>

		</div>
	</div>
</div>
<?php unset( $post_id, $is_wide );