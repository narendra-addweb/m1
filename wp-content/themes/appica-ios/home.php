<?php
/**
 * Blog Posts Index template
 *
 * @package Appica
 */

get_header();
$appica_title = appica_blog_page_title( false ); ?>

<div class="page-heading text-right <?php appica_is_page_heading( $appica_title, true ); ?>">
	<div class="container">
		<?php get_search_form(); ?>
		<?php echo $appica_title; unset( $appica_title ); ?>
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

					get_template_part( 'content', 'home' );

				endwhile;
				?>

			</div>

			<?php appica_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>


	</div>
</section>

<?php get_footer(); ?>
