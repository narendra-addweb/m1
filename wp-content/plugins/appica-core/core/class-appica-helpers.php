<?php

/**
 * Helpers functions for Appica 2 theme
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */
class Appica_Helpers {

	/**
	 * Returns the URI for provided image. This function is designed primarily for images,
	 * but it is allowable for any other assets type you need: images, fonts, js, css etc.
	 *
	 * @param string $asset Relative path to image, e.g. "image/logo.png"
	 * @param bool   $echo Echoed or not the value. Default is TRUE.
	 *
	 * @return string
	 */
	public static function image_uri( $asset, $echo = true ) {
		$asset = ltrim( $asset, '/' );
		$uri   = plugins_url( $asset, __DIR__ );

		if ( $echo ) {
			echo $uri;
		}

		return $uri;
	}

	/**
	 * Get option value by it's name
	 *
	 * @param string $option  Option name
	 * @param mixed  $default Default option value
	 *
	 * @return mixed
	 */
	public static function get_option( $option, $default = '' ) {
		global $appica_options;

		$value = $default;

		if ( null !== $appica_options && is_array( $appica_options ) && array_key_exists( $option, $appica_options ) ) {
			$value = $appica_options[ $option ];
		}

		return $value;
	}

	/**
	 * Generate CSS rules
	 *
	 * @param array  $atts   Array of css rules where key is property name itself and value is a property value
	 * @param string $key    CSS property name
	 * @param string $return Return string or array
	 *
	 * @return string
	 */
	public static function generate_css( $atts = array(), $key = '', $return = 'string' ) {
		$css = array();

		foreach ( (array) array_filter( $atts ) as $a => $v ) {
			$a = ( '' === $key ) ? $a : $key;
			if ( is_array( $v ) ) {
				$css = array_merge( $css, self::generate_css( $v, $a, 'array' ) ); // combine two arrays
			} elseif ( is_scalar( $v ) ) {
				$css[] = "{$a}: {$v};";
			}
		}
		unset( $a, $v );

		return ( 'array' === $return ) ? $css : implode( ' ', $css );
	}

	/**
	 * Prepare CSS Rules
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $classes Set of class or tags, to which properties will be applied
	 * @param string|array $props   Array of css rules where key is property name itself and value is a property value
	 *
	 * @return string
	 */
	public static function generate_css_rules( $classes, $props ) {
		// Convert to string
		if ( is_array( $classes ) ) {
			$classes = implode( ', ', $classes );
		}

		// convert to string, too
		if ( is_array( $props ) ) {
			$props = self::generate_css( $props );
		}

		return sprintf( '%1$s {%2$s}', $classes, $props );
	}

	/**
	 * Get social networks list.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_social_networks_list() {
		$ini      = wp_normalize_path( plugin_dir_path( __DIR__ ) . '/assets/social-networks.ini' );
		$networks = parse_ini_file( $ini, true );

		/**
		 * Filter the all available social networks
		 *
		 * @since 1.0.0
		 *
		 * @param array $networks Array of networks
		 */
		return apply_filters( 'appica_core_social_networks_list', $networks );
	}

	/**
	 * Convert input array of user social networks to more suitable format.
	 *
	 * @param array $socials Expected multidimensional array with two keys [networks] and [urls], both contains equal number of elements.
	 * <code>
	 * [
	 *   networks => array( facebook, twitter ),
	 *   urls     => array( url1, url2 ),
	 * ];
	 * </code>
	 *
	 * @return array New format of input array
	 * <code>
	 * [
	 *   network  => url,
	 *   facebook => url,
	 *   twitter  => url
	 * ];
	 * </code>
	 */
	public static function process_social_networks( $socials ) {
		if ( empty( $socials ) ) {
			return array();
		}

		// Return empty if networks or url not provided.
		if ( empty( $socials['networks'] ) || empty( $socials['urls'] ) ) {
			return array();
		}

		$result = array();
		// Network is network slug / options group from social-networks.ini
		array_map( function ( $network, $url ) use ( &$result ) {

			// Just skip iteration if network or url not set
			if ( '' === $network || '' === $url ) {
				return;
			}

			$result[ $network ] = esc_url_raw( $url );

		}, $socials['networks'], $socials['urls'] );

		/**
		 * Filter the provided social networks
		 *
		 * @since 1.0.0
		 *
		 * @param array $output Array of processed options
		 * @param array $input  Array of user inputs
		 */
		return apply_filters( 'appica_core_process_social_networks', $result, $socials );
	}

	/**
	 * Prepare the class set
	 * E.g. [ 'my', 'cool', 'class' ] or 'my cool class' will be sanitized and converted to "my cool class"
	 *
	 * If $classes is a string - just explode. But if an array - we have to iterate through array to find "extra class".
	 * "Extra class" is a string, which contains whitespaces.
	 *
	 * @since 1.0.0
	 *
	 * @param array|string $classes
	 *
	 * @return string
	 */
	public static function get_class_set( $classes ) {
		$_classes = array();

		if ( '' === $classes ) {
			return '';
		} elseif ( is_array( $classes ) ) {
			// remove empty element before loop
			$classes = array_filter( $classes );
			// add classes to array if more than one per element
			foreach( $classes as $key => $class ) {
				$class = trim( $class );
				if ( false !== strpos( $class, ' ' ) ) {
					$_classes = array_merge( $_classes, explode( ' ', $class ) );
					unset( $classes[ $key ] );
				}

				continue;
			}
			unset( $key, $class );

			// combine two arrays
			$_classes = array_merge( $classes, $_classes );

		} else {
			$_classes = explode( ' ', $classes );
		}

		// do not duplicate
		$_classes = array_unique( $_classes );
		// sanitize
		$_classes = array_filter( $_classes );
		$_classes = array_map( 'sanitize_html_class', $_classes );
		$_classes = array_filter( $_classes );

		return implode( ' ', $_classes );
	}

	/**
	 * Return prepared image size
	 *
	 * @param string $size User specified image size
	 *
	 * @return array|string Built-in size keyword or array of width and height
	 */
	public static function get_image_size( $size = 'medium' ) {
		$_size = 'medium';

		/**
		 * @var array Allowed image sizes and aliases
		 */
		$allowed_image_sizes = array(
			'thumb',
			'thumbnail',
			'post-thumbnail',
			'medium',
			'large',
			'full'
		);

		if ( is_numeric( $size ) ) {
			// user specify single integer
			$size = (int) $size;
			$_size = array( $size, $size );
		} elseif ( false !== strpos( $size, 'x' ) ) {
			// user specify pair of width,height
			$_size = array_map( 'absint', explode( 'x', $size ) );
		} elseif ( in_array( $size, $allowed_image_sizes, true ) ) {
			// user specify one of the built-in sizes
			$_size = $size;
		}

		return $_size;
	}

	/**
	 * Return icons
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_icons() {
		$icons = array();
		$file  = plugin_dir_path( __DIR__ ) . 'assets/icons.php';

		if ( is_readable( $file ) ) {
			require( $file );
		}

		if ( ! is_array( $icons ) || 0 === count( $icons ) ) {
			$icons = array();
		}

		/**
		 * Filter the icons pack
		 *
		 * @since 1.0.0
		 *
		 * @param array $icons Icons array, where values is a class names
		 */
		return apply_filters( 'appica_core_icons', $icons );
	}

	/**
	 * Return terms, assigned for specified Post ID, depending on {@see $context} param: "slug" or "name".
	 *
	 * @param integer $post_id  Post ID.
	 * @param string  $taxonomy The taxonomy for which to retrieve terms.
	 * @param string  $context  [optional] Term slug or name, depending on what is required. Default is "slug".
	 *
	 * @return array <code>[ term, term, ... ]</code>
	 */
	public static function get_post_terms( $post_id, $taxonomy, $context = 'slug' ) {
		$terms      = array();
		$post_terms = wp_get_post_terms( $post_id, $taxonomy );
		// Catch the WP_Error or if any terms was not assigned to post
		if ( is_wp_error( $post_terms ) || 0 === count( $post_terms ) ) {
			return $terms;
		}

		foreach ( $post_terms as $term ) {
			$terms[] = $term->$context;
		}
		unset( $term, $post_terms );

		return $terms;
	}

	/**
	 * Check if post has Featured Video.
	 *
	 * Note, that video has priority over Featured Image.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function has_featured_video() {
		$meta = get_post_meta( get_the_ID(), '_appica_featured_video', true );

		return ( '' !== $meta );
	}

	/**
	 * Show Featured Video embed.
	 *
	 * @since 1.0.0
	 */
	public static function the_featured_video() {
		$url = get_post_meta( get_the_ID(), '_appica_featured_video', true );
		$embed = wp_oembed_get( $url );

		if ( false !== $embed ) {
			echo $embed;
		}
	}

	/**
	 * Prints entry meta data to post footer.
	 *
	 * Do not show comments counter, social share buttons and tags.
	 *
	 * @since 1.0.0
	 */
	public static function entry_footer_wo_social() {
		if ( 'post' !== get_post_type() ) {
			return;
		}

		/**
		 * Filter the categories view, displayed in single post entry footer.
		 *
		 * @since 1.0.0
		 * @link  http://codex.wordpress.org/Function_Reference/get_the_category_list
		 *
		 * @param string $categories Categories list, separated by comma
		 */
		$categories = apply_filters( 'appica_get_the_category_list', get_the_category_list( ', ' ) );

		$by_author = sprintf(
			_x( 'by %s', 'post author', 'appica' ),
			'<span class="author vcard"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);
		?><div class="post-meta space-top-2x">
			<div class="column">
				<span><?php _e( 'In', 'appica' ); ?> </span><?php echo $categories; ?>
				<?php echo $by_author; ?>
			</div>
			<div class="column text-right">
				<span><?php self::posted_on(); ?></span>
			</div>
		</div><?php
	}

	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @since 1.0.0
	 */
	public static function posted_on() {
		$time_string = '<time class="entry-date" datetime="%1$s">%2$s</time>';

		$date_c  = esc_attr( get_the_date( 'c' ) );
		$date    = esc_html( get_the_date() );

		$time_string = sprintf( $time_string, $date_c, $date );

		echo $time_string;
	}

	/**
	 * Sanitize settings meta boxes. Callback for array_map()
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $setting Single setting value
	 *
	 * @return int|null|string
	 */
	public static function sanitize_settings_meta_box( $setting ) {
		$result = null;

		if ( is_numeric( $setting ) ) {
			$result = absint( $setting );
		} else {
			$result = sanitize_text_field( $setting );
		}

		return $result;
	}

	/**
	 * Return compiled css for <head>
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_head_css() {
		$css = array();

		$primary_color = Appica_Helpers::get_option( 'color_primary', '#007aff' );
		$success_color = Appica_Helpers::get_option( 'color_success', '#4cd964' );
		$info_color    = Appica_Helpers::get_option( 'color_info', '#5ac8fa' );
		$warning_color = Appica_Helpers::get_option( 'color_warning', '#ffcc00' );
		$danger_color  = Appica_Helpers::get_option( 'color_danger', '#ff2d55' );

		// Button color
		$css[] = Appica_Helpers::generate_css_rules( '.appica-button-color .primary', array( 'background-color' => $primary_color ) );
		$css[] = Appica_Helpers::generate_css_rules( '.appica-button-color .success', array( 'background-color' => $success_color ) );
		$css[] = Appica_Helpers::generate_css_rules( '.appica-button-color .info', array( 'background-color' => $info_color ) );
		$css[] = Appica_Helpers::generate_css_rules( '.appica-button-color .warning', array( 'background-color' => $warning_color ) );
		$css[] = Appica_Helpers::generate_css_rules( '.appica-button-color .danger', array( 'background-color' => $danger_color ) );

		// Badge color
		$css[] = Appica_Helpers::generate_css_rules( '.appica-badge-color .primary', array( 'background-color' => $primary_color ) );
		$css[] = Appica_Helpers::generate_css_rules( '.appica-badge-color .success', array( 'background-color' => $success_color ) );
		$css[] = Appica_Helpers::generate_css_rules( '.appica-badge-color .info', array( 'background-color' => $info_color ) );
		$css[] = Appica_Helpers::generate_css_rules( '.appica-badge-color .warning', array( 'background-color' => $warning_color ) );
		$css[] = Appica_Helpers::generate_css_rules( '.appica-badge-color .danger', array( 'background-color' => $danger_color ) );

		return implode( PHP_EOL, $css );
	}
}