<?php

/**
 * Class for storing global AJAX handlers for plugin
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */
class Appica_AJAX {
	/**
	 * Instance of class.
	 * @var null|Appica_AJAX
	 */
	private static $instance;

	/**
	 * Initialization
	 *
	 * @return Appica_AJAX
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {
		if ( is_admin() ) {
			add_action( 'wp_ajax_appica_core_icons', array( $this, 'icons_callback' ) );
		}
	}

	/**
	 * AJAX callback for rendering icons popup.
	 *
	 * Outputs HTML
	 *
	 * @since 1.0.0
	 */
	public function icons_callback() {
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'appica-ajax' ) ) {
			die();
		}

		// Current icon, for repeated opening popup
		$current = sanitize_text_field( $_POST['current'] );
		$icons   = Appica_Helpers::get_icons();
		$html    = '';

		if ( 0 !== count( $icons ) ) {
			$_el = array();

			/**
			 * @var string Template for single filterable icon
			 */
			$tpl = '<li data-filtertext="%1$s" %2$s><a href="#" class="appica-core-icon" data-icon="%1$s"><i class="%1$s"></i></a></li>';

			$html .= '<form class="ui-filterable"><input type="text" id="appica-core-icons-filterable-input" class="widefat" data-type="search"></form>';
			$html .= '<ul class="appica-core-filterable-icons clearfix" data-role="listview" data-filter="true" data-input="#appica-core-icons-filterable-input">';

			foreach ( (array) $icons as $icon ) {
				$active = ( $icon === $current ) ? 'class="active"' : '';
				$_el[] = sprintf( $tpl, $icon, $active );
			}

			$html .= implode( '', $_el );
			$html .= '</ul>';
		} else {
			$html .= '<p>' . __( 'No icons found', 'appica' ) . '</p>';
		}

		print $html;

		die;
	}
}