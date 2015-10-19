<?php
/**
 * Theme Settings via Redux Framework
 *
 * @since      1.0.0
 * @author     8guild
 * @package    Appica
 * @subpackage Settings
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

if ( ! class_exists( 'Redux' ) ) {
    return;
}

/**
 * Return list of Revolution Slider items
 *
 * @since 1.3.0
 *
 * @return array [alias => title]
 */
function appica_get_revslider_items() {
	$not_found = array(
		0 => __( 'No sliders found', 'appica' )
	);

	if ( ! class_exists( 'RevSlider', false ) ) {
		return $not_found;
	}

	$revslider = new RevSlider();
	$sliders   = $revslider->getArrSliders();

	if ( $sliders ) {
		$_sliders = array();
		foreach ( $sliders as $slider ) {
			$_sliders[ $slider->getAlias() ] = $slider->getTitle();
		}
		unset( $slider );

		return $_sliders;
	}

	return $not_found;
}

/**
 * Theme options slug.
 *
 * This is the variable where all option data is stored in the database.
 * It also acts as the global variable in which data options are retrieved via code.
 *
 * @var string
 */
$opt_name = 'appica_options';

/**
 * @var WP_Theme Theme object
 */
$theme = wp_get_theme();

/**
 * @var string Translated string for multiple usage
 */
$text_appica = __( 'Appica', 'appica' );

/**
 * @var string Localized string "Title"
 */
$text_title = __( 'Title', 'appica' );

/**
 * @var string Localized string "Subtitle"
 */
$text_subtitle = __( 'Subtitle', 'appica' );

/**
 * @var string "Enable" localized text
 */
$text_enable = __( 'Enable', 'appica' );

/**
 * @var string "Disable" localized text
 */
$text_disable = __( 'Disable', 'appica' );

/**
 * @var string "Show" localized
 */
$text_show = __( 'Show', 'appica' );

/**
 * @var string "Hide" localized
 */
$text_hide = __( 'Hide', 'appica' );

/**
 * @var string Localized string "Some HTML is allowed here."
 */
$text_html_allowed = __( 'Some HTML is allowed here.', 'appica' );

/**
 * @var string Localized string of allowable HTML tags
 */
$text_tags_allowed = __( 'Tags &lt;a&gt;, &lt;br&gt;, &lt;em&gt;, &lt;strong&gt; are allowed.', 'appica' );

/**
 * @var array font-weight possible values
 */
$font_weight = array(
    'lighter' => 'Lighter',
    '100'     => '100',
    '200'     => '200',
    '300'     => '300',
    '400'     => 'Normal (400)',
    '500'     => '500',
    '600'     => '600',
    '700'     => 'Bold (700)',
    '800'     => '800',
    '900'     => '900',
    'bolder'  => 'Bolder'
);

/**
 * @var array text-transform possible values
 */
$text_transform = array(
    'none'       => 'None',
    'capitalize' => 'Capitalize',
    'lowercase'  => 'Lowercase',
    'uppercase'  => 'Uppercase'
);

/*
 * Set global args
 */
Redux::setArgs( $opt_name, array(
    'opt_name'          => $opt_name,
    'display_name'      => $theme->get( 'Name' ),
    'display_version'   => $theme->get( 'Version' ),
    'page_slug'         => 'appica',
    'page_priority'     => '59.3',
    'page_permissions'  => 'edit_theme_options',
    'page_icon'         => 'icon-themes',
    'page_title'        => $text_appica,
    'menu_title'        => $text_appica,
    'menu_icon'         => 'dashicons-editor-textcolor',
    'menu_type'         => 'menu',
    'allow_sub_menu'    => true,
    'class'             => 'appica',
    'admin_bar_icon'    => 'dashicons-admin-generic',
    'output'            => false,
    'output_tag'        => false,
    'hide_expand'       => true,
    'disable_save_warn' => true,
    'customizer'        => false,
    'dev_mode'          => false,
    'update_notice'     => false,
    'system_info'       => false,
    'into_text'         => '',
    'footer_text'       => '<p>' . __( 'Appica theme by 8guild', 'appica' ) . '</p>',
    'admin_bar_links'   => array(),
    'share_icons'       => array(
        array(
            'url'   => 'https://twitter.com/8Guild',
            'title' => 'Twitter',
            'icon'  => 'el el-twitter'
        ),
        array(
            'url'   => 'https://www.facebook.com/8guild',
            'title' => 'Facebook',
            'icon'  => 'el el-facebook'
        ),
        array(
            'url'   => 'https://plus.google.com/u/0/b/109505223181338808677/109505223181338808677/posts',
            'title' => 'Google+',
            'icon'  => 'el el-googleplus'
        ),
        array(
            'url'   => 'http://dribbble.com/8guild',
            'title' => 'Dribbble',
            'icon'  => 'el el-dribbble'
        ),
        array(
            'url'   => 'https://www.behance.net/8Guild',
            'title' => 'Behance',
            'icon'  => 'el el-behance'
        )
    )
) );

/*
 * Global Settings section
 */
Redux::setSection( $opt_name, array(
    'title'  => __( 'Global Settings', 'appica' ),
    'icon'   => 'el-icon-globe',
    'fields' => array(
        array(
            'type'     => 'media',
            'id'       => 'global_favicon',
            'title'    => __( 'Custom Favicon', 'appica' ),
            'desc'     => '<p class="description">' . __( 'Upload your custom favicon here in .ico or .png format.', 'appica' ) . '</p>'
        ),
        array(
            'type'     => 'media',
            'id'       => 'global_preloader_logo',
            'title'    => __( 'Preloader Logo', 'appica' ),
            'desc'     => '<p class="description">' . __( 'Logo optimal size is 240x240px', 'appica' ) . '</p>'
        ),
        array(
            'type'    => 'text',
            'id'      => 'global_preloader_text',
            'title'   => __( 'Preloader Text', 'appica' ),
            'default' => 'Appica 2'
        ),
        array(
            'id'       => 'global_blog_pagination',
            'type'     => 'select',
            'title'    => __( 'Blog Pagination Type', 'appica' ),
            'default'  => 'pagination',
            'options'  => array(
                'pagination'      => __( 'Page links', 'appica' ),
                'load-more'       => __( 'Load More', 'appica' ),
                'infinite-scroll' => __( 'Infinite Scroll', 'appica' )
            )
        )
    )
) );

/*
 * Global Colors
 */
Redux::setSection( $opt_name, array(
    'title'  => __( 'Global Colors', 'appica' ),
    'desc'   => __( 'Set colors that are globally applied everywhere in the theme.', 'appica' ),
    'icon'   => 'el-icon-tint',
    'fields' => array(
        array(
            'type'        => 'color',
            'id'          => 'color_body_font',
            'title'       => __( 'Body Font Color', 'appica' ),
            'transparent' => false,
            'default'     => '#3a3a3a'
        ),
        array(
            'type'        => 'color',
            'id'          => 'color_primary',
            'title'       => __( 'Primary Color', 'appica' ),
            'transparent' => false,
            'default'     => '#007aff'
        ),
        array(
            'type'        => 'color',
            'id'          => 'color_primary_hover',
            'title'       => __( 'Primary Hover/Focus Color', 'appica' ),
            'transparent' => false,
            'default'     => '#3899ff'
        ),
        array(
            'type'        => 'color',
            'id'          => 'color_success',
            'title'       => __( 'Success Color', 'appica' ),
            'transparent' => false,
            'default'     => '#4cd964'
        ),
        array(
            'type'        => 'color',
            'id'          => 'color_success_hover',
            'title'       => __( 'Success Hover/Focus Color', 'appica' ),
            'transparent' => false,
            'default'     => '#74e286'
        ),
        array(
            'type'        => 'color',
            'id'          => 'color_info',
            'title'       => __( 'Info Color', 'appica' ),
            'transparent' => false,
            'default'     => '#5ac8fa'
        ),
        array(
            'type'        => 'color',
            'id'          => 'color_info_hover',
            'title'       => __( 'Info Hover/Focus Color', 'appica' ),
            'transparent' => false,
            'default'     => '#8dd9fb'
        ),
        array(
            'type'        => 'color',
            'id'          => 'color_warning',
            'title'       => __( 'Warning Color', 'appica' ),
            'transparent' => false,
            'default'     => '#ffcc00'
        ),
        array(
            'type'        => 'color',
            'id'          => 'color_warning_hover',
            'title'       => __( 'Warning Hover/Focus Color', 'appica' ),
            'transparent' => false,
            'default'     => '#ffd633'
        ),
        array(
            'type'        => 'color',
            'id'          => 'color_danger',
            'title'       => __( 'Danger Color', 'appica' ),
            'transparent' => false,
            'default'     => '#ff2d55'
        ),
        array(
            'type'        => 'color',
            'id'          => 'color_danger_hover',
            'title'       => __( 'Danger Hover/Focus Color', 'appica' ),
            'transparent' => false,
            'default'     => '#ff617e'
        ),
        array(
            'type'        => 'color',
            'id'          => 'color_text_light',
            'title'       => __( 'Light Gray Color', 'appica' ),
            'transparent' => false,
            'default'     => '#c4c4c4'
        ),
        array(
            'type'        => 'color',
            'id'          => 'color_text_dark',
            'title'       => __( 'Dark Gray Color', 'appica' ),
            'transparent' => false,
            'default'     => '#8e8e93'
        )
    )
) );

/*
 * Typography section
 */
Redux::setSection( $opt_name, array(
    'title'  => __( 'Typography', 'appica' ),
    'desc'   => __( 'Customize your typography', 'appica' ),
    'icon'   => 'el-icon-text-width',
    'fields' => array(
        array(
            'type'     => 'switch',
            'id'       => 'typography_is_google',
            'title'    => __( 'Enable/Disable Google Fonts', 'appica' ),
            'on'       => $text_enable,
            'off'      => $text_disable,
            'default'  => true
        ),
        array(
            'type'     => 'text',
            'id'       => 'typography_google_font',
            'title'    => __( 'Google Font link', 'appica' ),
            'desc'     => '<p class="description">' . __( 'Go to <a href="http://www.google.com/fonts" target="_blank">google.com/fonts</a>, click "Quick-use" button and follow the instructions. From step 3 copy the "href" value and paste in field above.', 'appica' ) . '</p>',
            'default'  => 'http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,300,800',
            'required' => array( 'typography_is_google', '=', 1 )
        ),
        array(
            'type'     => 'text',
            'id'       => 'typography_font_family',
            'title'    => __( 'Body Font Family', 'appica' ),
            'desc'     => '<p class="description">' . __( 'Put chosen google font (do not forget about quotation marks) along with fallback fonts, separated by comma.', 'appica' ) . '</p>',
            'default'  => '\'Open Sans\', Helvetica, Arial, sans-serif'
        ),
        array(
            'type'    => 'slider',
            'id'      => 'typography_body_font_size',
            'title'   => __( 'Body Font size', 'appica' ),
            'desc'    => '<p class="description">' . __( 'Min: 1px, Max: 50px, Step: 1px', 'appica' ) . '</p>',
            'min'     => 0,
            'max'     => 50,
            'step'    => 1,
            'default' => 16
        ),
        array(
            'type'    => 'slider',
            'id'      => 'typography_smaller_font_size',
            'title'   => __( 'Smaller Font size', 'appica' ),
            'desc'    => '<p class="description">' . __( 'Min: 1px, Max: 50px, Step: 1px', 'appica' ) . '</p>',
            'min'     => 0,
            'max'     => 50,
            'step'    => 1,
            'default' => 14
        ),
        array(
            'type'    => 'slider',
            'id'      => 'typography_h1_font_size',
            'title'   => __( 'Heading 1 (h1) Font size', 'appica' ),
            'desc'    => '<p class="description">' . __( 'Min: 1px, Max: 72px, Step: 1px', 'appica' ) . '</p>',
            'min'     => 0,
            'max'     => 72,
            'step'    => 1,
            'default' => 48
        ),
        array(
            'type'    => 'select',
            'id'      => 'typography_h1_font_weight',
            'title'   => __( 'Heading 1 (h1) Font weight', 'appica' ),
            'options' => $font_weight,
            'default' => '300'
        ),
        array(
            'type'    => 'select',
            'id'      => 'typography_h1_text_transform',
            'title'   => __( 'Heading 1 (h1) Text Transform', 'appica' ),
            'options' => $text_transform,
            'default' => 'none'
        ),
        array(
            'type'    => 'slider',
            'id'      => 'typography_h2_font_size',
            'title'   => __( 'Heading 2 (h2) Font size', 'appica' ),
            'desc'    => '<p class="description">' . __( 'Min: 1px, Max: 50px, Step: 1px', 'appica' ) . '</p>',
            'min'     => 0,
            'max'     => 50,
            'step'    => 1,
            'default' => 36
        ),
        array(
            'type'    => 'select',
            'id'      => 'typography_h2_font_weight',
            'title'   => __( 'Heading 2 (h2) Font weight', 'appica' ),
            'options' => $font_weight,
            'default' => '300'
        ),
        array(
            'type'    => 'select',
            'id'      => 'typography_h2_text_transform',
            'title'   => __( 'Heading 2 (h2) Text Transform', 'appica' ),
            'options' => $text_transform,
            'default' => 'none'
        ),
        array(
            'type'    => 'slider',
            'id'      => 'typography_h3_font_size',
            'title'   => __( 'Heading 3 (h3) Font size', 'appica' ),
            'desc'    => '<p class="description">' . __( 'Min: 1px, Max: 50px, Step: 1px', 'appica' ) . '</p>',
            'min'     => 0,
            'max'     => 50,
            'step'    => 1,
            'default' => 24
        ),
        array(
            'type'    => 'select',
            'id'      => 'typography_h3_font_weight',
            'title'   => __( 'Heading 3 (h3) Font weight', 'appica' ),
            'options' => $font_weight,
            'default' => '300'
        ),
        array(
            'type'    => 'select',
            'id'      => 'typography_h3_text_transform',
            'title'   => __( 'Heading 3 (h3) Text Transform', 'appica' ),
            'options' => $text_transform,
            'default' => 'none'
        ),
        array(
            'type'    => 'slider',
            'id'      => 'typography_h4_font_size',
            'title'   => __( 'Heading 4 (h4) Font size', 'appica' ),
            'desc'    => '<p class="description">' . __( 'Min: 1px, Max: 50px, Step: 1px', 'appica' ) . '</p>',
            'min'     => 0,
            'max'     => 50,
            'step'    => 1,
            'default' => 18
        ),
        array(
            'type'    => 'select',
            'id'      => 'typography_h4_font_weight',
            'title'   => __( 'Heading 4 (h4) Font weight', 'appica' ),
            'options' => $font_weight,
            'default' => '400'
        ),
        array(
            'type'    => 'select',
            'id'      => 'typography_h4_text_transform',
            'title'   => __( 'Heading 4 (h4) Text Transform', 'appica' ),
            'options' => $text_transform,
            'default' => 'none'
        ),
        array(
            'type'    => 'slider',
            'id'      => 'typography_h5_font_size',
            'title'   => __( 'Heading 5 (h5) Font size', 'appica' ),
            'desc'    => '<p class="description">' . __( 'Min: 1px, Max: 50px, Step: 1px', 'appica' ) . '</p>',
            'min'     => 0,
            'max'     => 50,
            'step'    => 1,
            'default' => 16
        ),
        array(
            'type'    => 'select',
            'id'      => 'typography_h5_font_weight',
            'title'   => __( 'Heading 5 (h5) Font weight', 'appica' ),
            'options' => $font_weight,
            'default' => '600'
        ),
        array(
            'type'    => 'select',
            'id'      => 'typography_h5_text_transform',
            'title'   => __( 'Heading 5 (h5) Text Transform', 'appica' ),
            'options' => $text_transform,
            'default' => 'none'
        ),
        array(
            'type'    => 'slider',
            'id'      => 'typography_h6_font_size',
            'title'   => __( 'Heading 6 (h6) Font size', 'appica' ),
            'desc'    => '<p class="description">' . __( 'Min: 1px, Max: 50px, Step: 1px', 'appica' ) . '</p>',
            'min'     => 0,
            'max'     => 50,
            'step'    => 1,
            'default' => 14
        ),
        array(
            'type'    => 'select',
            'id'      => 'typography_h6_font_weight',
            'title'   => __( 'Heading 6 (h6) Font weight', 'appica' ),
            'options' => $font_weight,
            'default' => '700'
        ),
        array(
            'type'    => 'select',
            'id'      => 'typography_h6_text_transform',
            'title'   => __( 'Heading 6 (h6) Text Transform', 'appica' ),
            'options' => $text_transform,
            'default' => 'none'
        ),
        array(
            'type'    => 'slider',
            'id'      => 'typography_badge_font_size',
            'title'   => __( 'Badge Font size', 'appica' ),
            'desc'    => '<p class="description">' . __( 'Min: 1px, Max: 50px, Step: 1px', 'appica' ) . '</p>',
            'min'     => 0,
            'max'     => 50,
            'step'    => 1,
            'default' => 14
        ),
        array(
            'type'    => 'select',
            'id'      => 'typography_badge_font_weight',
            'title'   => __( 'Badge Font weight', 'appica' ),
            'options' => $font_weight,
            'default' => '400'
        ),
        array(
            'type'    => 'select',
            'id'      => 'typography_badge_text_transform',
            'title'   => __( 'Badge Text Transform', 'appica' ),
            'options' => $text_transform,
            'default' => 'none'
        )
    )
) );

/*
 * Socials section
 */
Redux::setSection( $opt_name, array(
    'title'  => __( 'Socials', 'appica' ),
    'desc'   => __( 'Setup your social networks', 'appica' ),
    'icon'   => 'el-icon-group',
    'fields' => array(
        array(
            'id'       => 'socials_networks',
            'type'     => 'social_networks',
            'title'    => __( 'Social Networks List', 'appica' ),
            'subtitle' => __( 'Select as many social networks, as you want', 'appica' )
        ),
	    array(
		    'type'    => 'select',
		    'id'      => 'socials_networks_target',
		    'title'   => __( 'Open social links in', 'appica' ),
		    'desc'    => '<p class="description">' . __( 'This option affects social links in all advertised locations, like off-canvas, intro section and navbar.', 'appica' ) . '</p>',
		    'default' => '_blank',
		    'options' => array(
			    '_self'  => __( 'Current tab', 'appica' ),
			    '_blank' => __( 'New tab', 'appica' )
		    )
	    ),
        array(
            'id'       => 'socials_mailchimp',
            'type'     => 'text',
            'title'    => __( 'MailChimp URL', 'appica' ),
            'subtitle' => __( 'Setup your subscription url globally', 'appica' ),
            'desc'     => '<p class="description">' . __( 'This URL can be retrieved from your mailchimp dashboard > Lists > your desired list > list settings > forms. in your form creation page you will need to click on "share it" tab then find "Your subscribe form lives at this URL:". Its a short URL so you will need to visit this link. Once you get into the your created form page, then copy the full address and paste it here in this form. URL look like http://YOUR_USER_NAME.us6.list-manage.com/subscribe?u=d5f4e5e82a59166b0cfbc716f&id=4db82d169b', 'appica' ) . '</p>'
        ),
        array(
            'id'       => 'socials_subscribe_label',
            'type'     => 'text',
            'title'    => __( 'Subscribe link text', 'appica' ),
            'subtitle' => __( 'Global text, applied in intro, navbar, offcanvas, etc', 'appica' ),
            'desc'     => '<p class="description">' . __( 'Note: this text also applied in modal window as a title.', 'appica' ) . '</p>'
        ),
        array(
            'id'       => 'flickr_api_key',
            'type'     => 'text',
            'title'    => __( 'Flickr API Key', 'appica' ),
            'desc'     => '<div class="description"><ol><li>' . __( 'Go to <a href="https://www.flickr.com/services/apps/create" target="_blank">https://www.flickr.com/services/apps/create</a>', 'appica' ) . '</li><li>' . __( 'Click "Request an API Key"', 'appica' ) . '</li><li>' . __( 'Choose the application type', 'appica' ) . '</li><li>' . __( 'Register you app', 'appica' ) . '</li><li>' . __( 'Copy the "key" value in the field above', 'appica' ) . '</li></ol></div>'
        ),
        array(
            'id'       => 'twitter_screen_name',
            'type'     => 'text',
            'title'    => __( 'Twitter Name', 'appica' )
        ),
        array(
            'id'       => 'twitter_consumer_key',
            'type'     => 'text',
            'title'    => __( 'Twitter Consumer Key', 'appica' ),
            'desc'     => '<div class="description"><ol><li>' . __( 'Go to <a href="https://apps.twitter.com/" target="_blank">https://apps.twitter.com/</a>', 'appica' ) . '</li><li>' . __( 'Click on "Create new app"', 'appica' ) . '</li><li>' . __( 'Register your app', 'appica' ) . '</li><li>' . __( 'Go to "Keys and Access Tokens"', 'appica' ) . '</li><li>' . __( 'Copy consumer key and secret from "Application Settings" section and paste in respective fields', 'appica' ) . '</li></ol></div>'
        ),
        array(
            'id'       => 'twitter_consumer_secret',
            'type'     => 'text',
            'title'    => __( 'Twitter Consumer Secret', 'appica' )
        )
    )
) );

/*
 * Intro section
 */
Redux::setSection( $opt_name, array(
    'title'  => __( 'Intro Section', 'appica' ),
    'desc'   => __( 'Customize your Intro Screen', 'appica' ),
    'icon'   => 'el-icon-photo',
    'fields' => array(
        array(
            'type'    => 'switch',
            'id'      => 'intro_is_enabled',
            'title'   => __( 'Enable/Disable Intro Section', 'appica' ),
            'on'      => $text_enable,
            'off'     => $text_disable,
            'default' => true
        ),
	    array(
		    'type'     => 'select',
		    'id'       => 'intro_type',
		    'title'    => __( 'Type', 'appica' ),
		    'desc'     => '<p class="description">' . __( 'Choose your Intro Screen type', 'appica' ) . '</p>',
		    'default'  => 'appshowcase',
		    'options'  => array(
			    'appshowcase' => __( 'App Showcase', 'appica' ),
			    'revslider'   => __( 'Revolution Slider', 'appica' ),
		    ),
		    'required' => array( 'intro_is_enabled', '=', 1 ),
	    ),
	    array(
		    'type'     => 'select',
		    'id'       => 'intro_revslider',
		    'title'    => __( 'Revolution Slider', 'appica' ),
		    'desc'     => '<p class="description">' . __( 'Select your Revolution Slider.', 'appica' ) . '</p>',
		    'required' => array(
			    array( 'intro_is_enabled', '=', 1 ),
			    array( 'intro_type', '=', 'revslider' )
		    ),
		    'options'  => call_user_func( 'appica_get_revslider_items' ),
	    ),
	    array(
		    'type'     => 'media',
		    'id'       => 'intro_background',
		    'title'    => __( 'Intro Background', 'appica' ),
		    'subtitle' => __( 'Customize the background', 'appica' ),
		    'desc'     => '<p class="description">' . __( 'Optimal size is 1920x1200px', 'appica' ) . '</p>',
		    'compiler' => false,
		    'required' => array(
			    array( 'intro_is_enabled', '=', 1 ),
			    array( 'intro_type', '=', 'appshowcase' )
		    )
	    ),
        array(
            'type'    => 'switch',
            'id'      => 'intro_is_overlay',
            'title'   => __( 'Enable/Disable Background Overlay', 'appica' ),
            'default' => true,
            'on'      => $text_enable,
            'off'     => $text_disable,
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' )
            )
        ),
        array(
            'type'     => 'select',
            'id'       => 'intro_overlay_type',
            'title'    => __( 'Intro Overaly color type', 'appica' ),
            'options'  => array(
                'solid'    => __( 'Solid', 'appica' ),
                'gradient' => __( 'Gradient', 'appica' )
            ),
            'default'  => 'gradient',
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_overlay', '=', 1 )
            )
        ),
        array(
            'type'     => 'color_rgba',
            'id'       => 'intro_overlay_color',
            'title'    => __( 'Solid Overlay Color', 'appica' ),
            'desc'     => '<p class="description">' . __( 'Overlay color in RGBA format', 'appica' ) . '</p>',
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_overlay', '=', 1 ),
                array( 'intro_overlay_type', '=', 'solid' )
            ),
            'options'  => array(
                'show_input'             => true, // set to "true" for custom rgba() field
                'show_initial'           => true,
                'show_alpha'             => true,
                'show_palette'           => false,
                'show_palette_only'      => false,
                'show_selection_palette' => true,
                'max_palette_size'       => 10,
                'allow_empty'            => true,
                'clickout_fires_change'  => false,
                'show_buttons'           => true,
                'use_extended_classes'   => true,
                'palette'                => null,  // show default
                'input_text'             => __( 'Select Color', 'appica' ),
                'choose_text'            => __( 'Choose', 'appica' ),
                'cancel_text'            => __( 'Cancel', 'appica' )
            ),
            'default'  => array(
                'color' => '#3A1CFF',
                'alpha' => 0.75
            )
        ),
        array(
            'type'        => 'color_gradient',
            'id'          => 'intro_overlay_gradient',
            'title'       => __( 'Gradient Overlay', 'appica' ),
            'validate'    => 'color',
            'transparent' => false,
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_overlay', '=', 1 ),
                array( 'intro_overlay_type', '=', 'gradient' )
            ),
            'default'     => array(
                'from' => '#3a1cff',
                'to'   => '#ff3a30'
            )
        ),
        array(
            'type'          => 'slider',
            'id'            => 'intro_overlay_opacity',
            'title'         => __( 'Overlay Opacity', 'appica' ),
            'desc'          => __( 'Min: 0, Max: 1, Step: 0.01', 'appica' ),
            'default'       => 0.75,
            'min'           => 0,
            'max'           => 1,
            'step'          => 0.01,
            'display_value' => 'text',
            'resolution'    => 0.01,
            'float_mark'    => '.',
            'required'      => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_overlay', '=', 1 )
            )
        ),
        array(
            'type'     => 'media',
            'id'       => 'intro_logo',
            'title'    => __( 'Intro Logo', 'appica' ),
            'desc'     => __( 'Logo optimal size is 360x360px', 'appica' ),
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' )
            )
        ),
        array(
            'type'        => 'text',
            'id'          => 'intro_title',
            'title'       => $text_title,
            'placeholder' => 'Appica 2',
            'required'    => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' )
            )
        ),
        array(
            'type'        => 'text',
            'id'          => 'intro_subtitle',
            'title'       => $text_subtitle,
            'placeholder' => 'Flatter, lighter, appler, stronger',
            'required'    => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' )
            )
        ),
	    array(
		    'type'     => 'select',
		    'id'       => 'intro_device_color',
		    'title'    => __( 'Device Color', 'appica' ),
		    'required' => array(
			    array( 'intro_is_enabled', '=', 1 ),
			    array( 'intro_type', '=', 'appshowcase' )
		    ),
		    'default'  => 'gold',
		    'options'  => array(
			    'gold'       => __( 'Gold', 'appica' ),
			    'silver'     => __( 'Silver', 'appica' ),
			    'space-gray' => __( 'Space Gray', 'appica' )
		    ),

	    ),
        array(
            'type'     => 'media',
            'id'       => 'intro_iphone_screen',
            'title'    => __( 'Device Screen Upload', 'appica' ),
            'desc'     => '<p class="description">' . __( 'Optimal size is 652x1186px', 'appica' ) . '</p>',
            'mode'     => 'image',
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' )
            )
        ),
        array(
            'type'     => 'switch',
            'id'       => 'intro_is_social',
            'title'    => __( 'Social Networks', 'appica' ),
            'subtitle' => __( 'Show social networks in Intro Screen?', 'appica' ),
            'desc'     => '<p class="description">' . __( 'Note: You have to setup social networks list in "Socials" settings section.', 'appica' ) . '</p>',
            'on'       => $text_show,
            'off'      => $text_hide,
            'default'  => true,
            'required' => array( 'intro_is_enabled', '=', 1 ),
        ),
        array(
            'type'     => 'switch',
            'id'       => 'intro_is_subscribe',
            'title'    => __( 'Enable/Disable Subscription', 'appica' ),
            'subtitle' => __( 'MailChimp', 'appica' ),
            'desc'     => '<p class="description">' . __( 'You can customize your MailChimp link in "Socials" section. Also, do not forget to fill in link label.', 'appica' ) . '</p>',
            'on'       => $text_enable,
            'off'      => $text_disable,
            'default'  => true,
            'required' => array( 'intro_is_enabled', '=', 1 ),
        ),
        array(
            'type'     => 'switch',
            'id'       => 'intro_is_scroll',
            'title'    => __( 'Enable/Disable Scroll for More Button', 'appica' ),
            'on'       => $text_enable,
            'off'      => $text_disable,
            'default'  => true,
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' )
            )
        ),
        array(
            'type'     => 'text',
            'id'       => 'intro_scroll_text',
            'title'    => __( 'Scroll for More Button Text', 'appica' ),
            'default'  => 'Scroll for More',
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_scroll', '=', 1 )
            )
        ),
        array(
            'type'        => 'text',
            'id'          => 'intro_scroll_anchor',
            'title'       => __( 'Scroll for More Anchor', 'appica' ),
            'desc'        => '<p class="description">' . __( 'Set the ID of element, e.g. #my-first-block, where page will be scrolled to', 'appica' ) . '</p>',
            'placeholder' => '#block-id-here',
            'required'    => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_scroll', '=', 1 )
            )
        ),
        array(
            'type'     => 'switch',
            'id'       => 'intro_is_download',
            'title'    => __( 'Enable/Disable Download Button', 'appica' ),
            'on'       => $text_enable,
            'off'      => $text_disable,
            'default'  => true,
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' )
            )
        ),
        array(
            'type'        => 'text',
            'id'          => 'intro_download_helper',
            'title'       => __( 'Download Button helper', 'appica' ),
            'placeholder' => __( 'e.g. iPhone and iPad versions', 'appica' ),
            'required'    => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_download', '=', 1 )
            )
        ),
        array(
            'type'     => 'text',
            'id'       => 'intro_download_text',
            'title'    => __( 'Download Button text', 'appica' ),
            'desc'     => '<p class="description">' . __( 'Note: you can change only the first line of button text', 'appica' ) . '</p>',
            'default'  => __( 'Download', 'appica' ),
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_download', '=', 1 )
            )
        ),
        array(
            'type'     => 'text',
            'id'       => 'intro_download_url',
            'title'    => __( 'Download Button URL', 'appica' ),
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_download', '=', 1 )
            )
        ),
        array(
            'type'     => 'switch',
            'id'       => 'intro_is_features',
            'title'    => __( 'Enable/Disable Features', 'appica' ),
            'subtitle' => __( 'List of features to the right side of device', 'appica' ),
            'on'       => $text_enable,
            'off'      => $text_disable,
            'default'  => true,
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' )
            )
        ),
        array(
            'type'     => 'icon',
            'id'       => 'intro_feature_1_icon',
            'title'    => __( 'First Feature Icon', 'appica' ),
            'pack'     => 'flaticons',
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_features', '=', 1 )
            )
        ),
        array(
            'type'     => 'text',
            'id'       => 'intro_feature_1_title',
            'title'    => __( 'First Feature Title', 'appica' ),
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_features', '=', 1 )
            )
        ),
        array(
            'type'         => 'textarea',
            'id'           => 'intro_feature_1_desc',
            'title'        => __( 'First Feature Description', 'appica' ),
            'subtitle'     => $text_html_allowed,
            'desc'         => "<p class=\"description\">{$text_tags_allowed}</p>",
            'required'     => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_features', '=', 1 )
            ),
            'allowed_html' => array(
                'a'      => array( 'href'  => array(), 'title' => array(), 'target' => array(), 'class' => array() ),
                'br'     => array(),
                'em'     => array(),
                'strong' => array()
            )
        ),
        array(
            'type'     => 'icon',
            'id'       => 'intro_feature_2_icon',
            'title'    => __( 'Second Feature Icon', 'appica' ),
            'pack'     => 'flaticons',
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_features', '=', 1 )
            )
        ),
        array(
            'type'     => 'text',
            'id'       => 'intro_feature_2_title',
            'title'    => __( 'Second Feature Title', 'appica' ),
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_features', '=', 1 )
            )
        ),
        array(
            'type'         => 'textarea',
            'id'           => 'intro_feature_2_desc',
            'title'        => __( 'Second Feature Description', 'appica' ),
            'subtitle'     => $text_html_allowed,
            'desc'         => "<p class=\"description\">{$text_tags_allowed}</p>",
            'required'     => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_features', '=', 1 )
            ),
            'allowed_html' => array(
                'a'      => array( 'href'  => array(), 'title' => array(), 'target' => array(), 'class' => array() ),
                'br'     => array(),
                'em'     => array(),
                'strong' => array()
            )
        ),
        array(
            'type'     => 'icon',
            'id'       => 'intro_feature_3_icon',
            'title'    => __( 'Third Feature Icon', 'appica' ),
            'pack'     => 'flaticons',
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_features', '=', 1 )
            )
        ),
        array(
            'type' => 'text',
            'id' => 'intro_feature_3_title',
            'title' => __( 'Third Feature Title', 'appica' ),
            'required' => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_features', '=', 1 )
            )
        ),
        array(
            'type'         => 'textarea',
            'id'           => 'intro_feature_3_desc',
            'title'        => __( 'Third Feature Description', 'appica' ),
            'subtitle'     => $text_html_allowed,
            'desc'         => "<p class=\"description\">{$text_tags_allowed}</p>",
            'required'     => array(
	            array( 'intro_is_enabled', '=', 1 ),
	            array( 'intro_type', '=', 'appshowcase' ),
                array( 'intro_is_features', '=', 1 )
            ),
            'allowed_html' => array(
                'a'      => array( 'href'  => array(), 'title' => array(), 'target' => array(), 'class' => array() ),
                'br'     => array(),
                'em'     => array(),
                'strong' => array()
            )
        )
    )
) );

/*
 * Off-canvas navigation section
 */
Redux::setSection( $opt_name, array(
    'icon'   => 'el-icon-lines',
    'title'  => __( 'Off-Canvas Navi', 'appica' ),
    'desc'   => __( 'Customize the off-canvas navigation panel', 'appica' ),
    'fields' => array(
        array(
            'type'     => 'switch',
            'id'       => 'offcanvas_is_search',
            'title'    => __( 'Enable/Disable Search', 'appica' ),
            'subtitle' => __( 'Show or hide search form in off-canvas navigation', 'appica' ),
            'on'       => $text_enable,
            'off'      => $text_disable,
            'default'  => true
        ),
        array(
            'type'     => 'switch',
            'id'       => 'offcanvas_is_socials',
            'title'    => __( 'Enable/Disable Socials', 'appica' ),
            'subtitle' => __( 'Show or hide social networks list in off-canvas navigation', 'appica' ),
            'on'       => $text_enable,
            'off'      => $text_disable,
            'default'  => true
        ),
        array(
            'type'     => 'media',
            'id'       => 'offcanvas_logo',
            'title'    => __( 'Logo', 'appica' ),
            'subtitle' => __( 'Inside the Off-Canvas panel', 'appica' ),
            'desc'     => '<p class="description">' . __( 'Optimal size is 180x180px', 'appica' ) . '</p>',
            'mode'     => 'image'
        ),
        array(
            'type'        => 'text',
            'id'          => 'offcanvas_title',
            'title'       => $text_title,
            'placeholder' => 'Appica 2'
        ),
        array(
            'type'        => 'text',
            'id'          => 'offcanvas_subtitle',
            'title'       => $text_subtitle,
            'placeholder' => 'Flatter, lighter, appler'
        ),
        array(
            'type'     => 'spinner',
            'id'       => 'offcanvas_anchor_el_num',
            'title'    => __( 'Number of menu items in Anchor menu', 'appica' ),
            'subtitle' => __( 'per one column', 'appica' ),
            'desc'     => '<p class="description">' . __( 'Min: 1, Max: 100, Step: 1', 'appica' ) . '</p>',
            'min'      => 1,
            'max'      => 100,
            'step'     => 1,
            'default'  => 7
        ),
        array(
            'type'     => 'switch',
            'id'       => 'offcanvas_is_download',
            'title'    => __( 'Enable/Disable Download Button', 'appica' ),
            'subtitle' => __( 'Show or hide custom button link in off-canvas navigation', 'appica' ),
            'on'       => $text_enable,
            'off'      => $text_disable,
            'default'  => true
        ),
        array(
            'type'        => 'text',
            'id'          => 'offcanvas_download_label',
            'title'       => __( 'Download Button Text', 'appica' ),
            'placeholder' => __( 'Download or any other string', 'appica' ),
            'required'    => array( 'offcanvas_is_download', '=', 1 )
        ),
        array(
            'type'        => 'text',
            'id'          => 'offcanvas_download_url',
            'title'       => __( 'Download Button URL', 'appica' ),
            'placeholder' => 'http://...',
            'required'    => array( 'offcanvas_is_download', '=', 1 )
        ),
        array(
            'type'     => 'switch',
            'id'       => 'offcanvas_is_subscribe',
            'title'    => __( 'Enable/Disable Subscribe', 'appica' ),
            'subtitle' => __( 'Hide or show MailChimp subscription link', 'appica' ),
            'desc'     => '<p class="description">' . __( 'You can customize your MailChimp URL in "Socials" section', 'appica' ) . '</p>',
            'on'       => __( 'Enable', 'appica' ),
            'off'      => __( 'Disable', 'appica' ),
            'default'  => true
        )
    )
) );

/*
 * Navbar section
 */
Redux::setSection( $opt_name, array(
    'icon'   => 'el-icon-lines',
    'title'  => __( 'Navbar', 'appica' ),
    'fields' => array(
        array(
            'type'     => 'switch',
            'id'       => 'navbar_is_sticky',
            'title'    => __( 'Sticky Navbar', 'appica' ),
            'subtitle' => __( 'Enable/Disable Sticky Navbar', 'appica' ),
            'desc'     => '<p class="description">' . __( 'If enabled this makes navbar stick to top of the page when scrolling. Note, that on Front Page this option also enabled Sticky Footer, which makes footer fixed to the bottom of the page and revealed on scroll.', 'appica' ) . '</p>',
            'on'       => $text_enable,
            'off'      => $text_disable,
            'default'  => false
        ),
        array(
            'type'     => 'media',
            'id'       => 'navbar_logo',
            'title'    => __( 'Logo', 'appica' ),
            'subtitle' => __( 'Choose logo for navigation bar', 'appica' ),
            'desc'     => '<p class="description">' . __( 'Optimal size is 96x96px', 'appica' ) . '</p>',
            'mode'     => 'image'
        ),
        array(
            'type'     => 'text',
            'id'       => 'navbar_title',
            'title'    => $text_title,
            'subtitle' => __( 'Navbar title', 'appica' ),
            'default'  => 'Appica 2'
        ),
        array(
            'type'     => 'switch',
            'id'       => 'navbar_is_social',
            'title'    => __( 'Social Networks', 'appica' ),
            'subtitle' => __( 'Enable/Disable Social Networks in Navbar', 'appica' ),
            'desc'     => '<p class="description">' . __( 'Note: You have to setup social networks list in Socials settings section.', 'appica' ) . '</p>',
            'on'       => $text_enable,
            'off'      => $text_disable,
            'default'  => true
        ),
        array(
            'type'     => 'switch',
            'id'       => 'navbar_is_download',
            'title'    => __( 'Download Button', 'appica' ),
            'subtitle' => __( 'Enable/Disable Download Button', 'appica' ),
            'on'       => $text_enable,
            'off'      => $text_disable,
            'default'  => true
        ),
        array(
            'type'     => 'text',
            'id'       => 'navbar_download_helper',
            'title'    => __( 'Helper Text', 'appica' ),
            'required' => array( 'navbar_is_download', '=', 1 ),
            'default'  => __( 'iPhone and iPad versions', 'appica' )
        ),
        array(
            'type'     => 'text',
            'id'       => 'navbar_download_button_text',
            'title'    => __( 'Download Button Text', 'appica' ),
            'required' => array( 'navbar_is_download', '=', 1 ),
            'default'  => __( 'Download', 'appica' )
        ),
        array(
            'type'     => 'text',
            'id'       => 'navbar_download_button_url',
            'title'    => __( 'Download Button URL', 'appica' ),
            'required' => array( 'navbar_is_download', '=', 1 )
        ),
        array(
            'id'       => 'navbar_is_subscribe',
            'type'     => 'switch',
            'title'    => __( 'Enable/Disable Subscription', 'appica' ),
            'subtitle' => __( 'MailChimp', 'appica' ),
            'desc'     => '<p class="description">' . __( 'You can customize your MailChimp URL in "Socials" section', 'appica' ) . '</p>',
            'on'       => $text_enable,
            'off'      => $text_disable,
            'default'  => true
        ),
        array(
            'type'          => 'slider',
            'id'            => 'navbar_width',
            'title'         => __( 'Navbar max-width to convert to mobile version', 'appica' ),
            'desc'          => '<p class="description">' . __( 'Since we do not know how much information will be in your navbar that\'s why you to decide when it converts to mobile version to prevent content reflow.', 'appica' ) . '</p>'
                               . '<p class="description">' . __( 'Min: 1, Max: 1400, Step: 1', 'appica' ) . '</p>',
            'default'       => 991,
            'min'           => 0,
            'max'           => 1400,
            'step'          => 1,
            'display_value' => 'text',
            'float_mark'    => '.'
        )
    )
) );

/*
 * Footer section
 */
Redux::setSection( $opt_name, array(
    'title'  => __( 'Footer', 'appica' ),
    'desc'   => __( 'Customize your Footer', 'appica' ),
    'icon'   => 'el-icon-tasks',
    'fields' => array(
        array(
            'type'     => 'switch',
            'id'       => 'footer_is_nav',
            'title'    => __( 'Footer navigation', 'appica' ),
            'subtitle' => __( 'Enable/Disable Footer Menu Location', 'appica' ),
            'desc'     => __( 'See Appearance > Menus', 'appica' ),
            'on'       => $text_enable,
            'off'      => $text_disable,
            'default'  => true
        ),
        array(
            'type'     => 'editor',
            'id'       => 'footer_copyright',
            'title'    => __( 'Copyright', 'appica' ),
            'subtitle' => __( 'Enter your copyrights', 'appica' ),
            'default'  => '2015 &copy; 8Guild. Premium themes',
            'args'     => array(
                'wpautop'       => false,
                'media_buttons' => false,
                'teeny'         => true
            )
        ),
        array(
            'type'     => 'switch',
            'id'       => 'footer_is_device',
            'title'    => __( 'Device', 'appica' ),
            'subtitle' => __( 'Enable/Disable Device', 'appica' ),
            'on'       => $text_enable,
            'off'      => $text_disable,
            'default'  => true
        ),
	    array(
		    'type'     => 'select',
		    'id'       => 'footer_device_color',
		    'title'    => __( 'Device color', 'appica' ),
		    'required' => array( 'footer_is_device', '=', 1 ),
		    'default'  => 'gold',
		    'options'  => array(
			    'gold'       => __( 'Gold', 'appica' ),
			    'silver'     => __( 'Silver', 'appica' ),
			    'space-gray' => __( 'Space Gray', 'appica' )
		    ),
	    ),
        array(
            'type'     => 'media',
            'id'       => 'footer_device_screen',
            'title'    => __( 'Device Screen', 'appica' ),
            'desc'     => '<p class="description">' . __( 'Optimal size is 682x383px', 'appica' ) . '</p>',
            'mode'     => 'image',
            'required' => array( 'footer_is_device', '=', 1 )
        ),
        array(
            'type'     => 'switch',
            'id'       => 'footer_is_app',
            'title'    => __( 'Enable/Disable App Widget', 'appica' ),
            'on'       => $text_enable,
            'off'      => $text_disable,
            'default'  => true
        ),
        array(
            'type'     => 'media',
            'id'       => 'footer_logo',
            'title'    => __( 'App Logo', 'appica' ),
            'subtitle' => __( 'Choose your custom footer app logo', 'appica' ),
            'desc'     => '<p class="description">' . __( 'Logo optimal size is 240x240px', 'appica' ) . '</p>',
            'mode'     => 'image',
            'required' => array( 'footer_is_app', '=', 1 )
        ),
        array(
            'id'          => 'footer_app_name',
            'type'        => 'text',
            'title'       => __( 'Your App Name', 'appica' ),
            'placeholder' => __( 'Your App Name', 'appica' ),
            'required'    => array( 'footer_is_app', '=', 1 )
        ),
        array(
            'id'       => 'footer_is_app_tagline',
            'type'     => 'switch',
            'title'    => __( 'Tagline', 'appica' ),
            'subtitle' => __( 'Show / Hide your app custom tagline', 'appica' ),
            'on'       => $text_show,
            'off'      => $text_hide,
            'default'  => false,
            'required' => array( 'footer_is_app', '=', 1 )
        ),
        array(
            'id'          => 'footer_app_tagline',
            'type'        => 'text',
            'title'       => __( 'Your App tagline', 'appica' ),
            'desc'        => __( 'E.g. "Flatter, lighter, appler..." or any custom string', 'appica' ),
            'placeholder' => __( 'Your App tagline', 'appica' ),
            'required'    => array(
                array( 'footer_is_app', '=', 1 ),
                array( 'footer_is_app_tagline', '=', 1 )
            )
        ),
        array(
            'id'       => 'footer_is_app_content_rating',
            'type'     => 'switch',
            'title'    => __( 'Content rating', 'appica' ),
            'subtitle' => __( 'Show / Hide your app content rating', 'appica' ),
            'on'       => $text_show,
            'off'      => $text_hide,
            'default'  => false,
            'required' => array( 'footer_is_app', '=', 1 )
        ),
        array(
            'id'          => 'footer_app_content_rating',
            'type'        => 'text',
            'title'       => __( 'Your App content rating', 'appica' ),
            'desc'        => __( 'E.g. "18+" or "9+" or any custom string you want', 'appica' ),
            'placeholder' => __( 'Your App content rating', 'appica' ),
            'required'    => array(
                array( 'footer_is_app', '=', 1 ),
                array( 'footer_is_app_content_rating', '=', 1 )
            )
        ),
        array(
            'id'       => 'footer_is_app_rating',
            'type'     => 'switch',
            'title'    => __( 'Display App Rating', 'appica' ),
            'subtitle' => __( 'Show / Hide your app rating', 'appica' ),
            'on'       => $text_show,
            'off'      => $text_hide,
            'default'  => false,
            'required' => array( 'footer_is_app', '=', 1 )
        ),
        array(
            'id'            => 'footer_app_rating',
            'type'          => 'slider',
            'title'         => __( 'App Rating', 'appica' ),
            'desc'          => '<p class="description">' . __( 'Min: 0, Max: 5, Step: 0.1. Also, you can edit field.', 'appica' ) . '</p>',
            'min'           => 0,
            'max'           => 5,
            'step'          => 0.1,
            'default'       => 0,
            'resolution'    => 0.1,
            'display_value' => 'text',
            'required'      => array(
                array( 'footer_is_app', '=', 1 ),
                array( 'footer_is_app_rating', '=', 1 )
            )
        ),
        array(
            'id'       => 'footer_is_app_ratings_counter',
            'type'     => 'switch',
            'title'    => __( 'Display ratings counter', 'appica' ),
            'subtitle' => __( 'Show / Hide your app ratings counter', 'appica' ),
            'on'       => $text_show,
            'off'      => $text_hide,
            'default'  => false,
            'required' => array( 'footer_is_app', '=', 1 )
        ),
        array(
            'id'          => 'footer_app_ratings_counter',
            'type'        => 'text',
            'title'       => __( 'Your App ratings counter', 'appica' ),
            'desc'        => __( 'Any number of votes, in any format, e.g. number or "10K" for shorter form.', 'appica' ),
            'placeholder' => __( 'Your App ratings counter', 'appica' ),
            'required'    => array(
                array( 'footer_is_app', '=', 1 ),
                array( 'footer_is_app_ratings_counter', '=', 1 )
            )
        )
    )
) );

/*
 * Import/Export section
 */
Redux::setSection( $opt_name, array(
    'title'  => __( 'Import / Export', 'appica' ),
    'desc'   => __( 'Import and Export your Redux Framework settings from file, text or URL.', 'appica' ),
    'icon'   => 'el-icon-refresh',
    'fields' => array(
        array(
            'id'         => 'opt-import-export',
            'type'       => 'import_export',
            'title'      => 'Import Export',
            'subtitle'   => 'Save and restore your Redux options',
            'full_width' => false
        )
    )
) );

/*
 * Extensions
 */

/**
 * Add custom field "social_networks" to Redux fields
 *
 * @return string
 */
function appica_redux_field_social_networks() {
    return trailingslashit( __DIR__ ) . 'redux_extensions/social_networks/class-reduxframework-social_networks.php';
}

add_filter( "redux/{$opt_name}/field/class/social_networks", 'appica_redux_field_social_networks' );

/**
 * Add custom field "icon" to Redux fields
 *
 * @return string
 */
function appica_redux_field_icon() {
    return trailingslashit( __DIR__ ) . 'redux_extensions/icon/class-reduxframework-icon.php';
}

add_filter( "redux/{$opt_name}/field/class/icon", 'appica_redux_field_icon' );