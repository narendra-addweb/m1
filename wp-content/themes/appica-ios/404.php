<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Appica2
 */

get_header(); ?>

<section class="not-found padding-bottom">
	<div class="container text-center text-gray padding-top-3x padding-bottom-3x">

		<div class="error-404 space-bottom-2x">404</div>
		<h3 class="space-bottom"><?php _e( 'Oops! That page can&rsquo;t be found', 'appica' ); ?></h3>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php _e( 'Go to Home', 'appica' ); ?></a>

	</div>
</section>

<?php get_footer(); ?>