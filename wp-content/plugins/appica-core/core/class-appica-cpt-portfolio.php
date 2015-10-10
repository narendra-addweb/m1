<?php

/**
 * CPT "Portfolio"
 *
 * @since      1.3.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */
class Appica_CPT_Portfolio {
	/**
	 * Custom Post Type slug.
	 * @var string
	 */
	private $post_type = 'appica_portfolio';
	/**
	 * Custom taxonomy - category - for post type
	 * @var string
	 */
	private $taxonomy = 'appica_portfolio_category';
	/**
	 * Meta box nonce name
	 * @var string
	 */
	private $nonce = 'appica_portfolio_nonce';
	/**
	 * Meta box nonce field
	 * @var string
	 */
	private $nonce_field = 'appica_portfolio_nonce_field';
	/**
	 * Meta box "Tile" slug
	 * @var string
	 */
	private $mb_tile_name = '_appica_portfolio_tile';
	/**
	 * Instance of class
	 * @var null|Appica_CPT_Portfolio
	 */
	private static $instance;

	/**
	 * Initialization
	 *
	 * @return Appica_CPT_Portfolio
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self();
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

		// Display Featured Image in entries list
		add_filter( "manage_{$this->post_type}_posts_columns", array( $this, 'additional_posts_screen_columns' ) );
		add_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'additional_posts_screen_content' ), 10, 2 );

		// AJAX Load More
		if ( is_admin() ) {
			// Load more posts
			add_action( 'wp_ajax_appica_load_more_portfolio', array( $this, 'load_more' ) );
			add_action( 'wp_ajax_nopriv_appica_load_more_portfolio', array( $this, 'load_more' ) );
		}
	}

	public function register_post_type() {
		$labels  = array(
			'name'               => _x( 'Portfolio', 'Post Type General Name', 'appica' ),
			'singular_name'      => _x( 'Portfolio', 'Post Type Singular Name', 'appica' ),
			'menu_name'          => __( 'Portfolio', 'appica' ),
			'all_items'          => __( 'All Items', 'appica' ),
			'view_item'          => __( 'View', 'appica' ),
			'add_new_item'       => __( 'Add New', 'appica' ),
			'add_new'            => __( 'Add New', 'appica' ),
			'edit_item'          => __( 'Edit', 'appica' ),
			'update_item'        => __( 'Update', 'appica' ),
			'search_items'       => __( 'Search', 'appica' ),
			'not_found'          => __( 'Not found', 'appica' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'appica' )
		);
		$rewrite = array(
			'slug'       => 'portfolio-item',
			'with_front' => false,
			'pages'      => true,
			'feeds'      => true,
		);
		$args    = array(
			'label'               => __( 'Portfolio', 'appica' ),
			'labels'              => $labels,
			'description'         => __( 'Portfolio', 'appica' ),
			'public'              => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 48,
			'menu_icon'           => 'dashicons-images-alt2',
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'thumbnail', 'editor' ),
			'taxonomies'          => array( $this->taxonomy ),
			'has_archive'         => false,
			'rewrite'             => $rewrite,
			'query_var'           => true,
			'can_export'          => true,
		);

		register_post_type( $this->post_type, $args );
	}

	public function register_taxonomy() {
		$labels  = array(
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
		$rewrite = array(
			'slug'         => 'portfolio-cat',
			'with_front'   => false,
			'hierarchical' => true,
		);
		$args    = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
			'rewrite'           => $rewrite,
			'query_var'         => true,
		);

		register_taxonomy( $this->taxonomy, array( $this->post_type ), $args );
	}

	public function add_meta_boxes() {
		add_meta_box( 'appica-portfolio-tile', __( 'Portfolio Tile', 'appica' ), array( $this, 'render_portfolio_tile_mb' ), $this->post_type );
	}

	/**
	 * Show meta box: Portfolio Tile
	 *
	 * @param WP_Post $post Post object
	 */
	public function render_portfolio_tile_mb( $post ) {
		wp_nonce_field( $this->nonce, $this->nonce_field );

		/**
		 * Tile default keys & values
		 * @var array
		 */
		$defaults = array(
			'format' => 'default'
		);

		/**
		 * Options for "Format" select
		 * @var array
		 */
		$format_opts = array(
			'default'   => __( 'Default', 'appica' ),
			'wide'      => __( 'Wide', 'appica' ),
			'king-size' => __( 'King Size', 'appica' )
		);

		$tile = get_post_meta( $post->ID, $this->mb_tile_name, true );
		$tile = wp_parse_args( $tile, $defaults );

		// Templates

		// 1 - meta box name, 2 - field name (part), 3 - options, 4 - field label
		$tpl_sel = '<label for="%2$s%1$s">%4$s</label>' .
		           '<select name="%1$s[%2$s]" id="%2$s%1$s" class="widefat">%3$s</select>';

		?><div class="appica-single-settings"><?php

		$options = '';
		foreach ( $format_opts as $v => $t ) {
			$options .= sprintf(
				'<option value="%1$s"%3$s>%2$s</option>',
				$v, $t, selected( $v, esc_attr( $tile['format'] ), false )
			);
		}

		printf( $tpl_sel, $this->mb_tile_name, 'format', $options, __( 'Tile Format', 'appica' ) );
		unset( $options, $v, $t );

		?></div><?php
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
		// No auto-drafts, please
		if ( isset( $post->post_status ) && 'auto-draft' === $post->post_status ) {
			return;
		}

		// Check post type
		if ( $this->post_type !== $post->post_type ) {
			return;
		}

		// If something wrong with nonce
		if ( false === array_key_exists( $this->nonce_field, $_POST )
		     || false === wp_verify_nonce( $_POST[ $this->nonce_field ], $this->nonce ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_posts', $post_id ) ) {
			return;
		}

		// Check the autosave and revisions
		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Check, if meta box present in _POST
		if ( array_key_exists( $this->mb_tile_name, $_POST ) ) {
			$meta_box_value = array_map( 'sanitize_text_field', $_POST[ $this->mb_tile_name ] );
			update_post_meta( $post_id, $this->mb_tile_name, $meta_box_value );
			unset( $meta_box_value );
		}
	}

	/**
	 * Appica AJAX handler for portfolio "Load More" button
	 *
	 * Outputs HTML
	 */
	public function load_more() {
		// Check nonce.
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'appica-ajax' ) ) {
			wp_send_json_error( 'Bad nonce' );
		}

		$posts = $_POST['posts'];

		$post_not_in = array();
		if ( '' !== $posts && false === strpos( $posts, ',' ) ) {
			// if single post
			$post_not_in[] = absint( $posts );
		} elseif ( false !== strpos( $posts, ',' ) ) {
			$_posts      = explode( ',', $posts );
			$_posts      = array_map( 'absint', $_posts );
			$post_not_in = array_filter( $_posts );
		}

		unset( $posts );

		$query = new WP_Query( array(
			'post_type'           => 'appica_portfolio',
			'post_status'         => 'publish',
			'posts_per_page'      => - 1,
			'post__not_in'        => $post_not_in,
			'ignore_sticky_posts' => true
		) );

		$posts = array();
		if ( $query->have_posts() ) {
			while( $query->have_posts() ) {
				$query->the_post();
				ob_start();
				get_template_part( 'content', 'portfolio' );
				$posts[] = ob_get_clean();
			}
		}
		wp_reset_postdata();

		if ( count( $posts ) > 0 ) {
			wp_send_json_success( $posts );
		} else {
			wp_send_json_error( 'Posts not found' );
		}
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

	/**
	 * Change Featured Image context on "appica_portfolio" post type
	 *
	 * @author 8guild, Bill Erickson
	 * @link   http://www.billerickson.net/code/move-featured-image-metabox
	 */
	public function change_featured_image_context( $post_type ) {
		if ( $this->post_type !== $post_type ) {
			return;
		}

		remove_meta_box( 'postimagediv', $this->post_type, 'side' );
		add_meta_box( 'postimagediv', __( 'Portfolio Item Cover', 'appica' ), 'post_thumbnail_meta_box', $this->post_type, 'normal', 'high' );
	}
}