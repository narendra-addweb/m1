<?php

/**
 * Global filters for theme core
 *
 * Contain filters and static methods, which can be called outside of class as a filter
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */
class Appica_Filters {
	/**
	 * Instance of class.
	 * @var null|Appica_Filters
	 */
	private static $instance;

	/**
	 * Initialization
	 *
	 * @return Appica_Filters
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {}

	/**
	 * Change excerpt length, used in widgets.
	 *
	 * @since 1.0.0
	 *
	 * @param int $length The number of words to display
	 *
	 * @return int
	 */
	public static function excerpt_length( $length ) {
		return 9;
	}

	/**
	 * Reduce the length of custom excerpt (if specified manually).
	 *
	 * @since 1.0.0
	 *
	 * @param string $excerpt Current excerpt
	 *
	 * @return string
	 */
	public static function trim_excerpt( $excerpt ) {
		if ( false === strpos( $excerpt, '...' ) && str_word_count( $excerpt ) > 9 ) {
			$excerpt = wp_trim_words( $excerpt, 9, ' ...' );
		}

		return $excerpt;
	}
}