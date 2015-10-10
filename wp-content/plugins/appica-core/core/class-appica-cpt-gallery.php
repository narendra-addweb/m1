<?php

/**
 * CPT "Gallery"
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */
class Appica_CPT_Gallery {
	/**
	 * Custom Post Type slug.
	 * @var string
	 */
	private $post_type = 'appica_gallery';
	/**
	 * Custom taxonomy - category - for post type
	 * @var string
	 */
	private $taxonomy = 'appica_gallery_category';
	/**
	 * Meta box nonce name
	 * @var string
	 */
	private $nonce = 'appica_gallery_nonce';
	/**
	 * Meta box nonce field
	 * @var string
	 */
	private $nonce_field = 'appica_gallery_nonce_field';
	/**
	 * Meta box "Video" slug
	 * @var string
	 */
	private $mb_video_name = '_appica_gallery_video';
	/**
	 * Instance of class.
	 * @var null|Appica_CPT_Gallery
	 */
	private static $instance;

	/**
	 * Initialization
	 *
	 * @return Appica_CPT_Gallery
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ), 0 );
		add_action( 'init', array( $this, 'register_taxonomy' ), 0 );
		// Meta Boxes
		add_action( 'do_meta_boxes', array( $this, 'change_featured_image_context' ) );
		add_action( "add_meta_boxes_{$this->post_type}", array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 10, 2 );
		// AJAX
		if ( is_admin() ) {
			add_action( 'wp_ajax_appica_gallery_video', array( $this, 'ajax_video_mb' ) );
		}

		// Display Featured Image in entries list
		add_filter( "manage_{$this->post_type}_posts_columns", array( $this, 'additional_posts_screen_columns' ) );
		add_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'additional_posts_screen_content' ), 10, 2 );
	}

	public function register_post_type() {
		$labels = array(
			'name'                => _x( 'Gallery', 'Post Type General Name', 'appica' ),
			'singular_name'       => _x( 'Gallery', 'Post Type Singular Name', 'appica' ),
			'menu_name'           => __( 'Gallery', 'appica' ),
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
			'label'               => __( 'Gallery', 'appica' ),
			'description'         => __( 'Images or video gallery', 'appica' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail', 'excerpt' ),
			'taxonomies'          => array( $this->taxonomy ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'menu_position'       => 48,
			'menu_icon'           => 'dashicons-format-video',
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

	public function register_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Categories', 'Taxonomy General Name', 'appica' ),
			'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'appica' ),
			'menu_name'                  => __( 'Categories', 'appica' ),
			'all_items'                  => __( 'All Items', 'appica' ),
			'parent_item'                => __( 'Parent Item', 'appica' ),
			'parent_item_colon'          => __( 'Parent Item:', 'appica' ),
			'new_item_name'              => __( 'New Item Name', 'appica' ),
			'add_new_item'               => __( 'Add New', 'appica' ),
			'edit_item'                  => __( 'Edit', 'appica' ),
			'update_item'                => __( 'Update', 'appica' ),
			'separate_items_with_commas' => __( 'Separate with commas', 'appica' ),
			'search_items'               => __( 'Search', 'appica' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'appica' ),
			'choose_from_most_used'      => __( 'Choose from the most used items', 'appica' ),
			'not_found'                  => __( 'Not Found', 'appica' )
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => false,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
			'rewrite'                    => false
		);

		register_taxonomy( $this->taxonomy, $this->post_type, $args );
	}

	/**
	 * Change Featured Image context on "appica_gallery" post type
	 *
	 * @since  1.0.0
	 *
	 * @author 8guild, Bill Erickson
	 * @link   http://www.billerickson.net/code/move-featured-image-metabox
	 */
	public function change_featured_image_context( $post_type ) {
		if ( $this->post_type !== $post_type ) {
			return;
		}

		remove_meta_box( 'postimagediv', $this->post_type, 'side' );
		add_meta_box( 'postimagediv', __( 'Gallery Image', 'appica' ), 'post_thumbnail_meta_box', $this->post_type, 'normal', 'high' );
	}

	/**
	 * Add column "Preview" to CPT "Gallery" screen
	 *
	 * @param array $columns Current Posts Screen columns
	 *
	 * @return array New Posts Screen columns.
	 */
	public function additional_posts_screen_columns( $columns ) {
		$_columns = array(
			'cb'    => '<input type="checkbox" />',
			'image' => __( 'Preview', 'appica' )
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

	public function add_meta_boxes() {
		add_meta_box( 'appica-gallery-video', __( 'Video', 'appica' ), array( $this, 'render_video_mb' ), $this->post_type );
	}

	/**
	 * Save post metadata when a post of {@see $this->post_type} is saved.
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

		$meta_box_value = esc_url_raw( $_POST[ $this->mb_video_name ] );
		update_post_meta( $post_id, $this->mb_video_name, $meta_box_value );
	}

	/**
	 * Show meta box: video
	 *
	 * @param WP_Post $post Post object
	 */
	public function render_video_mb( $post ) {
		wp_nonce_field( $this->nonce, $this->nonce_field );

		$embed = '';
		$video_url = get_post_meta( $post->ID, $this->mb_video_name, true );
		if ( '' !== $video_url ) {
			$embed = wp_oembed_get( $video_url );
		}
		?>
		<p class="description"><?php _e( 'Gallery image represents cover image for the tile and must be set to avoid breaking layout.', 'appica' ); ?></p>
		<p class="description"><?php _e( 'Paste here URL to your video on YouTube/Vimeo. It will appear in pop-up when tile is clicked.', 'appica' ); ?></p>
		<?php

		printf(
			'<input type="text" class="%2$s widefat" id="appica-core-gallery-video" name="%2$s" value="%1$s" placeholder="%3$s">',
			esc_url( $video_url ), $this->mb_video_name, __( 'Video URL', 'appica' )
		);
		echo '<br>', "<div class=\"appica-core-video-holder\" style=\"margin-top: 20px;\">{$embed}</div>";
	}

	/**
	 * AJAX callback for rendering recently added video URL to field
	 *
	 * @since 1.0.0
	 */
	function ajax_video_mb() {
		// Verify nonce
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'appica-ajax' ) ) {
			wp_send_json_error( 'Nonce is not valid' );
		}

		$url = esc_url_raw( $_POST['url'] );
		// Just die in silence
		if ( '' === $url ) {
			wp_send_json_error( __( 'URL is empty', 'appica' ) );
		}

		// Else get oEmbed code
		$embed = wp_oembed_get( $url, array( 'width' => 510 ) );
		if ( false === $embed ) {
			wp_send_json_error( __( 'URL is not valid or provider do not support oEmbed protocol', 'appica' ) );
		}

		wp_send_json_success( $embed );
	}
}