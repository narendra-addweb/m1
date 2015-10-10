<?php

/**
 * CPT "Pricings"
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */
class Appica_CPT_Pricings {
	/**
	 * Custom Post Type slug.
	 * @var string
	 */
	private $post_type = 'appica_pricings';
	/**
	 * @var string Custom taxonomy name
	 */
	private $taxonomy = 'appica_pricings_terms';
	/**
	 * Meta box nonce name
	 * @var string
	 */
	private $nonce = 'appica_pricings_nonce';
	/**
	 * Meta box nonce field
	 * @var string
	 */
	private $nonce_field = 'appica_pricings_nonce_field';
	/**
	 * Meta box "Price" slug
	 * @var string
	 */
	private $mb_price_name = '_appica_pricings_price';
	/**
	 * Meta box "Button" slug
	 * @var string
	 */
	private $mb_button_name = '_appica_pricings_button';
	/**
	 * Meta box "Icon" slug
	 * @var string
	 */
	private $mb_icon_name = '_appica_pricings_icon';
	/**
	 * Check, if is android version of theme. Android version requires another set of settings
	 * @var bool
	 */
	private $is_android = false;
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
		$theme = wp_get_theme();
		$this->is_android = ( 'appica-android' === $theme->get_template() );

		add_action( 'init', array( $this, 'register_post_type' ), 0 );
		add_action( 'init', array( $this, 'register_taxonomy' ), 0 );
		// Meta Boxes
		add_action( "add_meta_boxes_{$this->post_type}", array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 10, 2 );

		/**
		 * @since 1.1.0
		 */
		add_filter( "manage_{$this->post_type}_posts_columns", array( $this, 'additional_posts_screen_columns' ) );
	}

	public function register_post_type() {
		$labels = array(
			'name'                => _x( 'Pricings', 'Post Type General Name', 'appica' ),
			'singular_name'       => _x( 'Pricing', 'Post Type Singular Name', 'appica' ),
			'menu_name'           => __( 'Pricings', 'appica' ),
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
			'label'               => __( 'Pricings', 'appica' ),
			'description'         => __( 'Custom post type for Pricing Plans', 'appica' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor' ),
			'taxonomies'          => array( $this->taxonomy ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'menu_position'       => 48,
			'menu_icon'           => 'dashicons-media-spreadsheet',
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
	 * Register custom taxonomy
	 *
	 * @since 1.1.0
	 */
	public function register_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Time spans', 'Taxonomy General Name', 'appica' ),
			'singular_name'              => _x( 'Time span', 'Taxonomy Singular Name', 'appica' ),
			'menu_name'                  => __( 'Time spans', 'appica' ),
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
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
			'meta_box_cb'       => false,
			'rewrite'           => false
		);

		register_taxonomy( $this->taxonomy, $this->post_type, $args );
	}

	public function add_meta_boxes() {
		add_meta_box( 'appica-pricings-icon', __( 'Icon', 'appica' ), array( $this, 'render_icon_mb' ), $this->post_type, 'side', 'core' );
		add_meta_box( 'appica-pricings-price', __( 'Plan', 'appica' ), array( $this, 'render_price_mb' ), $this->post_type, 'normal', 'core' );
		add_meta_box( 'appica-pricings-button', __( 'Button', 'appica' ), array( $this, 'render_button_mb' ), $this->post_type, 'normal', 'core' );
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
		     || ! wp_verify_nonce( $_POST[ $this->nonce_field ], $this->nonce )
		) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Update price meta box
		if ( array_key_exists( $this->mb_price_name, $_POST ) ) {
			$meta_box_value = array_map( array( 'Appica_CPT_Pricings', 'sanitize_plans' ), $_POST[ $this->mb_price_name ] );
			update_post_meta( $post_id, $this->mb_price_name, $meta_box_value );
			unset( $meta_box_value );
		}

		// Update button meta box
		if ( array_key_exists( $this->mb_button_name, $_POST ) ) {
			$meta_box_value = Appica_CPT_Pricings::sanitize_button( $_POST[ $this->mb_button_name ] );
			update_post_meta( $post_id, $this->mb_button_name, $meta_box_value );
			unset( $meta_box_value );
		}

		// Update icon meta box
		if ( array_key_exists( $this->mb_icon_name, $_POST ) ) {
			$meta_box_value = sanitize_text_field( $_POST[ $this->mb_icon_name ] );
			update_post_meta( $post_id, $this->mb_icon_name, $meta_box_value );
			unset( $meta_box_value );
		}
	}

	/**
	 * Show meta box: icon
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post Post object
	 */
	public function render_icon_mb( $post ) {
		wp_nonce_field( $this->nonce, $this->nonce_field );

		$meta_box_value = get_post_meta( $post->ID, $this->mb_icon_name, true );

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
		<input type="hidden" name="<?php echo $this->mb_icon_name; ?>" class="appica-core-icon-val" value="<?php echo $meta_box_value; ?>">
		<div class="appica-core-icon-preview" style="display: <?php echo $dPreview; ?>;"><?php echo $preview; ?></div>
		<button type="button" class="button appica-core-icon-select" data-pack="flaticons"><?php _e( 'Select', 'appica' ); ?></button>
		<button type="button" class="button appica-core-icon-remove" style="display: <?php echo $dRemove; ?>;"><?php _e( 'Remove', 'appica' ); ?></button>
		</div><?php
	}

	/**
	 * Show meta box: Plan
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post Post object
	 */
	public function render_price_mb( $post ) {
		wp_nonce_field( $this->nonce, $this->nonce_field );

		$defaults = array(
			'terms' => array(),
			'color' => ''
		);

		$plans = get_post_meta( $post->ID, $this->mb_price_name, true );
		$plans = wp_parse_args( $plans, $defaults );

		echo '<p>', __( 'Add as many payment terms, as you want in Pricings > Time spans', 'appica' ),
			 '<br>', __( 'Note: if you have the same price in all payment periods, e.g. "Free", you can fill in only the first field', 'appica' ), '</p>';

		// Get all terms
		$terms = get_terms( $this->taxonomy, array(
			'orderby'      => 'id',
			'order'        => 'DESC',
			'hierarchical' => false,
			'hide_empty'   => false,
		) );

		if ( ! is_wp_error( $terms ) && is_array( $terms ) && 0 !== count( $terms ) ) {
			foreach ( (array) $terms as $term ) :
				$term_name = "{$this->mb_price_name}[terms][{$term->slug}]";
				$term_val = ( array_key_exists( $term->slug, $plans['terms'] ) )
					? esc_attr( $plans['terms'][ $term->slug ] )
					: '';

				?><div class="appica-core-pricings-control">
					<input type="text" name="<?php echo $term_name; ?>" class="form-control"
					       value="<?php echo $term_val; ?>"
					       placeholder="<?php _e( 'Price', 'appica' ); ?>">
					<span class="pricings-divider">/ <?php echo $term->slug; ?></span>
				</div><?php

				unset( $term_val, $term_id, $term_name );
			endforeach;
			unset( $term );
		}

		/*
		 * Display color box for price
		 */

		if ( $this->is_android ) :
			/**
			 * @var array Predefined color options
			 */
			$color_options = array(
				'default' => __( 'Default', 'appica' ),
				'primary' => __( 'Primary', 'appica' ),
				'success' => __( 'Success', 'appica' ),
				'info'    => __( 'Info', 'appica' ),
				'warning' => __( 'Warning', 'appica' ),
				'danger'  => __( 'Danger', 'appica' ),
				'ghost'   => __( 'Ghost', 'appica' )
			);

			echo '<p>', __( 'Pricing plan color', 'appica' ), '</p>'; ?>
			<select name="<?php echo $this->mb_price_name; ?>[color]" class="widefat"><?php

			foreach ( $color_options as $co => $con ) :
				printf( '<option value="%1$s"%3$s>%2$s</option>', $co, $con, selected( $co, $plans['color'], false ) );
			endforeach;

			?></select><?php
		endif;
	}

	/**
	 * Show meta box: button
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post Post object
	 */
	public function render_button_mb( $post ) {
		wp_nonce_field( $this->nonce, $this->nonce_field );

		/**
		 * @var array Default value
		 */
		$defaults = array(
			'url'    => '',
			'text'   => '',
			'target' => '',
			'active' => 'no',
			'color'  => 'default'
		);

		$button = get_post_meta( $post->ID, $this->mb_button_name, true );
		$button = wp_parse_args( $button, $defaults );

		echo '<div class="appica-single-settings">';

		// 1 - meta box name, 2 - field name (part), 3 - field value, 4 - field label
		$tpl_inp = '<label for="%2$s%1$s">%4$s</label><input type="text" name="%1$s[%2$s]" id="%2$s%1$s" class="widefat" value="%3$s">';
		// 1 - meta box name, 2 - field name (part), 3 - options, 4 - field label
		$tpl_sel = '<label for="%2$s%1$s">%4$s</label><select name="%1$s[%2$s]" id="%2$s%1$s" class="widefat">%3$s</select>';

		// URL
		printf( $tpl_inp, $this->mb_button_name, 'url', esc_attr( $button['url'] ), 'URL' );
		printf( $tpl_inp, $this->mb_button_name, 'text', esc_attr( $button['text'] ), 'Text' );

		/**
		 * @var array Options for target select
		 */
		$target_options = array(
			'_self'  => __( 'current tab', 'appica' ),
			'_blank' => __( 'new tab', 'appica' )
		);

		/**
		 * @var string Target options markup
		 */
		$_target_options = '';
		foreach ( $target_options as $to => $ton ) {
			// 1 - value, 2 - option name, 3 - selected
			$_target_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $to, $ton, selected( $to, $button['target'], false ) );
		}
		unset( $target_options, $to, $ton );

		printf( $tpl_sel, $this->mb_button_name, 'target', $_target_options, __( 'Open in..', 'appica' ) );

		/**
		 * If is Android theme - show plan color or active button for iOS
		 */

		if ( $this->is_android ) {
			/**
			 * @var array Predefined color options
			 */
			$color_options = array(
				'default' => __( 'Default', 'appica' ),
				'primary' => __( 'Primary', 'appica' ),
				'success' => __( 'Success', 'appica' ),
				'info'    => __( 'Info', 'appica' ),
				'warning' => __( 'Warning', 'appica' ),
				'danger'  => __( 'Danger', 'appica' )
			);

			/**
			 * @var string Active options markup
			 */
			$_color_options = '';
			foreach ( $color_options as $co => $con ) {
				$_color_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $co, $con, selected( $co, $button['color'], false ) );
			}
			unset( $color_options, $co, $con );

			printf( $tpl_sel, $this->mb_button_name, 'color', $_color_options, __( 'Button color', 'appica' ) );

		} else {
			/**
			 * @var array Active options for active select
			 */
			$active_options = array(
				'yes' => __( 'Yes', 'appica' ),
				'no'  => __( 'No', 'appica' )
			);

			/**
			 * @var string Active options markup
			 */
			$_active_options = '';
			foreach ( $active_options as $ao => $aon ) {
				$_active_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $ao, $aon, selected( $ao, $button['active'], false ) );
			}
			unset( $active_options, $ao, $aon );

			printf( $tpl_sel, $this->mb_button_name, 'active', $_active_options, __( 'Make button active?', 'appica' ) );
		}

		echo '</div>';
	}

	/**
	 * Sanitize "Pricings" price meta box
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $plan Single plan price
	 *
	 * @return mixed
	 */
	public static function sanitize_plans( $plan ) {
		$_plan = array();

		if ( is_array( $plan ) ) {
			foreach ( (array) $plan as $k => $v ) {
				$_plan[ $k ] = sanitize_text_field( $v );
			}
			unset( $k, $v );
		} else {
			$_plan = sanitize_text_field( $plan );
		}

		return $_plan;
	}

	/**
	 * Sanitize meta box: button for custom post type "Pricings"
	 * @param array $button
	 *
	 * @return array
	 */
	public static function sanitize_button( $button ) {
		/**
		 * @var array Return value
		 */
		$_button = array();
		/**
		 * @var array Button default values for preventing PHP warnings
		 */
		$defaults = array(
			'url'    => '',
			'text'   => '',
			'target' => '',
			'active' => 'no',
			'color'  => 'default'
		);

		$button = (array) $button;
		$button = wp_parse_args( $button, $defaults );

		foreach( $button as $k => $v ) {
			$_button[ $k ] = ( 'url' === $k ) ? esc_url_raw( $v ) : sanitize_text_field( $v );
		}
		unset( $k, $v );

		return $_button;
	}

	/**
	 * Remove column "Time Spans" from CPT "Pricings" screen
	 *
	 * @param array $columns Current Posts Screen columns
	 *
	 * @return array New Posts Screen columns.
	 */
	public function additional_posts_screen_columns( $columns ) {
		if( array_key_exists( 'taxonomy-appica_pricings_terms', $columns ) ) {
			unset( $columns['taxonomy-appica_pricings_terms'] );
		}

		return $columns;
	}
}