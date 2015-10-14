<?php
/**
 * The template for displaying the footer.
 *
 * @package Appica 2
 */
?>

<?php
/*
 * If intro is enabled close /div.content-wrap tag
 */
if ( appica_is_intro() ) : ?>
</div>
<?php endif; ?>

<footer class="footer">
	<div class="container">

		<?php if ( appica_is_footer_app() ) : ?>
		<div class="footer-head padding-top-3x">
			<?php if ( appica_is_footer_logo() ) : ?>
			<div class="logo">
				<?php appica_footer_logo(); ?>
			</div>
			<?php endif; ?>
			<div class="info">
				<?php appica_footer_app_name(); ?>
				<?php appica_footer_app_tagline(); ?>
				<?php appica_footer_app_content_rating(); ?>
				<?php appica_footer_app_rating(); ?>
			</div>
		</div>
		<?php endif; ?>

		<div class="body padding-top-2x">
			<?php $appica_is_device = appica_is_footer_device(); ?>

			<div class="column copyright <?php if ( false === $appica_is_device ) : echo 'col-50'; endif; ?>">
				<?php appica_the_copyright(); ?>
			</div>

			<?php if ( $appica_is_device && appica_is_footer_device_screen() ) : ?>
			<div class="column hidden-sm hidden-xs">
				<div class="gadget">
					<?php appica_footer_device_screen(); ?>
				</div>
			</div>
			<?php endif; ?>

			<div class="column footer-nav <?php if ( false === $appica_is_device ) : echo 'col-50'; endif; ?>">
			<?php if ( appica_is_footer_nav() ) :
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => false,
					'fallback_cb'    => false,
					'depth'          => -1,
					'items_wrap'     => '<ul>%3$s</ul>'
				) );
			endif; ?>
			</div>
			<div class="column footer-nav footer-social">
				<?php/* appica_intro_socials(); */?>
			</div>
			<?php unset( $appica_is_device ); ?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
