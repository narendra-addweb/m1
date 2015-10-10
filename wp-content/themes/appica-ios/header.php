<?php
/**
 * The header for our theme
 *
 * @package Appica 2
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php appica_favicon(); ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class( appica_body_extra_class() ); ?>>

	<?php appica_preloader(); ?>

	<div class="fake-scrollbar"></div>

	<?php get_sidebar( 'off-canvas' ); ?>

	<?php appica_subscribe_modal_form(); ?>

	<?php
	/*
	 * Intro section
	 *
	 * If intro is enabled also open div.content-wrap before <header>
	 * and close it before <footer>
	 */
	if ( appica_is_intro() ) :

		// Check the type of intro screen
		if ( 'revslider' === appica_intro_type() ) : ?>

			<section class="intro-slider">
				<div class="container">
					<div class="column-wrap">
						<div class="column c-left">
							<?php appica_intro_socials(); ?>
						</div>
						<div class="column c-middle"></div>
						<div class="column c-right">
							<div class="navi">
								<?php appica_intro_subscribe(); ?>
								<div class="nav-toggle" data-offcanvas="open"><i class="flaticon-list26"></i></div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<?php appica_intro_revslider(); ?>

		<?php else: ?>

			<section class="intro" <?php appica_intro_background(); ?>>

				<?php if ( appica_is_intro_gradient() ) : ?>
					<div class="gradient"></div>
				<?php endif; ?>

				<div class="container">
					<div class="column-wrap">
						<div class="column c-left">
							<?php appica_intro_socials(); ?>
							<?php appica_intro_scroll(); ?>
						</div>

						<div class="column c-middle">
							<div class="beforeLogo"></div>
							<?php appica_intro_logo(); ?>
							<div class="afterLogo"></div>
							<?php //appica_intro_screen(); ?>
							<div class="slideCont phone">
								<div class="logoupper" style="display:none;"><?php
									$logo     = appica_get_option( 'intro_logo' );
									$title    = appica_get_option( 'intro_title' );
									$subtitle = appica_get_option( 'intro_subtitle' );

									if ( is_array( $logo ) && array_key_exists( 'url', $logo ) && '' !== $logo['url'] ) {
										printf( '<img src="%s">', $logo['url'] );
									}
								?></div>
								<div class="slideTextContent">
									<h3>Changing The Way The World Orders</h3>
									<p>m1-order enables print and mobile commerce for your business</p>
									<h4>Create <strong>Store.</strong> Share <strong>Link.</strong> Get <strong>Orders.</strong></h4>
								</div>
								<div class="slideVideoButton"><?php
								/*
									Video presentation...
								*/
								echo do_shortcode('[appica_video_popup text="" video="http://vimeo.com/113575647"][vc_row el_class="fw-gray-bg padding-top-3x padding-bottom-3x" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="default" badge_tc="#bebebe" content_color="dark" uniq_id="posts" is_container="yes" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70"]');
								?></div>
							</div>
						</div>

						<div class="column c-right">
							<div class="navi">
								<?php appica_intro_subscribe(); ?>
								<div class="nav-toggle" data-offcanvas="open"><i class="flaticon-list26"></i></div>
							</div>

							<?php appica_intro_features(); ?>
							<?php appica_intro_download(); ?>
						</div>
					</div>
				</div>
			</section>

		<?php endif; // end check intro type ?>

	<div class="content-wrap">
	<?php endif; // end check is_intro ?>

	<header class="<?php appica_the_sticky_navbar(); ?>">
		<div class="container">

			<?php appica_navbar_logo(); ?>
			<?php //appica_navbar_socials(); ?>

			<div class="toolbar">
				<?php appica_navbar_download_button(); ?>
				<?php appica_navbar_subscribe(); ?>
				<div class="nav-toggle" data-offcanvas="open"><i class="flaticon-list26"></i></div>
			</div>

		</div>
	</header>
