<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Appica
 */

get_header(); ?>

<section class="space-top padding-bottom">
	<div class="container">

		<?php if ( have_posts() ) : ?>

			<div class="page-heading">
				<?php
				the_archive_title( '<h2>', '</h2>' );
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</div>

			<div class="masonry-grid">
				<div class="grid-sizer"></div>
				<div class="gutter-sizer"></div>

				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'content' );

				endwhile;
				?>

			</div>

			<?php appica_paginate_links(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>


	</div>
</section>

<?php get_footer(); ?>
