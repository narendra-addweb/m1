<?php
/**
 * Register shortcode settings with Visual Composer
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

/*
 * Most used elements in shortcode
 */

/**
 * @var string Appica icon for all shortcodes
 */
$icon = plugins_url( 'assets/img/appica-small.png', __DIR__ );

/**
 * @var string Shortcodes global category
 */
$category = __( 'Appica', 'appica' );

/**
 * @var string Shortcodes title i18n single call
 */
$heading_title = __( 'Title', 'appica' );

/**
 * @var string Shortcodes subtitle i18n single call
 */
$heading_subtitle = __( 'Subtitle', 'appica' );

/**
 * @var string Icon heading name
 */
$heading_icon = __( 'Icon', 'appica' );

/**
 * @var array Yes/No dropdown value
 */
$value_yes_no = array(
	__( 'Yes', 'appica' ) => 'yes',
	__( 'No', 'appica' )  => 'no'
);

/**
 * @var array Left/Right dropdown value
 */
$value_left_right = array(
	__( 'Left', 'appica' )  => 'left',
	__( 'Right', 'appica' ) => 'right'
);

/**
 * @var array "Enable/Disable" dropdown value
 */
$value_enable_disable = array(
	__( 'Enable', 'appica' ) => 'enable',
	__( 'Disable', 'appica' ) => 'disable'
);

/**
 * @var array Extra class field in shortcode settings
 */
$field_extra_class = array(
	'type'        => 'textfield',
	'param_name'  => 'extra_class',
	'heading'     => __( 'Extra class name', 'appica' ),
	'description' => __( 'Add extra classes, divided by whitespace, if you wish to style particular content element differently.', 'appica' )
);

/**
 * @var array "Image Size" field
 */
$field_image_size = array(
	'type'        => 'textfield',
	'param_name'  => 'img_size',
	'heading'     => __( 'Image Size', 'appica' ),
	'description' => __( 'Choose one of WordPress built-in sizes: "thumbnail", "medium", "large" or "full". Or any custom width and height in pixels, separated by "x", e.g. "300x200". Or single "300" which means width and height will be 300px.', 'appica' ),
	'value'       => 'medium'
);

/**
 * @var array Title field
 */
$field_title = array(
	'type'       => 'textfield',
	'param_name' => 'title',
	'heading'    => $heading_title
);

/**
 * @var array Subtitle field
 */
$field_subtitle = array(
	'type'       => 'textfield',
	'param_name' => 'subtitle',
	'heading'    => $heading_subtitle
);

/**
 * @var array Field "Icon Library" allow choosing different icons
 */
$field_icon_library = array(
	'type'       => 'dropdown',
	'param_name' => 'icon_lib',
	'heading'    => __( 'Icon library', 'appica' ),
	'std'        => 'flaticons',
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

/**
 * @var array Param for text-align with values: left | center | right. Param name: "align"
 */
$field_text_align = array(
	'type'       => 'dropdown',
	'param_name' => 'align',
	'heading'    => __( 'Alignment', 'appica' ),
	'std'        => 'left',
	'value'      => array(
		__( 'Left', 'appica' )   => 'left',
		__( 'Center', 'appica' ) => 'center',
		__( 'Right', 'appica' )  => 'right'
	)
);

/*
 * VC shortcodes integration
 */

/**
 * "Custom Title" shortcodes params
 * @var array
 */
$appica_custom_title = array(
	'name'        => __( 'Appica Custom Title', 'appica' ),
	'base'        => 'appica_custom_title',
	'description' => __( 'A fancy title', 'appica' ),
	'icon'        => $icon,
	'category'    => $category,
	'params'      => array(
		array(
			'type'        => 'textfield',
			'param_name'  => 'title',
			'heading'     => $heading_title,
			'admin_label' => true
		),
		array(
			'type'        => 'textfield',
			'param_name'  => 'subtitle',
			'heading'     => $heading_subtitle,
			'admin_label' => true
		),
		$field_text_align,
		$field_extra_class
	)
);

vc_map( $appica_custom_title );

/**
 * "Testimonials" shortcode params
 * @var array
 */
$appica_testimonials = array(
	'name'                    => __( 'Appica Testimonials', 'appica' ),
	'description'             => __( 'For display the "Testimonials" CPT', 'appica' ),
	'base'                    => 'appica_testimonials',
	'icon'                    => $icon,
	'category'                => $category,
	'show_settings_on_create' => false,
	'params'                  => array(
		$field_extra_class
	)
);

vc_map( $appica_testimonials );

/**
 * "Team" shortcode params
 * @var array
 */
$appica_team = array(
	'name'                    => __( 'Appica Team', 'appica' ),
	'description'             => __( 'For display the "Team" CPT', 'appica' ),
	'base'                    => 'appica_team',
	'icon'                    => $icon,
	'category'                => $category,
	'show_settings_on_create' => false,
	'params'                  => array(
		$field_extra_class
	)
);

vc_map( $appica_team );

/**
 * "Gadgets Slideshow" shortcode params
 * @var array
 */
$appica_gadgets_slideshow = array(
	'name'        => __( 'Appica Gadgets Slideshow', 'appica' ),
	'description' => __( 'For display the "Gadgets Slideshow"', 'appica' ),
	'base'        => 'appica_gadgets_slideshow',
	'icon'        => $icon,
	'category'    => $category,
	'params'      => array(
		$field_title,
		$field_subtitle,
		array(
			'type'       => 'dropdown',
			'param_name' => 'is_sl',
			'heading'    => __( 'Enable/Disable slideshow', 'appica' ),
			'std'        => 'disable',
			'value'      => $value_enable_disable
		),
		array(
			'type'        => 'textfield',
			'param_name'  => 'sl_interval',
			'heading'     => __( 'Slideshow interval', 'appica' ),
			'description' => __( 'Interval between slide switching, in ms', 'appica' ),
			'value'       => 3000,
			'dependency'  => array(
				'element' => 'is_sl',
				'value'   => 'enable'
			)
		),
		$field_extra_class
	)
);

vc_map( $appica_gadgets_slideshow );

/**
 * "Video Popup" shortcode params
 * @var array
 */
$appica_video_popup = array(
	'name'        => __( 'Appica Video Popup', 'appica' ),
	'description' => __( 'For display embed video in popup', 'appica' ),
	'base'        => 'appica_video_popup',
	'icon'        => $icon,
	'category'    => $category,
	'params'      => array(
		array(
			'type'        => 'textarea',
			'param_name'  => 'text',
			'heading'     => $heading_title,
			'description' => __( 'Short description of video', 'appica' )
		),
		array(
			'type'       => 'textfield',
			'param_name' => 'video',
			'heading'    => __( 'Video URL', 'appica' )
		),
		$field_extra_class
	)
);

vc_map( $appica_video_popup );

/**
 * "Half Block Image" shortcode params
 * @var array
 */
$appica_half_block_image = array(
	'name'     => __( 'Appica Half Block Image', 'appica' ),
	'base'     => 'appica_half_block_image',
	'icon'     => $icon,
	'category' => $category,
	'params'   => array(
		$field_title,
		$field_subtitle,
		array(
			'type'       => 'attach_image',
			'param_name' => 'image',
			'heading'    => __( 'Image', 'appica' )
		),
		array(
			'type'       => 'textarea_html',
			'param_name' => 'content',
			'heading'    => __( 'Description', 'appica' )
		),
		array(
			'type'       => 'dropdown',
			'param_name' => 'align',
			'heading'    => __( 'Image position', 'appica' ),
			'std'        => 'left',
			'value'      => $value_left_right
		),
		$field_extra_class
	)
);

vc_map( $appica_half_block_image );

/**
 * @var array "Download Button" shortcode params
 */
$appica_download_button = array(
	'name'     => __( 'Appica Download Button', 'appica' ),
	'base'     => 'appica_download_btn',
	'icon'     => $icon,
	'category' => $category,
	'params'   => array(
		array(
			'type'       => 'textfield',
			'param_name' => 'text',
			'heading'    => __( 'Button text', 'appica' ),
			'value'      => 'Download on the'
		),
		array(
			'type'       => 'vc_link',
			'param_name' => 'link',
			'heading'    => __( 'Link', 'appica' )
		),
		$field_extra_class
	)
);

vc_map( $appica_download_button );

/**
 * @var array "Recent Posts" shortcode params
 */
$appica_recent_posts = array(
	'name'        => __( 'Appica Recent Posts', 'appica' ),
	'description' => __( 'Your latest posts inside slider', 'appica' ),
	'base'        => 'appica_recent_posts',
	'icon'        => $icon,
	'category'    => $category,
	'params'      => array(
		array(
			'type'        => 'textfield',
			'param_name'  => 'per_page',
			'heading'     => __( 'Number of entries to show', 'appica' ),
			'description' => __( 'Specify the number or "all" for all posts', 'appica' )
		),
		array(
			'type'       => 'dropdown',
			'param_name' => 'is_excerpt',
			'heading'    => __( 'Show excerpt?', 'appica' ),
			'std'        => 'yes',
			'value'      => $value_yes_no
		),
		$field_image_size,
		$field_extra_class
	)
);

vc_map( $appica_recent_posts );

/**
 * @var array "Fancy Text" shortcode params
 */
$appica_fancy_text = array(
	'name'        => __( 'Appica Fancy Text', 'appica' ),
	'description' => __( 'A fancy string', 'appica' ),
	'base'        => 'appica_fancy_text',
	'icon'        => $icon,
	'category'    => $category,
	'params'      => array(
		array(
			'type'        => 'textfield',
			'param_name'  => 'text',
			'weight'      => 999,
			'heading'     => __( 'Text', 'appica' ),
			'admin_label' => true
		),
		$field_extra_class
	)
);

vc_map( $appica_fancy_text );

/**
 * @var array "Timeline" shortcode params
 */
$appica_timeline = array(
	'name'        => __( 'Appica Timeline', 'appica' ),
	'description' => __( 'For display the Timeline', 'appica' ),
	'base'        => 'appica_timeline',
	'icon'        => $icon,
	'category'    => $category,
	'params'      => array(
		$field_title,
		array(
			'type'       => 'textarea',
			'param_name' => 'description',
			'heading'    => __( 'Description', 'appica' )
		),
		$field_extra_class
	)
);

vc_map( $appica_timeline );

/**#@+
 * Translated strings for shortcode groups
 */
$first        = __( 'First', 'appica' );
$second       = __( 'Second', 'appica' );
$third        = __( 'Third', 'appica' );
$percent      = __( 'Percent', 'appica' );
$percent_desc = __( 'Column height in percents from 0 to 100', 'appica' );
$color        = __( 'Color', 'appica' );
/**#@-*/

/**
 * @var array "Bar Charts" shortcode params
 */
$appica_bar_charts = array(
	'name'        => __( 'Appica Bar Charts', 'appica' ),
	'base'        => 'appica_bar_charts',
	'icon'        => $icon,
	'category'    => $category,
	'params'      => array(
		array(
			'type'        => 'textfield',
			'param_name'  => 'extra_class',
			'group'       => __( 'General', 'appica' ),
			'heading'     => __( 'Extra class name', 'appica' ),
			'description' => __( 'Add extra classes, divided by whitespace, if you wish to style particular content element differently.', 'appica' )
		),
		array(
			'type'       => 'textfield',
			'param_name' => 'first_title',
			'heading'    => $heading_title,
			'group'      => $first
		),
		array(
			'type'       => 'textfield',
			'param_name' => 'first_subtitle',
			'heading'    => $heading_subtitle,
			'group'      => $first
		),
		array(
			'type'        => 'textfield',
			'param_name'  => 'first_percent',
			'heading'     => $percent,
			'description' => $percent_desc,
			'group'       => $first
		),
		array(
			'type'       => 'colorpicker',
			'param_name' => 'first_color',
			'heading'    => $color,
			'group'      => $first
		),
		array(
			'type'       => 'textfield',
			'param_name' => 'second_title',
			'heading'    => $heading_title,
			'group'      => $second
		),
		array(
			'type'       => 'textfield',
			'param_name' => 'second_subtitle',
			'heading'    => $heading_subtitle,
			'group'      => $second
		),
		array(
			'type'        => 'textfield',
			'param_name'  => 'second_percent',
			'heading'     => $percent,
			'description' => $percent_desc,
			'group'       => $second
		),
		array(
			'type'       => 'colorpicker',
			'param_name' => 'second_color',
			'heading'    => $color,
			'group'      => $second
		),
		array(
			'type'       => 'textfield',
			'param_name' => 'third_title',
			'heading'    => $heading_title,
			'group'      => $third
		),
		array(
			'type'       => 'textfield',
			'param_name' => 'third_subtitle',
			'heading'    => $heading_subtitle,
			'group'      => $third
		),
		array(
			'type'        => 'textfield',
			'param_name'  => 'third_percent',
			'heading'     => $percent,
			'description' => $percent_desc,
			'group'       => $third
		),
		array(
			'type'       => 'colorpicker',
			'param_name' => 'third_color',
			'heading'    => $color,
			'group'      => $third
		)
	)
);

unset( $first, $second, $third, $percent, $percent_desc, $color );

vc_map( $appica_bar_charts );

/**
 * @var array "Button" shortcode params
 */
$appica_button = array(
	'name'        => __( 'Appica Button', 'appica' ),
	'description' => __( 'A stylish button for universal purposes', 'appica' ),
	'base'        => 'appica_button',
	'icon'        => $icon,
	'category'    => $category,
	'params'      => array(
		array(
			'type'       => 'textfield',
			'weight'     => 999,
			'param_name' => 'text',
			'heading'    => __( 'Button text', 'appica' ),
			'value'      => __( 'Click Me!', 'appica' )
		),
		array(
			'type'       => 'vc_link',
			'weight'     => 999,
			'param_name' => 'link'
		),
		array(
			'type'       => 'dropdown',
			'param_name' => 'type',
			'heading'    => __( 'Button type', 'appica' ),
			'std'        => 'default',
			'value'      => array(
				__( 'Default', 'appica' ) => 'default',
				__( 'Round', 'appica' )   => 'round'
			)
		),
		array(
			'type'       => 'dropdown',
			'param_name' => 'style',
			'heading'    => __( 'Button style', 'appica' ),
			'value'      => array(
				__( 'Standard', 'appica' ) => 'standard',
				__( 'Outlined', 'appica' ) => 'outlined'
			)
		),
		array(
			'type'       => 'dropdown',
			'param_name' => 'size',
			'heading'    => __( 'Button size', 'appica' ),
			'std'        => 'nl',
			'value'      => array(
				__( 'Small', 'appica' )  => 'sm',
				__( 'Normal', 'appica' ) => 'nl',
				__( 'Large', 'appica' )  => 'lg'
			)
		),
		array(
			'type'               => 'dropdown',
			'param_name'         => 'color',
			'param_holder_class' => 'appica-button-color',
			'heading'            => __( 'Button color', 'appica' ),
			'std'                => 'default',
			'value'              => array(
				__( 'Default', 'appica' ) => 'default',
				__( 'Primary', 'appica' ) => 'primary',
				__( 'Success', 'appica' ) => 'success',
				__( 'Info', 'appica' )    => 'info',
				__( 'Warning', 'appica' ) => 'warning',
				__( 'Danger', 'appica' )  => 'danger'
			)
		),
		$field_icon_library,
		$field_icon_fontawesome,
		$field_icon_openiconic,
		$field_icon_typicons,
		$field_icon_entypo,
		$field_icon_linecons,
		$field_icon_flaticons,
		array(
			'type'       => 'dropdown',
			'param_name' => 'icon_pos',
			'heading'    => __( 'Icon position', 'appica' ),
			'std'        => 'left',
			'value'      => $value_left_right,
			'dependency' => array(
				'element' => 'type',
				'value'   => 'default'
			)
		),
		array(
			'type'       => 'dropdown',
			'param_name' => 'is_full',
			'heading'    => __( 'Make button full-width?', 'appica' ),
			'std'        => 'no',
			'value'      => $value_yes_no,
			'dependency' => array(
				'element' => 'type',
				'value'   => 'default'
			)
		),
		$field_extra_class
	)
);

vc_map( $appica_button );

/**
 * @var array "Feature" shortcode param
 */
$appica_feature = array(
	'name'        => __( 'Appica Feature', 'appica' ),
	'description' => __( 'Universal feature presentation + icon', 'appica' ),
	'base'        => 'appica_feature',
	'icon'        => $icon,
	'category'    => $category,
	'params'      => array(
		$field_title,
		array(
			'type'       => 'textarea',
			'param_name' => 'description',
			'heading'    => __( 'Description', 'appica' )
		),
		array(
			'type'       => 'vc_link',
			'param_name' => 'link'
		),
		array(
			'type'       => 'textfield',
			'param_name' => 'link_text',
			'heading'    => __( 'Link Text', 'appica' ),
			'value'      => __( 'See more', 'appica' )
		),
		$field_text_align,
		$field_icon_library,
		$field_icon_fontawesome,
		$field_icon_openiconic,
		$field_icon_typicons,
		$field_icon_entypo,
		$field_icon_linecons,
		$field_icon_flaticons,
		array(
			'type'       => 'dropdown',
			'param_name' => 'icon_size',
			'heading'    => __( 'Icon size', 'appica' ),
			'std'        => 'default',
			'value'      => array(
				__( 'Default', 'appica' ) => 'default',
				__( 'Large', 'appica' )   => 'large'
			)
		),
		array(
			'type'       => 'dropdown',
			'param_name' => 'icon_pos',
			'heading'    => __( 'Icon position', 'appica' ),
			'std'        => 'left',
			'value'      => array(
				__( 'Left', 'appica' )  => 'left',
				__( 'Top', 'appica' )   => 'top',
				__( 'Right', 'appica' ) => 'right'
			)
		),
		array(
			'type'       => 'dropdown',
			'param_name' => 'icon_va',
			'heading'    => __( 'Icon vertical align', 'appica' ),
			'std'        => 'top',
			'value'      => array(
				__( 'Top', 'appica' )    => 'top',
				__( 'Middle', 'appica' ) => 'middle'
			),
			'dependency' => array(
				'element' => 'icon_pos',
				'value'   => array( 'left', 'right' )
			)
		),
		$field_extra_class
	)
);

vc_map( $appica_feature );

/**
 * @var array "Team 2" shortcode params
 */
$appica_team_alt = array(
	'name'                    => __( 'Appica Team 2', 'appica' ),
	'description'             => __( 'Present your team another way', 'appica' ),
	'base'                    => 'appica_team_alt',
	'icon'                    => $icon,
	'category'                => $category,
	'show_settings_on_create' => false,
	'params'                  => array(
		$field_extra_class
	)
);

vc_map( $appica_team_alt );

/**
 * @var array "Gallery" shortcode params
 */
$appica_gallery = array(
	'name'        => __( 'Appica Gallery', 'appica' ),
	'description' => __( 'Show your gallery to the world', 'appica' ),
	'base'        => 'appica_gallery',
	'icon'        => $icon,
	'category'    => $category,
	'params'      => array(
		$field_title,
		$field_subtitle,
		array(
			'type'       => 'dropdown',
			'param_name' => 'is_cat',
			'heading'    => __( 'Show categories?', 'appica' ),
			'std'        => 'yes',
			'value'      => $value_yes_no
		),
		$field_extra_class
	)
);

vc_map( $appica_gallery );

/**
 * @var array "Pricing Plans" shortcode params
 */
$appica_pricing_plans = array(
	'name'        => __( 'Appica Pricing Plans', 'appica' ),
	'description' => __( 'For display the "Pricings" CPT', 'appica' ),
	'base'        => 'appica_pricing_plans',
	'icon'        => $icon,
	'category'    => $category,
	'params'      => array(
		array(
			'type'       => 'dropdown',
			'param_name' => 'is_switcher',
			'heading'    => __( 'Show plan switcher?', 'appica' ),
			'std'        => 'yes',
			'value'      => $value_yes_no
		),
		array_merge( $field_title, array(
			'value'      => __( 'Choose a plan you need', 'appica' ),
			'dependency' => array(
				'element' => 'is_switcher',
				'value'   => 'yes'
			)
		) ),
		array_merge( $field_subtitle, array(
			'value'      => __( 'Save 20%', 'appica' ),
			'dependency' => array(
				'element' => 'is_switcher',
				'value'   => 'yes'
			)
		) ),
		$field_extra_class
	)
);

vc_map( $appica_pricing_plans );

/**
 * @var array "App Gallery" shortcode params
 */
$appica_app_gallery = array(
	'name'                    => __( 'Appica App Gallery', 'appica' ),
	'description'             => __( 'For display the "App Gallery" CPT', 'appica' ),
	'base'                    => 'appica_app_gallery',
	'icon'                    => $icon,
	'category'                => $category,
	'show_settings_on_create' => false,
	'params'                  => array(
		$field_extra_class
	)
);

vc_map( $appica_app_gallery );

/**
 * @var array "News" shortcode vc integration
 */
$appica_news = array(
	'name'        => __( 'Appica News', 'appica' ),
	'description' => __( 'For display the "News" CPT', 'appica' ),
	'base'        => 'appica_news',
	'icon'        => $icon,
	'category'    => $category,
	'params'      => array(
		array(
			'type'       => 'textfield',
			'param_name' => 'more',
			'heading'    => __( '"Read More" text', 'appica' ),
			'value'      => __( 'More...', 'appica' )
		),
		$field_extra_class
	)
);

vc_map( $appica_news );

/**
 * @var array Blog posts params
 */
$appica_posts = array(
	'name'        => __( 'Appica Posts', 'appica' ),
	'description' => __( 'Your posts inside simple, but fancy grid', 'appica' ),
	'base'        => 'appica_posts',
	'icon'        => $icon,
	'category'    => $category,
	'params'      => array(
		array(
			'type'        => 'loop',
			'param_name'  => 'loop',
			'settings'    => array(
				'size'     => array( 'value' => 10 ),
				'order_by' => array( 'value' => 'date' )
			),
			'heading'     => __( 'Grids content', 'appica' ),
			'description' => __( 'Create WordPress loop, to populate content from your site.', 'appica' )
		),
		$field_extra_class
	)
);

vc_map( $appica_posts );

/**
 * @var string "General" group translated string
 */
$group_general = __( 'General', 'appica' );

/**
 * @var string "Map" group translated string
 */
$group_map = __( 'Map', 'appica' );

/**
 * @var array "Contacts" shortcode mapping params
 */
$appica_contacts = array(
	'name'        => __( 'Appica Contacts', 'appica' ),
	'description' => __( 'Contacts information + Google Map', 'appica' ),
	'base'        => 'appica_contacts',
	'icon'        => $icon,
	'category'    => $category,
	'params'      => array(
		array_merge( $field_title, array( 'group' => $group_general ) ),
		array_merge( $field_subtitle, array( 'group' => $group_general ) ),
		array(
			'type'       => 'textarea_html',
			'param_name' => 'content',
			'heading'    => __( 'Contact Info', 'appica' ),
			'group'      => $group_general
		),
		array_merge( $field_extra_class, array( 'group' => $group_general ) ),
		array(
			'type'        => 'textfield',
			'param_name'  => 'location',
			'group'       => $group_map,
			'heading'     => __( 'Location', 'appica' ),
			'description' => __( 'Enter any search query, which you can find on Google Maps, e.g. "New York, USA" or "Odessa, Ukraine".', 'appica' )
		),
		array(
			'type'        => 'textfield',
			'param_name'  => 'zoom',
			'group'       => $group_map,
			'heading'     => __( 'Zoom', 'appica' ),
			'description' => __( 'The initial Map zoom level', 'appica' ),
			'value'       => 14
		),
		array(
			'type'       => 'dropdown',
			'param_name' => 'is_zoom',
			'group'      => $group_map,
			'heading'    => __( 'Zoom Controls', 'appica' ),
			'std'        => 'disable',
			'value'      => $value_enable_disable
		),
		array(
			'type'        => 'dropdown',
			'param_name'  => 'is_scroll',
			'group'       => $group_map,
			'heading'     => __( 'ScrollWheel', 'appica' ),
			'description' => __( 'Enable or disable scrollwheel zooming on the map.', 'appica' ),
			'std'         => 'disable',
			'value'       => $value_enable_disable
		),
		array(
			'type'       => 'attach_image',
			'param_name' => 'marker',
			'group'      => $group_map,
			'heading'    => __( 'Custom marker', 'appica' )
		),
		array(
			'type'        => 'textarea_raw_html',
			'param_name'  => 'gm_custom',
			'group'       => __( 'Styling', 'appica' ),
			'heading'     => __( 'Custom styling', 'appica' ),
			'description' => __( 'Generate your styles in <a href="https://snazzymaps.com/editor" target="_blank">Snazzymaps Editor</a> and paste JavaScript Style Array in field above', 'appica' ),
		)
	)
);

vc_map( $appica_contacts );

/**
 * @var array MailChimp form
 */
$appica_mailchimp_form = array(
	'name'        => __( 'Appica MailChimp', 'appica' ),
	'description' => __( 'MailChimp subscribe form', 'appica' ),
	'base'        => 'appica_mailchimp_form',
	'icon'        => $icon,
	'category'    => $category,
	'params'      => array(
		$field_title,
		array(
			'type'        => 'textfield',
			'param_name'  => 'action',
			'heading'     => __( 'MailChimp URL', 'appica' ),
			'description' => __( 'You can use custom URL here. If blank, url from Appica > <a href="admin.php?page=appica&tab=4" target="_blank">Socials</a> will be used instead.', 'appica' ),
		),
		array(
			'type'        => 'textfield',
			'param_name'  => 'label_name',
			'heading'     => __( 'Label: Name', 'appica' ),
			'description' => __( 'Label for "name" field', 'appica' ),
			'value'       => __( 'Name', 'appica' )
		),
		array(
			'type'        => 'textfield',
			'param_name'  => 'label_email',
			'heading'     => __( 'Label: Email', 'appica' ),
			'description' => __( 'Label for "email" field', 'appica' ),
			'value'       => __( 'Email', 'appica' )
		),
		array(
			'type'        => 'textfield',
			'param_name'  => 'label_btn',
			'heading'     => __( 'Button text', 'appica' ),
			'description' => __( 'Text on the button', 'appica' ),
			'value'       => __( 'Subscribe', 'appica' )
		),
		array(
			'type'        => 'dropdown',
			'param_name'  => 'orientation',
			'heading'     => __( 'Orientation', 'appica' ),
			'description' => __( 'How form fields will be aligned', 'appica' ),
			'std'         => 'horizontal',
			'value'       => array(
				__( 'Horizontal', 'appica' ) => 'horizontal',
				__( 'Vertical', 'appica' )   => 'vertical',
			)
		)
	)
);

vc_map( $appica_mailchimp_form );

/**
 * @var array Portfolio shortcode params
 */
$appica_portfolio = array(
	'name'        => __( 'Appica Portfolio', 'appica' ),
	'description' => __( 'Display Portfolio CPT', 'appica' ),
	'base'        => 'appica_portfolio',
	'icon'        => $icon,
	'category'    => $category,
	'params'      => array(
		array(
			'type'        => 'textfield',
			'param_name'  => 'num',
			'heading'     => __( 'Number of posts', 'appica' ),
			'description' => __( 'Set "all" for displaying all posts.', 'appica' )
		),
		array(
			'type'       => 'textfield',
			'param_name' => 'lm_text',
			'heading'    => __( 'Load More text', 'appica' ),
			'value'      => __( 'Load More Portfolio', 'appica' )
		),
		array(
			'type'       => 'dropdown',
			'param_name' => 'is_filters',
			'heading'    => __( 'Enable / Disable Filters', 'appica' ),
			'std'        => 'enable',
			'value'      => $value_enable_disable
		),
		$field_extra_class,
	)
);

vc_map( $appica_portfolio );