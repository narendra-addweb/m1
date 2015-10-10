<?php

/**
 * Appica shortcode
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */
class Appica_Shortcode {
	/**
	 * Instance of class.
	 * @var null|Appica_Shortcode
	 */
	private static $instance;
	/**
	 * Path to shortcode output templates folder.
	 *
	 * 1 - plugin document root folder, 2 - shortcode tag
	 * @var string
	 */
	private $template = '%1$s/templates/shortcodes/%2$s.php';

	/**
	 * Initialization
	 *
	 * @return Appica_Shortcode
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {
		/**
		 * Shortcodes map, where key is shortcode tag, and value is an absolute path to shortcode output template.
		 *
		 * @var array
		 */
		$shortcodes = array(
			'appica_testimonials',
			'appica_custom_title',
			'appica_team',
			'appica_gadgets_slideshow',
			'appica_video_popup',
			'appica_half_block_image',
			'appica_download_btn',
			'appica_recent_posts',
			'appica_fancy_text',
			'appica_timeline',
			'appica_bar_charts',
			'appica_button',
			'appica_feature',
			'appica_team_alt',
			'appica_gallery',
			'appica_pricing_plans',
			'appica_app_gallery',
			'appica_news',
			'appica_posts',
			'appica_contacts',
			'appica_mailchimp_form',
			'appica_portfolio',
		);

		/**
		 * Filter the shortcodes list
		 *
		 * The best place to add or remove shortcode(s).
		 *
		 * @since 1.0.0
		 *
		 * @param array $shortcodes Shortcodes list
		 */
		$shortcodes = apply_filters( 'appica_shortcodes', $shortcodes );

		// add shortcodes
		foreach( $shortcodes as $shortcode ) {
			add_shortcode( $shortcode, array( $this, $shortcode ) );
		}
	}

	/**
	 * Get shortcode output
	 *
	 * @param string      $shortcode Shortcode tag
	 * @param array       $atts      Shortcode attributes
	 * @param string|null $content   Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	private function render( $shortcode, $atts = array(), $content = null ) {
		/**
		 * @var string $html Shortcode html output
		 */
		$html = '';

		/**
		 * @var string Absolute path to default shortcodes templates directory
		 */
		$default = wp_normalize_path( APPICA_CORE_ROOT . '/templates/shortcodes' );

		/**
		 * Filter the absolute path to shortcodes folder.
		 * Allow to override any shortcode templates.
		 *
		 * @since 1.0.0
		 *
		 * @param string $default Default absolute path to shortcode templates folder
		 */
		$path = apply_filters( 'appica_shortcodes_templates_path', $default );
		$path = untrailingslashit( $path );

		// finally, path to shortcode template
		$template = "{$path}/{$shortcode}.php";

		// if user template doesn't exists - use default one
		if ( ! is_readable( $template ) ) {
			$template = "{$default}/{$shortcode}.php";
		}

		if ( is_readable( $template ) ) {
			ob_start();
			require $template;
			$html = ob_get_contents();
			ob_end_clean();
		}

		return $html;
	}

	/**
	 * Shortcode "Custom Title"
	 *
	 * Outputs the fancy title
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_custom_title( $atts, $content = null ) {
		return $this->render( 'appica_custom_title', $atts, $content );
	}

	/**
	 * Shortcode "Testimonials"
	 *
	 * Outputs the same-called CPT
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_testimonials( $atts, $content = null ) {
		return $this->render( 'appica_testimonials', $atts, $content );
	}

	/**
	 * Shortcode "Team"
	 *
	 * Outputs the same-called CPT
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_team( $atts, $content = null ) {
		return $this->render( 'appica_team', $atts, $content );
	}

	/**
	 * Shortcode "Gadgets Slideshow"
	 *
	 * Outputs the same-called CPT
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_gadgets_slideshow( $atts, $content = null ) {
		return $this->render( 'appica_gadgets_slideshow', $atts, $content );
	}

	/**
	 * Shortcode "Video Popup"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_video_popup( $atts, $content = null ) {
		return $this->render( 'appica_video_popup', $atts, $content );
	}

	/**
	 * Shortcode "Half Block Image"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_half_block_image( $atts, $content = null ) {
		return $this->render( 'appica_half_block_image', $atts, $content );
	}

	/**
	 * Shortcode "Download Button"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_download_btn( $atts, $content = null ) {
		return $this->render( 'appica_download_btn', $atts, $content );
	}

	/**
	 * Shortcode "Recent Posts"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_recent_posts( $atts, $content = null ) {
		return $this->render( 'appica_recent_posts', $atts, $content );
	}

	/**
	 * Shortcode "Fancy Text"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_fancy_text( $atts, $content = null ) {
		return $this->render( 'appica_fancy_text', $atts, $content );
	}

	/**
	 * Shortcode "Timeline"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_timeline( $atts, $content = null ) {
		return $this->render( 'appica_timeline', $atts, $content );
	}

	/**
	 * Shortcode "Bar Charts"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_bar_charts( $atts, $content = null ) {
		return $this->render( 'appica_bar_charts', $atts, $content );
	}

	/**
	 * Shortcode "Button"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_button( $atts, $content = null ) {
		return $this->render( 'appica_button', $atts, $content );
	}

	/**
	 * Shortcode "Feature"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_feature( $atts, $content = null ) {
		return $this->render( 'appica_feature', $atts, $content );
	}

	/**
	 * Shortcode "Team 2"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_team_alt( $atts, $content = null ) {
		return $this->render( 'appica_team_alt', $atts, $content );
	}

	/**
	 * Shortcode "Gallery"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_gallery( $atts, $content = null ) {
		return $this->render( 'appica_gallery', $atts, $content );
	}

	/**
	 * Shortcode "Pricing Plans"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_pricing_plans( $atts, $content = null ) {
		return $this->render( 'appica_pricing_plans', $atts, $content );
	}

	/**
	 * Shortcode "App Gallery"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_app_gallery( $atts, $content = null ) {
		return $this->render( 'appica_app_gallery', $atts, $content );
	}

	/**
	 * Shortcode "News"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_news( $atts, $content = null ) {
		return $this->render( 'appica_news', $atts, $content );
	}

	/**
	 * Shortcode "Posts"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_posts( $atts, $content = null ) {
		return $this->render( 'appica_posts', $atts, $content );
	}

	/**
	 * Shortcode "Contacts"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_contacts( $atts, $content = null ) {
		return $this->render( 'appica_contacts', $atts, $content );
	}

	/**
	 * Shortcode "MailChimp Form"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_mailchimp_form( $atts, $content = null ) {
		return $this->render( 'appica_mailchimp_form', $atts, $content );
	}

	/**
	 * Shortcode "Portfolio"
	 *
	 * @param array       $atts    Shortcode attributes
	 * @param null|string $content [optional] Shortcode content
	 *
	 * @return string Shortcode HTML
	 */
	public function appica_portfolio( $atts, $content = null ) {
		return $this->render( 'appica_portfolio', $atts, $content );
	}
}