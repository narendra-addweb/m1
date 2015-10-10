<?php

/**
 * CPT "Team 2"
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica 2
 * @subpackage Core
 */
class Appica_CPT_Team_Alt {
	/**
	 * Custom Post Type slug.
	 * @var string
	 */
	private $post_type = 'appica_team_alt';
	/**
	 * Meta box nonce name
	 * @var string
	 */
	private $nonce = 'appica_team_alt_nonce';
	/**
	 * Meta box nonce field
	 * @var string
	 */
	private $nonce_field = 'appica_team_alt_nonce_field';
	/**
	 * Meta box "Subtitle" slug
	 * @var string
	 */
	private $mb_subtitle_name = '_appica_team_alt_subtitle';
	/**
	 * Meta box "Social" slug
	 * @var string
	 */
	private $mb_social_name = '_appica_team_alt_social';
	/**
	 * Instance of class.
	 * @var null|Appica_CPT_Team_Alt
	 */
	private static $instance;

	/**
	 * Initialization
	 *
	 * @return Appica_CPT_Team_Alt
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
		// Display Featured Image in entries list
		add_filter( "manage_{$this->post_type}_posts_columns", array( $this, 'additional_posts_screen_columns' ) );
		add_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'additional_posts_screen_content' ), 10, 2 );
	}

	public function register_post_type() {
		$labels = array(
			'name'                => _x( 'Team 2', 'Post Type General Name', 'appica' ),
			'singular_name'       => _x( 'Team 2 ', 'Post Type Singular Name', 'appica' ),
			'menu_name'           => __( 'Team 2', 'appica' ),
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
			'label'               => __( 'Team 2', 'appica' ),
			'description'         => __( 'My cool team, second layout', 'appica' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'menu_position'       => 48,
			'menu_icon'           => 'dashicons-groups',
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
		add_meta_box( 'appica-team-subtitle', __( 'Additional Info', 'appica' ), array( $this, 'render_subtitle_mb'), $this->post_type, 'normal', 'core' );
		add_meta_box( 'appica-team-social', __( 'Social Networks', 'appica' ), array( $this, 'render_social_mb'), $this->post_type );
	}

	/**
	 * Save post metadata when a post of {@see $this->post_type} is saved.
	 *
	 * @param int     $post_id The ID of the post.
	 * @param WP_Post $post    Post object
	 *
	 * @return void|bool
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

		// Save subtitle
		$meta_box_value = sanitize_text_field( $_POST[ $this->mb_subtitle_name ] );
		update_post_meta( $post_id, $this->mb_subtitle_name, $meta_box_value );
		unset( $meta_box_value );

		// Save socials
		$meta_box_value = Appica_Helpers::process_social_networks( $_POST[ $this->mb_social_name ] );
		update_post_meta( $post_id, $this->mb_social_name, $meta_box_value );
		unset( $meta_box_value );
	}

	/**
	 * Show subtitle meta box
	 *
	 * @param WP_Post $post Post object
	 */
	public function render_subtitle_mb( $post ) {
		wp_nonce_field( $this->nonce, $this->nonce_field );

		$meta_box_value = get_post_meta( $post->ID, $this->mb_subtitle_name, true );

		printf( '<label for="%1$s" class="screen-reader-text">%2$s</label><br>', $this->mb_subtitle_name, __( 'Drop any line here', 'appica' ) );
		printf(
			'<input type="text" class="%2$s widefat" id="%2$s" name="%2$s" value="%1$s">',
			esc_attr( $meta_box_value ), $this->mb_subtitle_name
		);
		echo '<br><p>', __( 'Here you can add position / second name / any additional information you want. This information will be displayed under the title.', 'appica' ), '</p>';
	}

	/**
	 * Show social meta box.
	 *
	 * @param WP_Post $post Post object
	 */
	public function render_social_mb( $post ) {
		wp_nonce_field( $this->nonce, $this->nonce_field );

		$networks = Appica_Helpers::get_social_networks_list();
		$socials  = get_post_meta( $post->ID, $this->mb_social_name, true );

		echo '<p>', __( 'Choose social networks for displaying', 'appica' ), '</p>';
		echo '<div class="appica-social-networks-wrap">';
		if ( ! empty( $socials ) ) {
			$this->render_networks_list( $this->mb_social_name, $networks, $socials );
		} else {
			$this->render_empty_list( $this->mb_social_name, $networks );
		}
		echo '</div><br>'; // close .appica-social-networks-wrap
		echo '<button type="button" class="button button-primary appica-add-social-network">', __( 'Add one more social network', 'appica' ), '</button>';
	}

	/**
	 * Render empty list of social networks with controls
	 *
	 * @param string $name     Name of meta box
	 * @param array  $networks Array of allowed networks
	 */
	private function render_empty_list( $name, $networks ) {
		$select_name = "{$name}[networks][]";
		$input_name  = "{$name}[urls][]";

		?><div class="appica-social-group">
		<select name="<?php echo $select_name; ?>" class="appica-social-network"><?php
			foreach ( $networks as $network => $data ) :
				printf( '<option value="%1$s">%2$s</option>', $network, $data['name'] );
			endforeach; unset( $network, $data ); ?>
		</select>
		<input type="text" name="<?php echo $input_name; ?>" placeholder="<?php _e( 'Profile URL', 'appica' ); ?>"
		       class="appica-social-url">
		</div><?php
	}

	/**
	 * Render filled list of social networks with controls
	 *
	 * @param string $name     Name of meta box
	 * @param array  $networks Array of allowed networks
	 * @param array  $socials  Array of selected networks
	 */
	private function render_networks_list( $name, $networks, $socials ) {
		$select_name = "{$name}[networks][]";
		$input_name  = "{$name}[urls][]";

		foreach ( (array) $socials as $social => $url ) : ?><div class="appica-social-group">
			<select name="<?php echo $select_name; ?>" class="appica-social-network"><?php
				// Check, if this network was selected
				foreach ( $networks as $network => $data ) :
					$selected = ( $network === $social ) ? 'selected' : '';
					// 1 - network slug, 2 - network name, 3 - selected
					printf( '<option value="%1$s" %3$s>%2$s</option>', $network, $data['name'], $selected );
				endforeach; unset( $network, $data ); ?>
			</select>
			<input type="text" name="<?php echo $input_name; ?>" class="appica-social-url"
			       value="<?php echo esc_url( $url ); ?>" placeholder="<?php _e( 'Profile URL', 'domino' ); ?>">
			</div><?php
		endforeach;
	}

	/**
	 * Add column "Featured Image" to {@see Appica_CPT_Team_Alt::$post_type} screen
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