<?php

/**
 * CPT "News"
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */
class Appica_CPT_News {
	/**
	 * Custom Post Type slug.
	 * @var string
	 */
	private $post_type = 'appica_news';
	/**
	 * Meta box nonce name
	 * @var string
	 */
	private $nonce = 'appica_news_nonce';
	/**
	 * Meta box nonce field
	 * @var string
	 */
	private $nonce_field = 'appica_news_nonce_field';
	/**
	 * @var string Meta box "Settings" name
	 */
	private $mb_settings_name = '_appica_news_settings';
	/**
	 * Instance of class.
	 * @var null|Appica_CPT_News
	 */
	private static $instance;

	/**
	 * Initialization
	 *
	 * @return Appica_CPT_News
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self();
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
			'name'                => _x( 'News', 'Post Type General Name', 'appica' ),
			'singular_name'       => _x( 'News', 'Post Type Singular Name', 'appica' ),
			'menu_name'           => __( 'News', 'appica' ),
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
		$rewrite = array(
			'slug'                => 'news',
			'with_front'          => false,
			'pages'               => true,
			'feeds'               => false
		);
		$args = array(
			'label'               => __( 'News', 'appica' ),
			'description'         => __( 'Your project official news', 'appica' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'menu_position'       => 48,
			'menu_icon'           => 'dashicons-megaphone',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capability_type'     => 'post'
		);

		register_post_type( $this->post_type, $args );
	}

	public function add_meta_boxes() {
		add_meta_box( 'appica-news-settings', __( 'Post Settings', 'appica' ), array( $this, 'render_settings_mb' ), $this->post_type, 'normal', 'core' );
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
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( $this->post_type !== $post->post_type ) {
			return;
		}

		// If something wrong with nonce
		if ( ! array_key_exists( $this->nonce_field, $_POST )
		     || ! wp_verify_nonce( $_POST[ $this->nonce_field ], $this->nonce ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( array_key_exists( $this->mb_settings_name, $_POST ) ) {
			$meta_box_value = array_map( array( 'Appica_Helpers', 'sanitize_settings_meta_box' ), $_POST[ $this->mb_settings_name ] );
			update_post_meta( $post_id, $this->mb_settings_name, $meta_box_value );
			unset( $meta_box_value );
		}
	}

	/**
	 * Show meta box: settings
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post Post object
	 */
	public function render_settings_mb( $post ) {
		wp_nonce_field( $this->nonce, $this->nonce_field );

		$defaults = array(
			'sidebar'      => 'left',
			'search'       => 0,
			'custom_title' => ''
		);

		$settings = get_post_meta( $post->ID, $this->mb_settings_name, true );
		$settings = wp_parse_args( $settings, $defaults );

		// Sanitize settings
		$sidebar      = esc_attr( $settings['sidebar'] );
		$custom_title = esc_attr( $settings['custom_title'] );
		?>
		<div class="appica-single-settings">
			<label for="appica-news-sidebar-position"><?php _e( 'Sidebar Position', 'appica' ); ?></label>
			<select name="<?php echo $this->mb_settings_name; ?>[sidebar]" id="appica-news-sidebar-position" class="widefat">
				<option value="left" <?php selected( 'left', $sidebar ); ?>><?php _e( 'Left', 'appica' ); ?></option>
				<option value="right" <?php selected( 'right', $sidebar ); ?>><?php _e( 'Right', 'appica' ); ?></option>
				<option value="none" <?php selected( 'none', $sidebar ); ?>><?php _e( 'No sidebar', 'appica' ); ?></option>
			</select>
			<p class="description"><?php _e( 'Choose sidebar position, or disable it', 'appica' ); ?></p>

			<label for="appica-news-custom-title"><?php _e( 'Custom Post Title', 'appica' ); ?></label>
			<input type="text" name="<?php echo $this->mb_settings_name; ?>[custom_title]" id="appica-news-custom-title"
			       class="widefat" value="<?php echo $custom_title; ?>">
			<p class="description"><?php _e( 'Title will be rendered above standard post title', 'appica' ); ?></p>
		</div>
		<?php
	}


}