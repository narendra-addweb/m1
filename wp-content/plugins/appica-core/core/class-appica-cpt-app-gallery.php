<?php

/**
 * CPT "App Gallery"
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica 2
 * @subpackage Core
 */
class Appica_CPT_App_Gallery {
	/**
	 * Custom Post Type slug.
	 * @var string
	 */
	private $post_type = 'appica_app_gallery';
	/**
	 * Instance of class.
	 * @var null|Appica_CPT_App_Gallery
	 */
	private static $instance;

	/**
	 * Initialization
	 *
	 * @return Appica_CPT_App_Gallery
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ), 0 );
		add_action( 'do_meta_boxes', array( $this, 'change_featured_image_context' ) );
		// Add image sizes: normal and large
		add_image_size( 'appica-app-gallery', 378, 223, true );
		add_image_size( 'appica-app-gallery-large', 378, 451, true );
		// Display Featured Image in entries list
		add_filter( "manage_{$this->post_type}_posts_columns", array( $this, 'additional_posts_screen_columns' ) );
		add_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'additional_posts_screen_content' ), 10, 2 );
	}

	public function register_post_type() {
		$labels = array(
			'name'                => _x( 'App Gallery', 'Post Type General Name', 'appica' ),
			'singular_name'       => _x( 'App Gallery', 'Post Type Singular Name', 'appica' ),
			'menu_name'           => __( 'App Gallery', 'appica' ),
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
			'label'               => __( 'App Gallery', 'appica' ),
			'description'         => __( 'Fancy app gallery', 'appica' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'menu_position'       => 48,
			'menu_icon'           => 'dashicons-format-gallery',
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

	/**
	 * Change Featured Image context on "appica_app_gallery" post type
	 *
	 * @since 1.0.0
	 * @author 8guild, Bill Erickson
	 * @link http://www.billerickson.net/code/move-featured-image-metabox
	 */
	public function change_featured_image_context( $post_type ) {
		if ( $this->post_type !== $post_type ) {
			return;
		}

		remove_meta_box( 'postimagediv', $this->post_type, 'side' );
		add_meta_box( 'postimagediv', __( 'App Image', 'appica' ), 'post_thumbnail_meta_box', $this->post_type, 'normal', 'high' );
	}

	/**
	 * Add column "Featured Image" to "App Gallery" screen
	 *
	 * @param array $columns Current Posts Screen columns
	 *
	 * @return array New Posts Screen columns.
	 */
	public function additional_posts_screen_columns( $columns ) {
		$_columns = array(
			'cb'    => '<input type="checkbox" />',
			'image' => __( 'Featured Image', 'appica' )
		);

		$columns = array_merge( $_columns, $columns );

		return $columns;
	}

	/**
	 * Show Featured image in "Featured Image" column
	 *
	 * @param string $column  Column slug
	 * @param int    $post_id Post ID
	 */
	public function additional_posts_screen_content( $column, $post_id ) {
		switch ( $column ) {
			case 'image':
				echo get_the_post_thumbnail( $post_id, array( 75, 75 ) );
				break;
		}
	}
}