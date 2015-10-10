<?php

/**
 * CPT "Timeline"
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica 2
 * @subpackage Core
 */
class Appica_CPT_Timeline {
	/**
	 * Custom Post Type slug.
	 * @var string
	 */
	private $post_type = 'appica_timeline';
	/**
	 * Meta box nonce name
	 * @var string
	 */
	private $nonce = 'appica_timeline_nonce';
	/**
	 * Meta box nonce field
	 * @var string
	 */
	private $nonce_field = 'appica_timeline_nonce_field';
	/**
	 * Meta box "Date" slug
	 * @var string
	 */
	private $mb_date_name = '_appica_timeline_date';
	/**
	 * Instance of class.
	 * @var null|Appica_CPT_Timeline
	 */
	private static $instance;

	/**
	 * Initialization
	 *
	 * @return Appica_CPT_Timeline
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ), 0 );
		// Meta Boxes
		add_action( "add_meta_boxes_{$this->post_type}", array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 10, 2 );
	}

	public function register_post_type() {
		$labels = array(
			'name'                => _x( 'Timeline', 'Post Type General Name', 'appica' ),
			'singular_name'       => _x( 'Timeline', 'Post Type Singular Name', 'appica' ),
			'menu_name'           => __( 'Timeline', 'appica' ),
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
			'label'               => __( 'Timeline', 'appica' ),
			'description'         => __( 'Custom post type for Timeline', 'appica' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'excerpt' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'menu_position'       => 48,
			'menu_icon'           => 'dashicons-flag',
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

	public function add_meta_boxes() {
		add_meta_box( 'appica-timeline-date', __( 'Date', 'appica' ), array( $this, 'render_date_mb' ), $this->post_type, 'normal', 'core' );
	}

	/**
	 * Save post metadata when a post of {@see $this->post_type} is saved.
	 *
	 * @since 1.0.0
	 *
	 * @param int     $post_id The ID of the post.
	 * @param WP_Post $post    Post object
	 *
	 * @return void
	 */
	public function save_meta_boxes( $post_id, $post ) {
		if ( $this->post_type !== $post->post_type ) {
			return;
		}

		// If something wrong with nonce
		if ( ! array_key_exists( $this->nonce_field, $_POST )
		     || ! wp_verify_nonce( $_POST[ $this->nonce_field ], $this->nonce ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Update date meta box
		$meta_box_value = sanitize_text_field( $_POST[ $this->mb_date_name ] );
		update_post_meta( $post_id, $this->mb_date_name, $meta_box_value );
		unset( $meta_box_value );
	}

	/**
	 * Show meta box: date
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post Post object
	 */
	public function render_date_mb( $post ) {
		wp_nonce_field( $this->nonce, $this->nonce_field );

		$date = get_post_meta( $post->ID, $this->mb_date_name, true );

		printf( '<p class="description">%s</p>', __( 'Set date here. Any format acceptable.', 'appica' ) );
		printf( '<input type="text" name="%1$s" class="widefat" value="%2$s"', $this->mb_date_name, esc_attr( $date ) );
	}
}