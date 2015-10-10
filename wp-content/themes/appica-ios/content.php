<?php
/**
 * The template part for displaying posts in archive pages
 * or results in search pages, etc.
 *
 * @package Appica
 */
?><div id="post-<?php the_ID(); ?>" <?php post_class( 'item' ); ?>>
	<div class="post-tile">
		<?php if ( has_post_thumbnail() ) : ?>
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

			<?php appica_entry_footer_wo_social(); ?>

		</div>
	</div>
</div>