<?php
/**
 * Appica2 functions and definitions
 *
 * @package Appica2
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 750; /* pixels */
}

$appica_template_directory = get_template_directory();

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once "{$appica_template_directory}/vendor/tgm/class-tgm-plugin-activation.php";

/**
 * Theme helper functions
 */
require "{$appica_template_directory}/inc/helpers.php";

/**
 * Theme custom Template Tags
 */
require "{$appica_template_directory}/inc/template-tags.php";

if ( ! function_exists( 'appica_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function appica_setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Appica2, use a find and replace
		 * to change 'appica' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'appica', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form to output valid HTML5.
		 */
		add_theme_support( 'html5', array( 'search-form' ) );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'anchor'  => __( 'Anchored Menu', 'appica' ),
			'primary' => __( 'Primary Menu', 'appica' ),
			'footer'  => __( 'Footer Menu', 'appica' )
		) );

		/*
		 * Add some extra image sizes.
		 * This image sizes uses only for images preview on blog posts index page
		 */
		add_image_size( 'appica-home-thumbnail-double', 770, 240, true );
		add_image_size( 'appica-home-thumbnail', 440, 371, true );
	}

	add_action( 'after_setup_theme', 'appica_setup' );

endif; // appica_setup

if ( ! function_exists( 'appica_widgets_init' ) ) :
	/**
	 * Register widget area. And unregister some default widgets.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
	 */
	function appica_widgets_init() {

		register_sidebar( array(
			'name'          => __( 'Sidebar in Blog', 'appica' ),
			'id'            => 'sidebar-blog',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );

		register_sidebar( array(
			'name'          => __( 'Off-Canvas Sidebar', 'appica' ),
			'id'            => 'sidebar-offcanvas',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget widget-offcanvas %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );

		register_sidebar( array(
			'name'          => __( 'Custom sidebar', 'appica' ),
			'id'            => 'custom-sidebar',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );

		register_widget( 'Appica_Widget_Recent_Posts' );
		register_widget( 'Appica_Widget_Categories' );
		register_widget( 'Appica_Widget_Twitter_Feed' );
		register_widget( 'Appica_Widget_Twitter_Blog_Feed' );
		register_widget( 'Appica_Widget_Offcanvas_Blog' );
		register_widget( 'Appica_Widget_Flickr_Feed' );
	}

	add_action( 'widgets_init', 'appica_widgets_init' );

endif; // appica_widgets_init

if ( ! function_exists( 'appica_scripts' ) ) :
	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.0.0
	 */
	function appica_scripts() {
		$template_directory_uri = get_template_directory_uri();

		/**
		 * @var array Theme CSS dependencies
		 */
		$style_deps = array();

		if ( appica_is_google_font()
		     && '' !== ( $google_font = appica_get_option( 'typography_google_font' ) )
		) {
			$google_font = ltrim( $google_font, 'http:' );
			wp_register_style( 'appica-google-font', $google_font, array(), null, 'screen' );
			$style_deps[] = 'appica-google-font';
		}

		wp_register_style( 'appica-style', $template_directory_uri . '/css/style.css', array(), null, 'screen' );
		$style_deps[] = 'appica-style';

		// enqueue theme main style.css file
		wp_enqueue_style( 'appica', get_stylesheet_uri(), $style_deps, null );

		// enqueue theme main m1order.css file
		wp_register_style( 'm1order', $template_directory_uri . '/css/m1order.css' );
 		wp_enqueue_style( 'm1order');
 		
 		// enqueue theme main m1order.js file
 		wp_register_script( 'm1order', $template_directory_uri . '/js/m1order.js ');
 		wp_enqueue_script( 'm1order');

		// scripts in <head>
		wp_enqueue_script( 'appica-pace', $template_directory_uri . '/js/plugins/pace.min.js', array(), null );
		wp_enqueue_script( 'appica-modernizr', $template_directory_uri . '/js/libs/modernizr.custom.js', array(), null );
		wp_enqueue_script( 'appica-detectizr', $template_directory_uri . '/js/libs/detectizr.min.js', array(), null );

		// scripts in footer
		wp_register_script( 'appica-easing', $template_directory_uri . '/js/libs/jquery.easing.1.3.js', array( 'jquery' ), null, true );
		wp_register_script( 'appica-velocity', $template_directory_uri . '/js/plugins/velocity.min.js', array( 'jquery' ), null, true );
		wp_register_script( 'appica-bootstrap', $template_directory_uri . '/js/plugins/bootstrap.min.js', array( 'jquery' ), null, true );
		wp_register_script( 'appica-smoothscroll', $template_directory_uri . '/js/plugins/smoothscroll.js', array(), null, true );
		wp_register_script( 'appica-form', $template_directory_uri . '/js/plugins/form-plugins.js', array( 'jquery' ), null, true );
		wp_register_script( 'appica-scrollbar', $template_directory_uri . '/js/plugins/jquery.mCustomScrollbar.min.js', array( 'jquery' ), null, true );
		wp_register_script( 'appica-isotope', $template_directory_uri . '/js/plugins/isotope.pkgd.min.js', array( 'jquery' ), null, true );
		wp_register_script( 'appica-waypoints', $template_directory_uri . '/js/plugins/jquery.waypoints.min.js', array( 'jquery' ), null, true );

		wp_enqueue_script( 'appica', $template_directory_uri . '/js/scripts.js', array(
			'jquery',
			'appica-easing',
			'appica-velocity',
			'appica-bootstrap',
			'appica-smoothscroll',
			'appica-form',
			'appica-scrollbar',
			'appica-isotope',
			'appica-waypoints'
		), null, true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Nonce and ajaxurl for AJAX calls
		wp_localize_script( 'appica', 'appica', array(
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
			'nonce'     => wp_create_nonce( 'appica-ajax' ),
			'masonry'   => '', // for masonry container,
			'portfolio' => '', // for portfolio masonry container
		) );

		$head_css = appica_get_head_css();
		wp_add_inline_style( 'appica', $head_css );
	}

	add_action( 'wp_enqueue_scripts', 'appica_scripts', 11 );

endif; // appica_scripts

/**
 * Register the required plugins for this theme.
 *
 * @author 8guild
 */
function appica_tgm_init() {

	$template_directory = get_template_directory();

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array(
			'name'     => 'Appica Core',
			'slug'     => 'appica-core',
			'source'   => wp_normalize_path( "{$template_directory}/plugins/appica-core.zip" ),
			'required' => true,
			'version'  => '1.3.0',
		),
		array(
			'name'     => 'Visual Composer',
			'slug'     => 'js_composer',
			'source'   => wp_normalize_path( "{$template_directory}/plugins/js_composer.zip" ),
			'required' => true,
			'version'  => '4.5.3'
		),
		array(
			'name'     => 'Redux Framework',
			'slug'     => 'redux-framework',
			'required' => true,
			'version'  => '3.4.1'
		),
		array(
			'name'     => 'Revolution Slider',
			'slug'     => 'revslider',
			'source'   => wp_normalize_path( "{$template_directory}/plugins/revslider.zip" ),
			'required' => false
		),
		array(
			'name'     => 'Contact Form 7',
			'slug'     => 'contact-form-7',
			'required' => false
		)
	);

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => '',
		'strings'      => array(
			'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
			'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
			'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
			'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
			'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
			'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
			'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
			'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
		)
	);

	tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'appica_tgm_init' );

/**
 * Filters and actions that act independently of the theme templates.
 */
require $appica_template_directory . '/inc/extras.php';

/**
 * AJAX Handlers
 */
require $appica_template_directory . '/inc/ajax.php';

/**
 * Customizer additions.
 */
require $appica_template_directory . '/inc/customizer.php';

/**
 * Comments functions
 */
require $appica_template_directory . '/inc/comments.php';

/**
 * Theme custom widgets
 */
require $appica_template_directory . '/inc/widgets.php';

/**
 * Menus staff & walkers
 */
require $appica_template_directory . '/inc/menus.php';

/**
 * Theme custom meta boxes
 */
require $appica_template_directory . '/inc/meta-boxes.php';

/**
 * Theme Options via Redux Framework
 */
require $appica_template_directory . '/inc/options.php';

/**
 * Visual Composer actions & filters
 */
require $appica_template_directory . '/inc/vc.php';