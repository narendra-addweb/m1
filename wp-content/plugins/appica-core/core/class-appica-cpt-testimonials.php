<?php

/**
 * CPT "Testimonials"
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica 2
 * @subpackage Core
 */
class Appica_CPT_Testimonials {
	/**
	 * Custom Post Type slug.
	 * @var string
	 */
	private $post_type = 'appica_testimonials';
	/**
	 * Instance of class.
	 * @var null|Appica_CPT_Testimonials
	 */
	private static $instance;

	/**
	 * Initialization
	 *
	 * @return Appica_CPT_Testimonials
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ), 0 );
	}

	public function register_post_type() {
		$labels = array(
			'name'                => _x( 'Testimonials', 'Post Type General Name', 'appica' ),
			'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'appica' ),
			'menu_name'           => __( 'Testimonials', 'appica' ),
			'all_items'           => __( 'All Items', 'appica' ),
			'view_item'           => __( 'View', 'appica' ),
			'add_new_item'        => __( 'Add New', 'appica' ),
			'add_new'             => __( 'Add New', 'appica' ),
			'edit_item'           => __( 'Edit', 'appica' ),
			'update_item'         => __( 'Update', 'appica' ),
			'search_items'        => __( 'Search', 'appica' ),
			'not_found'           => __( 'Not found', 'appica' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'appica' )
		);
		$args = array(
			'label'               => __( 'Testimonials', 'appica' ),
			'description'         => __( 'Testimonials', 'appica' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'menu_position'       => 48,
			'menu_icon'           => 'dashicons-testimonial',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'query_var'           => true,
			'rewrite'             => false,
			'capability_type'     => 'post'
		);

		register_post_type( $this->post_type, $args );
	}
}