<?php
/**
 * The template for displaying all pages.
 *
 * @package Appica
 */

get_header();

appica_custom_page_title( true, true );

while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( appica_get_page_wrapper() ); ?>>

		<?php the_content(); ?>

	</article><?php

endwhile; // end of the loop.

get_footer();
