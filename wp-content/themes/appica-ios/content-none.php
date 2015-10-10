<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Appica2
 */
?>

<div class="no-results not-found text-center text-gray padding-top-3x padding-bottom-3x">

	<h3 class="space-bottom"><?php _e( 'Nothing Found', 'appica' ); ?></h3>

	<div class="page-content space-bottom-2x">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'appica' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'appica' ); ?></p>

		<?php else : ?>

			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'appica' ); ?></p>

		<?php endif; ?>
	</div><!-- .page-content -->

</div>
