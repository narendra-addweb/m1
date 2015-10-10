<?php
/**
 * Visual Composer actions & filters
 *
 * @author 8guild
 * @package Appica 2
 */

if ( ! function_exists( 'appica_vc_before_init' ) ) :
	/**
	 * Setup Visual Composer for theme.
	 *
	 * Also, this function could be defined in theme "core" plugin.
	 */
	function appica_vc_before_init() {
		vc_disable_frontend();

		vc_set_as_theme( true );

		// Allow post by default
		vc_set_default_editor_post_types( array( 'page', 'post', 'appica_portfolio' ) );

		// Set path to directory where Visual Composer should look for template files for content elements.
		$dir = get_template_directory() . '/inc/vc_templates';
		vc_set_shortcodes_templates_dir( $dir );
	}

	add_action( 'vc_before_init', 'appica_vc_before_init' );

endif; // appica_vc_before_init

if ( ! function_exists( 'appica_vc_after_init' ) ) :
	/**
	 * Customize some Visual Composer default shortcodes
	 *
	 * Also, this function could be defined in theme "core" plugin.
	 */
	function appica_vc_after_init() {
		/*
		 * frequently used values
		 */
		$show_hide_value = array(
			__( 'Show', 'appica' ) => 'show',
			__( 'Hide', 'appica' ) => 'hide'
		);

		$left_right_value = array(
			__( 'Left', 'appica' )  => 'left',
			__( 'Right', 'appica' ) => 'right'
		);

		$enable_disable_value = array(
			__( 'Enable', 'appica' ) => 'enable',
			__( 'Disable', 'appica' ) => 'disable'
		);

		/**
		 * @var array Value for yes/no dropdown
		 */
		$value_yes_no = array(
			__( 'Yes', 'appica' ) => 'yes',
			__( 'No', 'appica' )  => 'no'
		);

		/**
		 * @var string Icon heading name
		 */
		$heading_icon = __( 'Icon', 'appica' );

		/**
		 * @var array Field "Icon Library" allow choosing different icons
		 */
		$field_icon_library = array(
			'type'       => 'dropdown',
			'param_name' => 'icon_lib',
			'heading'    => __( 'Icon library', 'appica' ),
			'std'        => 'fontawesome',
			'value'      => array(
				'Font Awesome' => 'fontawesome',
				'Open Iconic'  => 'openiconic',
				'Typicons'     => 'typicons',
				'Entypo'       => 'entypo',
				'Linecons'     => 'linecons',
				'Flaticons'    => 'flaticons'
			)
		);

		/**
		 * @var array Icon from Font Awesome pack. Depends on {@see $field_icon_library}
		 */
		$field_icon_fontawesome = array(
			'type'       => 'iconpicker',
			'param_name' => 'icon_fontawesome',
			'heading'    => $heading_icon,
			'settings'   => array(
				'emptyIcon'    => true,
				'iconsPerPage' => 200
			),
			'dependency' => array(
				'element' => 'icon_lib',
				'value'   => 'fontawesome'
			)
		);

		/**
		 * @var array Icon from Openiconic pack. Depends on {@see $field_icon_library}
		 */
		$field_icon_openiconic = array(
			'type'       => 'iconpicker',
			'param_name' => 'icon_openiconic',
			'heading'    => $heading_icon,
			'settings'   => array(
				'type'         => 'openiconic',
				'emptyIcon'    => true,
				'iconsPerPage' => 200
			),
			'dependency' => array(
				'element' => 'icon_lib',
				'value'   => 'openiconic'
			)
		);

		/**
		 * @var array Icon from Typicons pack. Depends on {@see $field_icon_library}
		 */
		$field_icon_typicons = array(
			'type'       => 'iconpicker',
			'param_name' => 'icon_typicons',
			'heading'    => $heading_icon,
			'settings'   => array(
				'type'         => 'typicons',
				'emptyIcon'    => true,
				'iconsPerPage' => 200
			),
			'dependency' => array(
				'element' => 'icon_lib',
				'value'   => 'typicons'
			)
		);

		/**
		 * @var array Icon from Entypo pack. Depends on {@see $field_icon_library}
		 */
		$field_icon_entypo = array(
			'type'       => 'iconpicker',
			'param_name' => 'icon_entypo',
			'heading'    => $heading_icon,
			'settings'   => array(
				'type'         => 'entypo',
				'emptyIcon'    => true,
				'iconsPerPage' => 300
			),
			'dependency' => array(
				'element' => 'icon_lib',
				'value'   => 'entypo'
			)
		);

		/**
		 * @var array Icon from Linecons pack. Depends on {@see $field_icon_library}
		 */
		$field_icon_linecons = array(
			'type'       => 'iconpicker',
			'param_name' => 'icon_linecons',
			'heading'    => $heading_icon,
			'settings'   => array(
				'type'         => 'linecons',
				'emptyIcon'    => false,
				'iconsPerPage' => 200
			),
			'dependency' => array(
				'element' => 'icon_lib',
				'value'   => 'linecons'
			)
		);

		/**
		 * @var array Icon from Flaticons pack. Depends on {@see $field_icon_library}
		 */
		$field_icon_flaticons = array(
			'type'       => 'iconpicker',
			'param_name' => 'icon_flaticons',
			'heading'    => $heading_icon,
			'settings'   => array(
				'type'         => 'flaticons',
				'emptyIcon'    => true,
				'iconsPerPage' => 350
			),
			'dependency' => array(
				'element' => 'icon_lib',
				'value'   => 'flaticons'
			)
		);

		/*
		 * Add "Caption" tab with parameters to vc_single_image
		 */
		$caption = __( 'Caption', 'appica' );
		$vc_single_image_caption = array(
			array(
				'type'        => 'checkbox',
				'param_name'  => 'is_caption_used',
				'heading'     => __( 'Use caption?', 'appica' ),
				'description' => __( 'Add a fancy small description under the image', 'appica' ),
				'group'       => $caption,
				'value'       => array( __( 'Yes', 'appica' ) => 'yes' )
			),
			array(
				'type'        => 'textfield',
				'param_name'  => 'caption_line',
				'heading'     => __( 'Enter your caption line', 'appica' ),
				'description' => __( 'Leave this field empty if you want to show the caption from media library.', 'appica' ),
				'group'       => $caption
			),
			array(
				'type'       => 'dropdown',
				'param_name' => 'caption_align',
				'heading'    => __( 'Caption alignment', 'appica' ),
				'group'       => $caption,
				'std'        => 'right',
				'value'      => $left_right_value
			),
			array(
				'type'        => 'textfield',
				'param_name'  => 'caption_class',
				'heading'     => __( 'Caption extra class name', 'appica' ),
				'description' => __( 'Add extra classes, if you wish to style particular content element differently.', 'appica' ),
				'group'       => $caption
			)
		);
		vc_add_params( 'vc_single_image', $vc_single_image_caption );

		/*
		 * Add "Badge" tab with parameters to vc_row & vc_row_inner
		 */
		$badge = __( 'Badge', 'appica' );
		$vc_row_badge = array(
			array(
				'type'        => 'dropdown',
				'param_name'  => 'badge',
				'heading'     => __( 'Display Badge', 'appica' ),
				'description' => __( 'N.B. To use badge "container" on "General" tab must set to "yes".', 'appica' ),
				'group'       => $badge,
				'std'         => 'hide',
				'value'       => $show_hide_value
			),
			array(
				'type'       => 'dropdown',
				'param_name' => 'badge_align',
				'heading'    => __( 'Align', 'appica' ),
				'group'      => $badge,
				'std'        => 'left',
				'value'      => $left_right_value
			),
			array(
				'type'        => 'textfield',
				'param_name'  => 'badge_title',
				'heading'     => __( 'Badge Title', 'appica' ),
				'description' => __( 'Will be shown near the badge. How text will be aligned depends on previous param.', 'appica' ),
				'group'       => $badge
			),
			array_merge( $field_icon_library, array( 'group' => $badge ) ),
			array_merge( $field_icon_fontawesome, array( 'group' => $badge ) ),
			array_merge( $field_icon_openiconic, array( 'group' => $badge ) ),
			array_merge( $field_icon_typicons, array( 'group' => $badge ) ),
			array_merge( $field_icon_entypo, array( 'group' => $badge ) ),
			array_merge( $field_icon_linecons, array( 'group' => $badge ) ),
			array_merge( $field_icon_flaticons, array( 'group' => $badge ) ),
			array(
				'type'               => 'dropdown',
				'param_name'         => 'badge_pc',
				'heading'            => __( 'Predefined Gradient Color', 'appica' ),
				'description'        => __( 'Choose one from predefined colors', 'appica' ),
				'param_holder_class' => 'appica-badge-color',
				'group'              => $badge,
				'std'                => 'default',
				'value'              => array(
					__( 'Default', 'appica' ) => 'default',
					__( 'Color 1', 'appica' ) => 'alt-color',
					__( 'Color 2', 'appica' ) => 'alt-color-2',
					__( 'Color 3', 'appica' ) => 'alt-color-3',
					__( 'Color 4', 'appica' ) => 'alt-color-4'
				)
			),
			array(
				'type'        => 'colorpicker',
				'param_name'  => 'badge_cc',
				'heading'     => __( 'Custom Color', 'appica' ),
				'description' => __( 'This param overrides previous.', 'appica' ),
				'group'       => $badge
			),
			array(
				'type'        => 'colorpicker',
				'param_name'  => 'badge_btc',
				'heading'     => __( 'Border Top Color', 'appica' ),
				'description' => __( 'Custom fancy line above badge. Just leave empty if you don\'t want to see it.', 'appica' ),
				'group'       => $badge
			),
			array(
				'type'        => 'colorpicker',
				'param_name'  => 'badge_tc',
				'heading'     => __( 'Text Color', 'appica' ),
				'description' => __( 'Customize your badges text color!', 'appica' ),
				'value'       => '#bebebe',
				'group'       => $badge
			),
			array(
				'type'        => 'textfield',
				'param_name'  => 'badge_class',
				'heading'     => __( 'Extra class name', 'appica' ),
				'description' => __( 'Add extra classes, if you wish to style particular content element differently.', 'appica' ),
				'group'       => $badge
			)
		);
		vc_add_params( 'vc_row', $vc_row_badge );
		vc_add_params( 'vc_row_inner', $vc_row_badge );

		/*
		 * Add "Content color" param to vc_row
		 */
		vc_add_param( 'vc_row', array(
			'type'        => 'dropdown',
			'param_name'  => 'content_color',
			'heading'     => __( 'Content color', 'appica' ),
			'description' => __( 'You can adjust this color in Global Colors Settings: for dark customize Body Text Color / for light customize Light Text Color', 'appica' ),
			'std'         => 'dark',
			'value'       => array(
				__( 'Light', 'appica' ) => 'light',
				__( 'Dark', 'appica' )  => 'dark'
			)
		) );

		/**
		 * Add "ID" field to vc_row params
		 *
		 * @since 1.1.0 Rename param and change description
		 * @since 1.0.0
		 */
		vc_add_param( 'vc_row', array(
			'type'        => 'textfield',
			'param_name'  => 'uniq_id',
			'heading'     => __( 'Row ID', 'appica' ),
			'description' => __( 'Enter row ID (Note: make sure it is unique and valid according to <a href="http://www.w3schools.com/tags/att_global_id.asp" target="_blank">w3c specification</a>). Also used for anchored navigation.', 'appica' ),
			'value'       => 'r' . uniqid()
		) );

		/*
		 * Add "Container" dropdown to vc_row
		 */
		vc_add_param( 'vc_row', array(
			'type'       => 'dropdown',
			'param_name' => 'is_container',
			'heading'    => __( 'Add container to row', 'appica' ),
			'std'        => 'yes',
			'value'      => $value_yes_no
		) );

		/*
		 * Add "Overlay" tab to vc_row shortcode
		 */
		$overlay = __( 'Overlay', 'appica' );
		$vc_row_overlay = array(
			array(
				'type'        => 'dropdown',
				'param_name'  => 'overlay',
				'heading'     => $overlay,
				'description' => __( 'Enable/Disable overlay', 'appica' ),
				'group'       => $overlay,
				'std'         => 'disable',
				'value'       => $enable_disable_value
			),
			array(
				'type'        => 'dropdown',
				'param_name'  => 'overlay_partial',
				'heading'     => __( 'Use partial overlay', 'appica' ),
				'description' => __( 'Partial overlay covers only a half of block', 'appica' ),
				'group'       => $overlay,
				'std'         => 'no',
				'value'       => $value_yes_no
			),
			array(
				'type'       => 'dropdown',
				'param_name' => 'overlay_type',
				'heading'    => __( 'Color type', 'appica' ),
				'group'      => $overlay,
				'std'        => 'gradient',
				'value'      => array(
					__( 'Solid', 'appica' )    => 'solid',
					__( 'Gradient', 'appica' ) => 'gradient'
				)
			),
			array(
				'type'       => 'colorpicker',
				'param_name' => 'overlay_sc',
				'heading'    => __( 'Choose solid color', 'appica' ),
				'group'      => $overlay,
				'dependency' => array(
					'element' => 'overlay_type',
					'value'   => 'solid'
				)
			),
			array(
				'type'       => 'colorpicker',
				'param_name' => 'overlay_gc_start',
				'class' => 'appica-core-overlay-gr-start',
				'heading'    => __( 'Start gradient color', 'appica' ),
				'group'      => $overlay,
				'dependency' => array(
					'element' => 'overlay_type',
					'value'   => 'gradient'
				)
			),
			array(
				'type'       => 'colorpicker',
				'param_name' => 'overlay_gc_end',
				'heading'    => __( 'End gradient color', 'appica' ),
				'group'      => $overlay,
				'dependency' => array(
					'element' => 'overlay_type',
					'value'   => 'gradient'
				)
			),
			array(
				'type'        => 'textfield',
				'param_name'  => 'overlay_opacity',
				'heading'     => __( 'Opacity, %', 'appica' ),
				'description' => __( 'From 0 to 100', 'appica' ),
				'group'       => $overlay,
				'value'       => 70
			)
		);
		vc_add_params( 'vc_row', $vc_row_overlay );

		/*
		 * Remove "Row stretch" param from vc_row
		 * Make .vc_row always full width
		 */
		vc_remove_param( 'vc_row', 'full_width' );

		/*
		 * Remove params "title" and "interval" from vc_tour & vc_tabs shortcode
		 */
		vc_remove_param( 'vc_tour', 'title' );
		vc_remove_param( 'vc_tour', 'interval' );
		vc_remove_param( 'vc_tabs', 'title' );
		vc_remove_param( 'vc_tabs', 'interval' );

		/**
		 * @var array "Title" shortcode param
		 */
		$appica_field_title = array(
			'type'        => 'textfield',
			'param_name'  => 'title',
			'weight'      => 10,
			'heading'     => __( 'Title', 'appica' ),
			'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', 'appica' )
		);

		/*
		 * Change weight of "title" param in vc_tour & vc_tabs shortcode
		 */
		vc_add_param( 'vc_tour', $appica_field_title );
		vc_add_param( 'vc_tabs', $appica_field_title );

		/**
		 * @var array "Subtitle" shortcode param
		 */
		$appica_field_subtitle = array(
			'type'       => 'textfield',
			'param_name' => 'subtitle',
			'weight'     => 9,
			'heading'    => __( 'Subtitle', 'appica' )
		);

		/*
		 * Add "Subtitle" param to vc_tabs & vc_tour
		 */
		vc_add_param( 'vc_tour', $appica_field_subtitle );
		vc_add_param( 'vc_tabs', $appica_field_subtitle );

		/**
		 * @var array Value left/right for shortcode params
		 */
		$appica_value_left_right = array(
			__( 'Left', 'appica' )   => 'left',
			__( 'Right', 'appica' )  => 'right'
		);

		/**
		 * @var array "Text Align" shortcode param
		 */
		$appica_text_align = array(
			'type'        => 'dropdown',
			'param_name'  => 'text_align',
			'weight'      => 7,
			'heading'     => __( 'Text align', 'appica' ),
			'description' => __( 'Applied only for "tab" column', 'appica' ),
			'std'         => 'left',
			'value'       => array_merge( $appica_value_left_right, array( __( 'Center', 'appica' ) => 'center' ) )
		);

		/*
		 * Add "Text align" param to vc_tour & vc_tabs
		 */
		vc_add_param( 'vc_tabs', $appica_text_align );
		vc_add_param( 'vc_tour', $appica_text_align );

		/**
		 * @var array Content position for vc_tour & vc_tabs shortcodes
		 */
		$appica_content_position = array(
			'type'        => 'dropdown',
			'param_name'  => 'position',
			'weight'      => 6,
			'heading'     => __( 'Content position', 'appica' ),
			'description' => __( 'Choose your content column position', 'appica' ),
			'std'         => 'right',
			'value'       => $appica_value_left_right
		);

		/*
		 * Add "Content Position" param to vc_tour & vc_tabs
		 */
		vc_add_param( 'vc_tour', $appica_content_position );
		vc_add_param( 'vc_tabs', $appica_content_position );

		/*
		 * Add "Description" param to vc_tabs only
		 */
		vc_add_param( 'vc_tabs', array(
			'type'       => 'textarea',
			'param_name' => 'description',
			'weight'     => 8,
			'heading'    => __( 'Description', 'appica' )
		) );

		/*
		 * Add "transition" param to vc_tab
		 */
		vc_add_param( 'vc_tab', array(
			'type'       => 'dropdown',
			'param_name' => 'transition',
			'heading'    => __( 'Animation effect', 'appica' ),
			'std'        => 'top',
			'value'      => array(
				__( 'Fade', 'appica' )     => 'fade_',
				__( 'Scale', 'appica' )    => 'scale',
				__( 'Scale Up', 'appica' ) => 'scaleup',
				__( 'Top', 'appica' )      => 'top',
				__( 'Bottom', 'appica' )   => 'bottom',
				__( 'Left', 'appica' )     => 'left',
				__( 'Right', 'appica' )    => 'right',
				__( 'Flip', 'appica' )     => 'flip'
			)
		) );

		/*
		 * Add "Offset" param to vc_column_inner
		 */
		vc_add_param( 'vc_column_inner', array(
			'type'        => 'column_offset',
			'param_name'  => 'offset',
			'heading'     => __( 'Responsiveness', 'appica' ),
			'group'       => __( 'Width & Responsiveness', 'appica' ),
			'description' => __( 'Adjust column for different screen sizes. Control width, offset and visibility settings.', 'appica' )
		) );

		/*
		 * Remove unnecessary & unsupported shortcodes
		 */
		vc_remove_element( 'vc_custom_heading' );
		vc_remove_element( 'vc_posts_grid' );
		vc_remove_element( 'vc_media_grid' );
		vc_remove_element( 'vc_masonry_grid' );
		vc_remove_element( 'vc_masonry_media_grid' );
		vc_remove_element( 'vc_basic_grid' );
		vc_remove_element( 'vc_facebook' );
		vc_remove_element( 'vc_tweetmeme' );
		vc_remove_element( 'vc_googleplus' );
		vc_remove_element( 'vc_pinterest' );
		vc_remove_element( 'vc_cta_button' );
		vc_remove_element( 'vc_cta_button2' );
		vc_remove_element( 'vc_flickr' );
		vc_remove_element( 'vc_toggle' );
		vc_remove_element( 'vc_gallery' );
		vc_remove_element( 'vc_posts_slider' );
		vc_remove_element( 'vc_wp_categories' );
		vc_remove_element( 'vc_wp_posts' );
		vc_remove_element( 'vc_wp_meta' );
		vc_remove_element( 'vc_wp_calendar' );
		vc_remove_element( 'vc_wp_tagcloud' );
		vc_remove_element( 'vc_wp_rss' );

		/**
		 * Remove vc_row params:
		 * - parallax
		 * - el_id
		 *
		 * Because Appica 2 has own implementation of vc_row
		 * and does not support these features.
		 *
		 * @since 1.1.0
		 */
		vc_remove_param( 'vc_row', 'parallax' );
		vc_remove_param( 'vc_row', 'el_id' );

		/**
		 * Add new param "Device color" to "Gadgets Slideshow"
		 * Only for iOS version
		 *
		 * @since 1.2.0
		 */
		vc_add_param( 'appica_gadgets_slideshow', array(
			'type'        => 'dropdown',
			'param_name'  => 'color',
			'heading'     => __( 'Devices color', 'appica' ),
			'description' => __( 'This option affects iPad and iPhone device', 'appica' ),
			'std'         => 'gold',
			'value'       => array(
				__( 'Gold', 'appica' )       => 'gold',
				__( 'Silver', 'appica' )     => 'silver',
				__( 'Space Gray', 'appica' ) => 'space-gray'
			)
		) );

		/**
		 * Remove vc_row "parallax_image" param
		 *
		 * @since 1.3.0
		 */
		vc_remove_param( 'vc_row', 'parallax_image' );
	}

	add_action( 'vc_after_init', 'appica_vc_after_init' );

endif; // appica_vc_after_init


/**
 * Add VC default templates for iOS version
 *
 * @since 1.0.0
 */
function appica_vc_default_templates( $templates ) {

	/**
	 * @var string Path to templates icon
	 */
	$icon = preg_replace( '/\s/', '%20', appica_image_uri( 'img/layouts.jpg', false ) );

	/*
	 * Page: About
	 */
	$data = array(
		'name'         => __( 'Appica page: About', 'appica' ),
		'weight'       => 999,
		'image_path'   => $icon,
		'custom_class' => 'appica-custom-template appica-page-about',
		'content'      => '[vc_row badge_align="left" badge_pc="default" badge_fs="12" content_color="light" uniq_id="timeline" is_container="yes" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" el_class="fw-bg partial-overlay padding-top-2x padding-bottom-2x" badge_tc="#bebebe" css=".vc_custom_1429707101054{background-image: url(http://the8guild.com/themes/wordpress/appica2/ios/wp-content/uploads/sites/3/2015/04/about1.jpg?id=968) !important;}" badge="hide" overlay="enable" overlay_partial="yes" overlay_type="gradient" overlay_gc_start="#ff2d54" overlay_gc_end="#d711ff" overlay_opacity="94"][vc_column width="2/3" offset="vc_col-lg-4 vc_col-md-6"][appica_timeline title="Company" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero eum dolores laudantium veritatis, recusandae pariatur velit doloribus fugiat reprehenderit qui assumenda ullam vitae."][/vc_column][vc_column width="1/3" offset="vc_col-lg-8 vc_col-md-6 vc_hidden-xs"][appica_custom_title align="right" title="About"][/vc_column][/vc_row][vc_row badge="hide" badge_align="left" badge_pc="default" badge_fs="12" content_color="dark" uniq_id="super-id" is_container="yes" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70" el_class="padding-top-3x padding-bottom" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_tc="#bebebe"][vc_column width="1/3" css=".vc_custom_1426505175204{margin-bottom: 46px !important;}"][appica_fancy_text text="3108"][vc_column_text]US dollars is an average monthly sallary paid out to employee in 2014. And it is growing.[/vc_column_text][/vc_column][vc_column width="1/3" css=".vc_custom_1426505182338{margin-bottom: 46px !important;}"][appica_fancy_text text="241"][vc_column_text]Extremely talented individuals has been grown under our roof. Professional designers &amp; developers.[/vc_column_text][/vc_column][vc_column width="1/3"][appica_fancy_text text="Millions"][vc_column_text]Since its initial release, app has been downloaded 5+ million times and number is growing.[/vc_column_text][/vc_column][/vc_row][vc_row badge="show" badge_align="left" badge_title="Moments of our life" badge_pc="alt-color-2" badge_fs="12" content_color="dark" uniq_id="gallery" is_container="yes" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70" badge_btc="#cccccc" icon_lib="flaticons" icon_linecons="vc_li vc_li-heart" icon_flaticons="flaticon-camera41" icon_fontawesome="fa fa-camera" el_class="padding-bottom" badge_tc="#bebebe"][vc_column width="1/1"][appica_gallery is_cat="yes" title="Our Gallery" subtitle="Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque."][/vc_column][/vc_row][vc_row badge="hide" badge_align="left" badge_pc="primary" badge_fs="12" content_color="dark" uniq_id="achievments" is_container="yes" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_tc="#bebebe"][vc_column css=".vc_custom_1426171151135{margin-top: 24px !important;}" width="1/1"][vc_row_inner badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="default" badge_tc="#bebebe"][vc_column_inner width="5/12" offset="vc_col-md-4"][appica_custom_title align="right" title="Some Figures? A Lot Of Work" subtitle="Has been done"][/vc_column_inner][vc_column_inner width="7/12" el_class="padding-top" offset="vc_col-md-offset-1 vc_col-md-5"][vc_column_text]Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus, reprehenderit a vel distinctio inventore alias pariatur debitis itaque reiciendis at voluptas eum, eligendi unde ad commodi nostrum.[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner el_class="space-top" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="default" badge_tc="#bebebe"][vc_column_inner width="1/1"][appica_bar_charts first_title="1455" first_subtitle="Pixels" first_percent="88" first_color="#4cd964" second_title="67%" second_subtitle="Love" second_percent="67" second_color="#ffcc00" third_title="911" third_subtitle="Codelines" third_percent="100" third_color="#5ac8fa"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row el_class="fw-white-bg padding-top-3x padding-bottom-3x" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="default" badge_tc="#bebebe" content_color="dark" uniq_id="loved-by" is_container="no" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70"][vc_column width="1/1"][vc_row_inner el_class="space-bottom" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="primary" badge_tc="#bebebe"][vc_column_inner width="5/12" offset="vc_col-md-offset-8 vc_col-md-4 vc_col-sm-offset-7"][appica_custom_title align="left" title="Loved by" subtitle="Some trusted companies"][/vc_column_inner][/vc_row_inner][vc_row_inner el_class="padding-bottom" badge="hide" badge_align="left" icon_lib="flaticons" icon_linecons="vc_li vc_li-heart" badge_pc="primary" badge_tc="#bebebe"][vc_column_inner width="1/6" offset="vc_col-sm-offset-1 vc_col-xs-4"][vc_single_image image="846" border_color="grey" img_link_large="" img_link_target="_self" is_caption_used="" caption_align="right" img_size="full"][/vc_column_inner][vc_column_inner width="1/6" offset="vc_col-xs-4"][vc_single_image image="847" border_color="grey" img_link_large="" img_link_target="_self" is_caption_used="" caption_align="right" img_size="full"][/vc_column_inner][vc_column_inner width="1/6" offset="vc_col-xs-4"][vc_single_image image="848" border_color="grey" img_link_large="" img_link_target="_self" is_caption_used="" caption_align="right" img_size="full"][/vc_column_inner][vc_column_inner width="1/6" offset="vc_col-xs-4"][vc_single_image image="849" border_color="grey" img_link_large="" img_link_target="_self" is_caption_used="" caption_align="right" img_size="full"][/vc_column_inner][vc_column_inner width="1/6" offset="vc_col-xs-4"][vc_single_image image="850" border_color="grey" img_link_large="" img_link_target="_self" is_caption_used="" caption_align="right" img_size="full"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row badge="show" badge_align="right" badge_title="Smart people in here" badge_pc="alt-color-2" badge_fs="12" content_color="dark" uniq_id="team" is_container="yes" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70" css=".vc_custom_1429693212855{border-top-width: 1px !important;border-top-color: #cccccc !important;border-top-style: solid !important;}" icon_lib="flaticons" icon_linecons="vc_li vc_li-heart" icon_flaticons="flaticon-brain1" el_class="fw-bg space-bottom-3x" badge_tc="#bebebe"][vc_column width="1/1"][appica_team_alt][/vc_column][/vc_row][vc_row css=".vc_custom_1429693283794{background-color: #ffffff !important;}" badge="hide" badge_align="left" badge_pc="default" badge_fs="12" content_color="dark" uniq_id="contacts" is_container="no" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_tc="#bebebe"][vc_column width="1/1"][appica_contacts zoom="14" is_scroll="disable" title="Where You Can Find Us" subtitle="In Odessa of course" location="Kyivs\'kyi District, Odessa" marker="909" is_zoom="enable"]<a href="mailto:support@appica.com">support@appica.com</a> support team <a href="mailto:job@appica.com">job@appica.com</a> Job opportunities Kyivskiy District Odessa Region, Odessa 65000[/appica_contacts][/vc_column][/vc_row][vc_row el_class="padding-bottom-2x" badge="show" badge_align="right" badge_title="Drop us a line" badge_pc="alt-color-2" badge_fs="12" content_color="dark" uniq_id="contact-form" is_container="yes" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70" icon_lib="flaticons" icon_linecons="vc_li vc_li-heart" icon_flaticons="flaticon-mail21" badge_tc="#bebebe"][vc_column width="5/6" offset="vc_col-md-offset-2 vc_col-md-8 vc_col-sm-offset-1"][contact-form-7 id="559"][/vc_column][/vc_row]'
	);

	vc_add_default_templates( $data );

	/*
	 * Page: Press
	 */
	$data = array(
		'name'         => __( 'Appica page: Press', 'appica' ),
		'weight'       => 1,
		'image_path'   => $icon,
		'custom_class' => 'appica-custom-template appica-page-press',
		'content'      => '[vc_row badge="hide" badge_align="left" badge_pc="default" badge_fs="12" content_color="dark" uniq_id="press" is_container="yes" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70" el_class="space-top-3x" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_tc="#bebebe"][vc_column width="1/1"][vc_row_inner][vc_column_inner width="1/1"][appica_custom_title align="left" title="People Talking About Appica 2"][/vc_column_inner][/vc_row_inner][vc_row_inner el_class="space-top" badge="hide" badge_align="left" badge_pc="color1" badge_fs="12"][vc_column_inner width="1/1" offset="vc_col-lg-9 vc_col-md-9"][appica_posts loop="size:10|order_by:date|order:DESC|post_type:post|categories:6"][/vc_column_inner][vc_column_inner width="1/3" offset="vc_col-lg-offset-1 vc_col-lg-2 vc_col-md-3 vc_hidden-sm vc_hidden-xs"][vc_single_image image="8" border_color="grey" img_link_large="" img_link_target="_self" is_caption_used="" caption_align="right" img_size="full" alignment="center"][vc_single_image image="907" border_color="grey" img_link_large="" img_link_target="_self" is_caption_used="" caption_align="right" img_size="full" alignment="center"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row el_class="space-top-2x padding-bottom-2x" badge="hide" badge_align="left" badge_pc="default" badge_fs="12" content_color="dark" uniq_id="news" is_container="yes" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_tc="#bebebe"][vc_column width="1/1"][vc_row_inner][vc_column_inner width="1/3" offset="vc_col-md-offset-9 vc_col-md-3 vc_col-sm-offset-8"][appica_custom_title align="left" title="News" subtitle="Fresh and tasty"][/vc_column_inner][/vc_row_inner][vc_row_inner el_class="space-top" badge="hide" badge_align="left" badge_pc="color1" badge_fs="12"][vc_column_inner width="1/1"][appica_news more="More..."][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]'
	);

	vc_add_default_templates( $data );

	/*
	 * Page: Legal
	 */
	$data = array(
		'name'         => __( 'Appica page: Legal', 'appica' ),
		'weight'       => 1,
		'image_path'   => $icon,
		'custom_class' => 'appica-custom-template appica-page-legal',
		'content'      => '[vc_row badge="hide" badge_align="left" icon_lib="flaticons" icon_linecons="vc_li vc_li-heart" badge_pc="default" badge_tc="#bebebe" content_color="dark" uniq_id="legal-title" is_container="yes" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70"][vc_column width="1/1"][vc_column_text]<h1>Before using Appica 2, there are a few things you should know</h1>[/vc_column_text][/vc_column][/vc_row][vc_row el_class="space-top-2x padding-bottom-2x" badge="show" badge_align="left" badge_title=" Last updated: January 15, 2014" icon_lib="flaticons" icon_linecons="vc_li vc_li-heart" icon_flaticons="flaticon-correct7" badge_pc="alt-color-4" content_color="dark" uniq_id="legal" is_container="yes" css=".vc_custom_1429691298262{border-top-width: 1px !important;border-top-color: #cccccc !important;border-top-style: solid !important;}" badge_tc="#bebebe" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70"][vc_column width="3/4" offset="vc_col-lg-offset-3 vc_col-lg-6 vc_col-md-offset-2 vc_col-md-7"][vc_column_text]Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo.<h3><span class="text-gray">1 BASIC INFORMATION</span></h3>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo.<h3><span class="text-gray">2 APP UPDATES</span></h3><span class="text-uppercase text-danger text-semibold">LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISICING ELIT, SED DO EIUSMOD TEMPOR INCIDIDUNT UT LABORE ET DOLORE MAGNA ALIQUA.</span> Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.<h3><span class="text-gray">3 PROVISION OF THE SERVICE</span></h3>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni.[/vc_column_text][/vc_column][vc_column width="1/4"][vc_column_text]<p style="text-align: right;"><a class="text-smaller" href="mailto:support@appica.com">support@appica.com</a></p><p style="text-align: right;">Fulltime support</p>[/vc_column_text][/vc_column][/vc_row]'
	);

	vc_add_default_templates( $data );

	/*
	 * Page: Home
	 */
	$data = array(
		'name'         => __( 'Appica page: Home', 'appica' ),
		'weight'       => 1,
		'image_path'   => $icon,
		'custom_class' => 'appica-custom-template appica-page-home',
		'content'      => '[vc_row css=".vc_custom_1429695977248{background-image: url(http://the8guild.com/themes/wordpress/appica2/ios/wp-content/uploads/sites/3/2015/04/features-bg.jpg?id=911) !important;}" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="default" badge_tc="#bebebe" content_color="light" uniq_id="features" is_container="no" el_class="fw-bg bg-align-bottom" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70"][vc_column width="1/1"][appica_gadgets_slideshow is_sl="disable" sl_interval="3000" title="Why is it special?" subtitle="Look what this app has to offer"][/vc_column][/vc_row][vc_row css=".vc_custom_1429696127798{background-image: url(http://the8guild.com/themes/wordpress/appica2/ios/wp-content/uploads/sites/3/2015/04/space.jpg?id=912) !important;}" badge="show" badge_align="right" badge_title="No other app has this" icon_lib="flaticons" icon_linecons="vc_li vc_li-heart" icon_flaticons="flaticon-star51" badge_pc="default" badge_tc="#bebebe" content_color="light" uniq_id="features-list" is_container="yes" el_class="fw-bg padding-bottom-2x" overlay="enable" overlay_partial="no" overlay_type="gradient" overlay_opacity="78" overlay_gc_start="#494949" overlay_gc_end="#313131"][vc_column width="1/1" offset="vc_col-lg-offset-1 vc_col-lg-10"][vc_row_inner][vc_column_inner width="1/2" css=".vc_custom_1429627086900{margin-bottom: 30px !important;}"][appica_feature link_text="See more" align="right" icon_lib="flaticons" icon_size="large" icon_pos="right" icon_va="middle" title="Handcrafted UX" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas harum a aperiam pariatur totam impedit, sint quibusdam minus deserunt labore ipsum commodo." link="url:http%3A%2F%2Fthe8guild.com%2Fthemes%2Fwordpress%2Fappica2%2Fios%2Fdesigned-with-love%2F|title:Designed%20with%20love|" icon_linecons="vc_li vc_li-heart" icon_flaticons="flaticon-ruler9"][/vc_column_inner][vc_column_inner width="1/2"][appica_feature link_text="See more" align="left" icon_lib="flaticons" icon_size="large" icon_pos="left" icon_va="middle" title="Smart Design" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas harum a aperiam pariatur totam impedit, sint quibusdam minus deserunt labore ipsum commodo." link="url:http%3A%2F%2Fthe8guild.com%2Fthemes%2Fwordpress%2Fappica2%2Fios%2Fdesigned-with-love%2F|title:Designed%20with%20love|" icon_linecons="vc_li vc_li-heart" icon_flaticons="flaticon-brain1"][/vc_column_inner][/vc_row_inner][vc_row_inner el_class="space-top-2x" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="primary" badge_tc="#bebebe"][vc_column_inner width="1/2" css=".vc_custom_1429627099192{margin-bottom: 30px !important;}"][appica_feature link_text="See more" align="right" icon_lib="flaticons" icon_size="large" icon_pos="right" icon_va="middle" title="Free Cloud Storage" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas harum a aperiam pariatur totam impedit, sint quibusdam minus deserunt labore ipsum commodo." link="url:http%3A%2F%2Fthe8guild.com%2Fthemes%2Fwordpress%2Fappica2%2Fios%2Fhow-to-make-design%2F|title:How%20To%20Make%20Design%3F|" icon_linecons="vc_li vc_li-heart" icon_flaticons="flaticon-cloud79"][/vc_column_inner][vc_column_inner width="1/2"][appica_feature link_text="See more" align="left" icon_lib="flaticons" icon_size="large" icon_pos="left" icon_va="middle" title="Easy Photo Sharing" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas harum a aperiam pariatur totam impedit, sint quibusdam minus deserunt labore ipsum commodo." link="url:http%3A%2F%2Fthe8guild.com%2Fthemes%2Fwordpress%2Fappica2%2Fios%2Fhow-to-make-design%2F|title:How%20To%20Make%20Design%3F|" icon_linecons="vc_li vc_li-heart" icon_flaticons="flaticon-photo31"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row el_class="gallery padding-top-3x padding-bottom-3x" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="default" badge_tc="#bebebe" content_color="dark" uniq_id="gallery" is_container="yes" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70"][vc_column width="1/1"][vc_tour text_align="right" position="right" title="App Gallery" subtitle="The best way to show off"][vc_tab title="App Screenshots" tab_id="6926a76a-0879-9baa3-f815" transition="fade_"][appica_app_gallery][/vc_tab][vc_tab title="Video Preview" tab_id="a601a09a-4bd7-6baa3-f815" transition="top"][vc_video link="http://vimeo.com/113575647"][/vc_tab][vc_tab title="Prototype" tab_id="1428568366776-2-9baa3-f815" transition="flip"][vc_single_image image="913" border_color="grey" img_link_large="" img_link_target="_self" is_caption_used="" caption_align="right" img_size="large"][/vc_tab][/vc_tour][/vc_column][/vc_row][vc_row el_class="fw-bg bg-align-bottom" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="default" badge_tc="#bebebe" content_color="light" uniq_id="video" is_container="yes" css=".vc_custom_1429705318980{background-image: url(http://the8guild.com/themes/wordpress/appica2/ios/wp-content/uploads/sites/3/2015/04/photodune-9146072-man-in-front-of-computer-screen-l.jpg?id=949) !important;}" overlay="enable" overlay_partial="no" overlay_type="gradient" overlay_opacity="67" overlay_gc_start="#00ff80" overlay_gc_end="#0077ff"][vc_column width="1/1"][appica_video_popup text="Video Presentation" video="http://vimeo.com/113575647"][/vc_column][/vc_row][vc_row el_class="fw-gray-bg padding-top-3x padding-bottom-3x" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="default" badge_tc="#bebebe" content_color="dark" uniq_id="posts" is_container="yes" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70"][vc_column width="1/3" offset="vc_col-md-3"][appica_custom_title align="right" title="Happy Posts" subtitle="Make people read"][vc_widget_sidebar sidebar_id="custom-sidebar"][/vc_column][vc_column width="2/3" offset="vc_col-lg-offset-1 vc_col-lg-8 vc_col-md-9"][appica_recent_posts is_excerpt="yes" img_size="medium" per_page="4"][/vc_column][/vc_row][vc_row badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="default" badge_tc="#bebebe" content_color="dark" uniq_id="app-story" is_container="no" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70"][vc_column width="1/1"][appica_half_block_image align="left" title="How We Build This Awesome App" subtitle="Little story of app development" image="950"]Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut voluptas dolor, neque adipisci ullam. Optio deleniti dolores ex doloribus, incidunt nisi veniam libero.<a href="#">READ FULL INTERESTING STORY</a>[/appica_half_block_image][/vc_column][/vc_row][vc_row el_class="fw-bg padding-top-3x padding-bottom-2x" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="default" badge_tc="#bebebe" content_color="light" uniq_id="versions" is_container="yes" css=".vc_custom_1429695317360{background-image: url(http://the8guild.com/themes/wordpress/appica2/ios/wp-content/uploads/sites/3/2015/04/space.jpg?id=912) !important;}" overlay="enable" overlay_partial="no" overlay_type="gradient" overlay_gc_start="#3a1cff" overlay_gc_end="#ff3a30" overlay_opacity="80"][vc_column width="1/1"][vc_row_inner][vc_column_inner width="1/1"][appica_custom_title align="center" title="Check other Appica 2 versions" subtitle="They are are cool too, don\'t miss it!"][/vc_column_inner][/vc_row_inner][vc_row_inner el_class="appica-versions space-top-3x" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="primary" badge_tc="#bebebe"][vc_column_inner width="1/3" offset="vc_col-xs-4"][vc_single_image image="855" alignment="center" border_color="grey" img_link_large="" img_link_target="_blank" is_caption_used="" caption_align="right" img_size="full" css=".vc_custom_1429695349230{margin-bottom: 10px !important;}"][vc_column_text]<h3 style="text-align: center;">iOS</h3>[/vc_column_text][/vc_column_inner][vc_column_inner width="1/3" offset="vc_col-xs-4"][vc_single_image image="854" alignment="center" border_color="grey" img_link_large="" img_link_target="_blank" is_caption_used="" caption_align="right" link="http://google.com" img_size="full" css=".vc_custom_1429695357850{margin-bottom: 10px !important;}"][vc_column_text]<h3 style="text-align: center;">Android</h3>[/vc_column_text][/vc_column_inner][vc_column_inner width="1/3" offset="vc_col-xs-4"][vc_single_image image="856" alignment="center" border_color="grey" img_link_large="" img_link_target="_self" is_caption_used="" caption_align="right" img_size="full" css=".vc_custom_1429695366524{margin-bottom: 10px !important;}"][vc_column_text]<h3 style="text-align: center;">Windows Phone</h3>[/vc_column_text][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row el_class="padding-top-3x space-bottom-3x" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="default" badge_tc="#bebebe" content_color="dark" uniq_id="figures" is_container="yes" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70"][vc_column width="1/1"][vc_tabs text_align="right" position="right" title="We Made Great App" subtitle="Optimization. Performance. Popularity" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat."][vc_tab title="Loading speed" tab_id="9af04d05-a0c6-3baa3-f815" transition="top"][vc_single_image image="853" border_color="grey" img_link_large="" img_link_target="_self" is_caption_used="" caption_align="right" img_size="full" alignment="center"][/vc_tab][vc_tab title="Downloads" tab_id="bece5e85-2fa4-4baa3-f815" transition="scaleup"][vc_single_image image="851" border_color="grey" img_link_large="" img_link_target="_self" is_caption_used="" caption_align="right" img_size="full" alignment="center"][/vc_tab][vc_tab title="Market share" tab_id="1428577984289-2-4baa3-f815" transition="right"][vc_single_image image="852" border_color="grey" img_link_large="" img_link_target="_self" is_caption_used="" caption_align="right" img_size="full" alignment="center"][/vc_tab][/vc_tabs][/vc_column][/vc_row][vc_row el_class="space-top" badge="show" badge_align="left" badge_title="Big boys about us" icon_lib="flaticons" icon_linecons="vc_li vc_li-heart" icon_flaticons="flaticon-star51" badge_pc="alt-color" badge_tc="#bebebe" content_color="dark" uniq_id="reviews" is_container="yes" css=".vc_custom_1429695519714{border-top-width: 1px !important;border-top-color: #F2F2F2 !important;border-top-style: solid !important;}" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70"][vc_column width="1/1"][appica_testimonials][/vc_column][/vc_row][vc_row css=".vc_custom_1429695585706{background-color: #f5f5f5 !important;}" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="default" badge_tc="#bebebe" content_color="dark" uniq_id="team" is_container="yes" el_class="fw-gray-bg padding-top-3x" overlay="disable" overlay_partial="no" overlay_type="gradient" overlay_opacity="70"][vc_column width="1/1"][vc_row_inner][vc_column_inner width="1/2"][vc_column_text css=".vc_custom_1429625213648{margin-bottom: 24px !important;}"]Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi delectus recusandae ducimus, voluptates sequi laborum atque odio inventore iusto. Accusantium vel molestiae quo quam praesentium.[/vc_column_text][/vc_column_inner][vc_column_inner width="1/2" offset="vc_col-lg-offset-2 vc_col-lg-4 vc_col-md-offset-1 vc_col-md-5"][appica_custom_title align="left" title="Our Dream Team" subtitle="People behind this app"][/vc_column_inner][/vc_row_inner][vc_row_inner el_class="space-top-3x" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="default" badge_tc="#bebebe"][vc_column_inner width="1/1"][appica_team][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row el_class="fw-bg padding-top-3x padding-bottom-2x" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="default" badge_tc="#bebebe" content_color="light" uniq_id="pricing" is_container="yes" overlay="enable" overlay_partial="no" overlay_type="gradient" overlay_gc_start="#ffcc00" overlay_gc_end="#ff8c00" overlay_opacity="100"][vc_column width="1/1"][appica_pricing_plans is_switcher="yes" title="Choose a plan you need" subtitle="Save 20%" first_name="Monthly" second_name="Yearly"][/vc_column][/vc_row][vc_row el_class="fw-bg bottom-shadow padding-top-3x padding-bottom-2x" css=".vc_custom_1429695825281{background-image: url(http://the8guild.com/themes/wordpress/appica2/ios/wp-content/uploads/sites/3/2015/04/web-app-bg.jpg?id=916) !important;}" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="default" badge_tc="#bebebe" content_color="light" uniq_id="web-app" is_container="yes" overlay="enable" overlay_partial="no" overlay_type="gradient" overlay_gc_start="#ff3a30" overlay_gc_end="#3a1cff" overlay_opacity="70"][vc_column width="1/1"][vc_row_inner][vc_column_inner width="1/1" offset="vc_col-md-5"][appica_custom_title align="right" title="Web Interface" subtitle="Our app right in your browser"][/vc_column_inner][/vc_row_inner][vc_row_inner el_class="space-top-2x" badge="hide" badge_align="left" icon_lib="fontawesome" icon_linecons="vc_li vc_li-heart" badge_pc="primary" badge_tc="#bebebe"][vc_column_inner width="1/1" offset="vc_col-md-5"][appica_feature align="left" icon_lib="flaticons" icon_size="default" icon_pos="left" icon_va="top" title="Full Featured Web App" description="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas harum a aperiam pariatur totam impedit, sint quibusdam minus deserunt labore ipsum commodo. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa." icon_linecons="vc_li vc_li-heart" icon_flaticons="flaticon-globe14" link="url:http%3A%2F%2Fthe8guild.com%2Fthemes%2Fwordpress%2Fappica2%2Fios%2Fdesigned-with-love%2F|title:Designed%20with%20love|" link_text="Visit website"][appica_download_btn text="Download on the" link="url:%23||"][/vc_column_inner][vc_column_inner width="1/1" offset="vc_col-md-7"][vc_single_image image="951" alignment="center" border_color="grey" img_link_large="" img_link_target="_self" is_caption_used="" caption_align="right" img_size="full"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]'
	);

	vc_add_default_templates( $data );
}

add_action( 'vc_load_default_templates_action', 'appica_vc_default_templates' );
