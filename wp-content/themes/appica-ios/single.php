<?php
/**
 * The template for displaying all single posts.
 *
 * @package Appica
 */

get_header();

$appica_is_search = appica_is_search();
$appica_title     = appica_custom_post_title( false ); ?>

<div class="page-heading text-right <?php appica_is_page_heading( $appica_title, $appica_is_search ); ?>">
	<div class="container">
		<?php
		if ( $appica_is_search ) :
			get_search_form();
		endif;

		echo $appica_title;
		?>
	</div>
</div>

<section class="space-top padding-bottom appica-single-post">
	<div class="container">
		<div class="row">

			<?php
			// Sidebar magic
			$appica_sidebar_position = appica_sidebar_position();
			?>

			<div class="<?php appica_content_column_classes( $appica_sidebar_position ); ?>">
			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'content', 'single' );

				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // end of the loop.
			?>
			</div>

			<?php appica_post_sidebar( null, $appica_sidebar_position ); ?>

		</div>
	</div>
</section>

<?php
unset( $appica_title, $appica_is_search, $appica_sidebar_position );
get_footer();
