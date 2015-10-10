<?php
/**
 * Plugin Name: Appica Core
 * Plugin URI: http://the8guild.com
 * Description: Core functionality for 8guild's Appica 2 Theme
 * Version: 1.3.0
 * Author: 8guild
 * Author URI: http://the8guild.com
 * License: GPLv2 or later
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

/**
 * Plugin DocRoot absolute path without trailing slash
 *
 * @since 1.0.0
 * @var string
 */
define( 'APPICA_CORE_ROOT', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

/**
 * Load core classes according to WordPress naming conventions.
 *
 * @link  https://make.wordpress.org/core/handbook/coding-standards/php/#naming-conventions
 *
 * @param string $class Class name
 *
 * @since 1.0.0
 */
function appica_loader( $class ) {
    $chunks = array_filter( explode( '_', strtolower( $class ) ) );

    // Load only plugins classes
    $fchunk = reset( $chunks );
    if ( false === $fchunk || 'appica' !== $fchunk ) {
        return;
    }

    $root = APPICA_CORE_ROOT . '/core/';
    $file = 'class-' . implode( '-', $chunks ) . '.php';

    $path = wp_normalize_path( $root . $file );
    if ( is_readable( $path ) ) {
        require $path;
    }
}

/**
 * Load plugin
 *
 * Load the text domain and hook up the CPTs
 *
 * @since 1.0.0
 */
function appica_core_load() {
    // Load text domain
    load_plugin_textdomain( 'appica', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    // Register the auto loader function
    spl_autoload_register( 'appica_loader' );

    // Let's add some CPTs
    Appica_CPT_News::init();
    Appica_CPT_Team::init();
    Appica_CPT_Team_Alt::init();
    Appica_CPT_Gallery::init();
    Appica_CPT_Pricings::init();
    Appica_CPT_Timeline::init();
	Appica_CPT_Portfolio::init();
    Appica_CPT_App_Gallery::init();
    Appica_CPT_Testimonials::init();
    Appica_CPT_Gadget_Slideshow::init();
}

add_action( 'plugins_loaded', 'appica_core_load' );

/**
 * Plugin initialization
 *
 * @since 1.0.0
 */
function appica_core_init() {
    Appica_Filters::init();
    Appica_Shortcode::init();
    Appica_AJAX::init();
}

add_action( 'init', 'appica_core_init' );

/**
 * Load VC integration file
 *
 * @since 1.0.0
 */
function appica_core_vc_init() {
    require APPICA_CORE_ROOT . '/inc/vc-map.php';
}

add_action( 'vc_before_init', 'appica_core_vc_init' );

/**
 * Enqueue scripts and styles on admin screens
 *
 * @since 1.0.0
 */
function appica_core_scripts() {
    wp_register_style( 'appica-fancybox2', plugins_url( 'assets/css/fancybox/jquery.fancybox.css', __FILE__ ), array(), null );
    wp_register_style( 'appica-filterable', plugins_url( 'assets/css/jquery.mobile.custom.structure.min.css', __FILE__ ), array(), null );
    wp_enqueue_style( 'appica-core', plugins_url( 'assets/css/appica-core.css', __FILE__ ), array(
        'appica-fancybox2',
        'appica-filterable'
    ), null );

    wp_register_script( 'appica-filterable', plugins_url( 'assets/js/jquery.mobile.custom.min.js', __FILE__ ), array( 'jquery' ), null, true );
    wp_register_script( 'appica-fancybox2', plugins_url( 'assets/js/jquery.fancybox.pack.js', __FILE__ ), array( 'jquery' ), null, true );
    wp_enqueue_script( 'appica-core', plugins_url( 'assets/js/appica-core-admin.js', __FILE__ ), array(
        'jquery',
        'appica-filterable',
        'appica-fancybox2'
    ), null, true );

    wp_enqueue_media();

    wp_localize_script( 'appica-core', 'appicaCore', array(
        'nonce' => wp_create_nonce( 'appica-ajax' ),
        'icon'  => array(
            'preview' => '',
            'value'   => ''
        )
    ) );

    $head_css = Appica_Helpers::get_head_css();
    wp_add_inline_style( 'appica-core', $head_css );
}

add_action( 'admin_enqueue_scripts', 'appica_core_scripts' );

/**
 * Enqueue scripts and styles on front-end
 *
 * @since 1.0.0
 */
function appica_core_front_scripts() {
    // TODO: maybe get custom google maps api key?
    wp_register_script( 'appica-google-maps', '//maps.googleapis.com/maps/api/js?key=AIzaSyA5DLwPPVAz88_k0yO2nmFe7T9k1urQs84', array(), null );
    wp_register_script( 'appica-magnific-popup', plugins_url( 'assets/js/jquery.magnific-popup.min.js', __FILE__ ), array( 'jquery' ), null, true );
    wp_enqueue_script( 'appica-core', plugins_url( 'assets/js/appica-core.js', __FILE__ ), array(
        'jquery',
        'appica-magnific-popup'
    ), null, true );
}

add_action( 'wp_enqueue_scripts', 'appica_core_front_scripts' );

/**
 * Flush rewrite rules on plugin activation/deactivation
 *
 * @since 1.0.0
 */
function appica_core_flush() {
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'appica_core_flush' );
register_deactivation_hook( __FILE__, 'appica_core_flush' );