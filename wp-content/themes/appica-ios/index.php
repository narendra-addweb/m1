<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * According to ThemeForest submission requirements this template
 * is reserved for standard blog "latest posts" view.
 *
 * @package Appica2
 */

get_header(); ?>

<section class="space-top padding-bottom">
	<div class="container">

		<?php if ( have_posts() ) : ?>

			<div class="masonry-grid">
				<div class="grid-sizer"></div>
				<div class="gutter-sizer"></div>

				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'content', 'home' );

				endwhile;
				?>

			</div>

			<?php the_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>


	</div>
</section>

<?php get_footer(); ?>
