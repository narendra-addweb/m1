<?php
/**
 * The template for displaying all single posts.
 *
 * @package Appica
 */

get_header(); ?>

	<section class="space-top-2x padding-bottom appica-single-post appica-portfolio">
		<div class="container">
			<div class="row">

				<div class="<?php appica_content_column_classes( 'none' ); ?>">
					<?php while ( have_posts() ) :
						the_post();

						get_template_part( 'content', 'single' );

					endwhile; // end of the loop. ?>
				</div>

			</div>
		</div>
	</section>

<?php get_footer();
