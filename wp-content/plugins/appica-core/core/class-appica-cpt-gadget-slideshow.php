<?php

/**
 * CPT "Gadget Slideshow"
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica 2
 * @subpackage Core
 */
class Appica_CPT_Gadget_Slideshow {
	/**
	 * Custom Post Type slug.
	 * @var string
	 */
	private $post_type = 'appica_slideshow';
	/**
	 * Instance of class.
	 * @var null|Appica_CPT_Gadget_Slideshow
	 */
	private static $instance;

	/**
	 * Initialization
	 *
	 * @return Appica_CPT_Gadget_Slideshow
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
			'name'                => _x( 'Gadget Slideshow', 'Post Type General Name', 'appica' ),
			'singular_name'       => _x( 'Gadget Slideshow', 'Post Type Singular Name', 'appica' ),
			'menu_name'           => __( 'Gadget Slideshow', 'appica' ),
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
			'label'               => __( 'Gadget Slideshow', 'appica' ),
			'description'         => __( 'Gadget Slideshow', 'appica' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'excerpt' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'menu_position'       => 48,
			'menu_icon'           => 'dashicons-slides',
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
		add_meta_box( 'appica-slideshow-icon', __( 'Icon', 'appica' ), array( $this, 'render_icon_mb' ), $this->post_type, 'side', 'core' );
		add_meta_box( 'appica-slideshow-transition', __( 'Transition', 'appica' ), array( $this, 'render_transition_mb' ), $this->post_type, 'side' );
		add_meta_box( 'appica-slideshow-media', __( 'Phone & Tablet image', 'appica' ), array( $this, 'render_media_mb' ), $this->post_type );
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
		if ( ! array_key_exists( 'appica_gs_nonce_field', $_POST )
		     || ! wp_verify_nonce( $_POST['appica_gs_nonce_field'], 'appica_gs_nonce' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save subtitle
		$meta_box_name  = '_appica_slideshow_icon';
		$meta_box_value = sanitize_text_field( $_POST[ $meta_box_name ] );
		update_post_meta( $post_id, $meta_box_name, $meta_box_value );
		unset( $meta_box_name, $meta_box_value );

		// Save transition
		$meta_box_name  = '_appica_slideshow_transition';
		$meta_box_value = sanitize_text_field( $_POST[ $meta_box_name ] );
		update_post_meta( $post_id, $meta_box_name, $meta_box_value );
		unset( $meta_box_name, $meta_box_value );

		// Save media
		$meta_box_name  = '_appica_slideshow_media';
		$meta_box_value = array_map( 'absint', $_POST[ $meta_box_name ] );
		update_post_meta( $post_id, $meta_box_name, $meta_box_value );
		unset( $meta_box_name, $meta_box_value );
	}

	/**
	 * Show icon meta box
	 *
	 * @param WP_Post $post Post object
	 */
	public function render_icon_mb( $post ) {
		wp_nonce_field( 'appica_gs_nonce', 'appica_gs_nonce_field' );

		$meta_box_name = '_appica_slideshow_icon';
		$meta_box_value = get_post_meta( $post->ID, $meta_box_name, true );

		$dPreview = 'none';
		$dRemove  = 'none';
		$preview  = '';
		if ( '' !== $meta_box_value ) {
			$dPreview = 'block';
			$dRemove  = 'inline';
			$preview  = "<i class=\"glyph-icon {$meta_box_value}\"></i>";
		}

		echo '<p>', __( 'Choose an icon', 'appica' ), '</p>'
		?><div class="appica-core-icon-wrapper">
			<input type="hidden" name="<?php echo $meta_box_name; ?>" class="appica-core-icon-val" value="<?php echo $meta_box_value; ?>">
			<div class="appica-core-icon-preview" style="display: <?php echo $dPreview; ?>;"><?php echo $preview; ?></div>
			<button type="button" class="button appica-core-icon-select" data-pack="flaticons"><?php _e( 'Select', 'appica' ); ?></button>
			<button type="button" class="button appica-core-icon-remove" style="display: <?php echo $dRemove; ?>;"><?php _e( 'Remove', 'appica' ); ?></button>
		</div><?php
	}

	/**
	 * Show "Transition" meta box
	 *
	 * @param WP_Post $post Post object
	 */
	public function render_transition_mb( $post ) {
		wp_nonce_field( 'appica_gs_nonce', 'appica_gs_nonce_field' );

		$meta_box_name = '_appica_slideshow_transition';
		$meta_box_value = get_post_meta( $post->ID, $meta_box_name, true );

		$transitions = array(
			'fade'    => 'Fade',
			'scale'   => 'Scale',
			'scaleup' => 'Scaleup',
			'top'     => 'Top',
			'bottom'  => 'Bottom',
			'left'    => 'Left',
			'right'   => 'Right',
			'flip'    => 'Flip'
		);

		$options = '';
		foreach( $transitions as $key => $transition ) {
			$options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $key, $transition, selected( $meta_box_value, $key, false ) );
		}

		echo '<label for="appica-sh-transition-sel" style="display: inline-block; margin-bottom: 10px;">', __( 'Choose the transition for current entry', 'appica' ), '</label>';
		printf( '<select name="%1$s" id="appica-sh-transition-sel" class="widefat">%2$s</select>', $meta_box_name, $options );
	}

	/**
	 * Render meta box "Phone & Tablet image"
	 *
	 * @param WP_Post $post Post object
	 */
	public function render_media_mb( $post ) {
		wp_nonce_field( 'appica_gs_nonce', 'appica_gs_nonce_field' );

		$defaults = array(
			'phone'  => 0,
			'tablet' => 0
		);

		$meta_box_name = '_appica_slideshow_media';
		$meta_box_value = get_post_meta( $post->ID, $meta_box_name, true );
		$meta_box_value = wp_parse_args( $meta_box_value, $defaults );

		/*
		 * Phone preview
		 */
		$phone   = (int) $meta_box_value['phone'];
		$preview = 'none'; // preview style:display
		$remove  = 'none'; // remove btn style:display
		$image   = '';

		if ( ! empty( $phone ) ) {
			$preview = 'block';
			$remove  = 'inline';
			$image   = wp_get_attachment_image( $phone, 'full' );
		}
		?>

		<p><?php _e( 'Select "Phone" image. Min image size: 233х423px. Recommended size: 473x858px for better smoothing', 'appica' ); ?></p>
		<div class="appica-core-media-wrapper">
			<input type="hidden" name="<?php echo $meta_box_name; ?>[phone]" class="appica-core-media-val" value="<?php echo $phone; ?>">
			<div class="appica-core-media-preview" style="display: <?php echo $preview; ?>"><?php echo $image; ?></div><?php

			printf(
				'<button type="button" class="button appica-core-media" data-title="%2$s" data-button="%1$s" data-multiple="%3$d">%1$s</button>',
				__( 'Select', 'appica' ), __( 'Select a phone image', 'appica' ), 0
			);

			?><button type="button" class="button appica-core-media-remove" style="display: <?php echo $remove; ?>"><?php _e( 'Remove', 'appica' ); ?></button>
		</div><?php

		/*
		 * Tablet preview
		 */
		$tablet  = (int) $meta_box_value['tablet'];
		$preview = 'none';
		$remove  = 'none';
		$image   = '';

		if ( ! empty( $tablet ) ) {
			$preview = 'block';
			$remove  = 'inline';
			$image   = wp_get_attachment_image( $tablet, 'full' );
		}
		?>

		<p><?php _e( 'Select "Tablet" image. Min image size: 838х629px. Recommended size: 1223x917px for better smoothing', 'appica' ); ?></p>
		<div class="appica-core-media-wrapper">
			<input type="hidden" name="<?php echo $meta_box_name; ?>[tablet]" class="appica-core-media-val" value="<?php echo $tablet; ?>">
			<div class="appica-core-media-preview" style="display: <?php echo $preview; ?>"><?php echo $image; ?></div><?php

			printf(
				'<button type="button" class="button appica-core-media" data-title="%2$s" data-button="%1$s" data-multiple="%3$d">%1$s</button>',
				__( 'Select', 'appica' ), __( 'Select a tablet image', 'appica' ), 0
			);

		?><button type="button" class="button appica-core-media-remove" style="display: <?php echo $remove; ?>"><?php _e( 'Remove', 'appica' ); ?></button>
		</div><?php
	}
}