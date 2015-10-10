<?php
/**
 * The template for displaying search results pages.
 *
 * @package Appica 2
 */

get_header(); ?>

<div class="page-heading text-right">
	<div class="container">
		<?php get_search_form(); ?>
		<h2><?php _e( 'Search results', 'appica' ); ?></h2>
	</div>
</div>

<section class="space-top padding-bottom">
	<div class="container">

		<?php if ( have_posts() ) : ?>

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
