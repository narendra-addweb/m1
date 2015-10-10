<div class="offcanvas-nav">

	<div class="nav-head">

		<div class="top-bar">
			<div class="nav-toggle" data-offcanvas="close"><i class="flaticon-list26"></i></div>

			<?php if ( appica_is_offcanvas_search() ) : ?>
			<form role="search" method="get" class="search-box" action="<?php echo home_url( '/' ); ?>">
				<span class="search-toggle search-icon"></span>
				<input type="hidden" name="post_type" value="post">
				<input type="text" name="s" class="search-field" id="search-field" value="<?php the_search_query(); ?>"
				       placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'appica' ); ?>">
				<button type="submit" class="search-btn"><i class="search-icon"></i></button>
			</form>
			<?php endif; ?>

			<?php appica_offcanvas_socials(); ?>
		</div>

		<?php appica_offcanvas_logo(); ?>
		<?php appica_offcanvas_button(); ?>
		<?php appica_offcanvas_subscribe(); ?>
	</div>

	<div class="nav-body">
		<div class="overflow">
			<div class="inner">

				<!-- Navigation -->
				<nav class="nav-link">

					<?php
					$anchor_menu_args = array(
						'theme_location' => 'anchor',
						'container'      => false,
						'fallback_cb'    => false,
						'depth'          => -1, // flat list
						'walker'         => new Appica_Anchor_Menu_Walker()
					);

					// Anchor menu on Front Page requires container for scroll spy
					if ( is_front_page() ) {
						$anchor_menu_args = array_merge( $anchor_menu_args, array(
							'container'       => 'div',
							'container_class' => 'scroll-nav'
						) );
					}

					// Anchored menu
					wp_nav_menu( $anchor_menu_args );
					unset( $anchor_menu_args );

					// Paged menu
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false,
						'fallback_cb'    => false,
						'depth'          => -1,
						'menu_class'     => 'pages'
					) );
					?>

				</nav>

				<?php
				/**
				 * Dynamic sidebar inside off-canvas navi
				 *
				 * @since 1.0.0
				 */
				if ( is_active_sidebar( 'sidebar-offcanvas' ) ) :
					dynamic_sidebar( 'sidebar-offcanvas' );
				endif; ?>

			</div>
		</div>
	</div>
</div><!-- Off-canvas Navigation End -->