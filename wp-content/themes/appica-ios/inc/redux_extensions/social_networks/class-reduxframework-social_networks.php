<?php
/**
 * Redux custom field, called "social_networks".
 *
 * This field allow user to setup his social networks list.
 *
 * @package     Appica
 * @subpackage  Redux
 * @author      8guild
 * @version     1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Do not duplicate
if ( ! class_exists( 'ReduxFramework_social_networks' ) ) :

	/**
	 * Custom field "social_networks" for Redux Framework
	 *
	 * @since 1.0.0
	 */
	class ReduxFramework_social_networks {
		/**
		 * Instance of Redux Framework Class
		 * @var ReduxFramework
		 */
		public $parent;
		/**
		 * Field attributes
		 * @var array
		 */
		public $field;
		/**
		 * Field value
		 * @var array|string
		 */
		public $value;

		/**
		 * Field Constructor.
		 *
		 * @since       1.0.0
		 * @access      public
		 *
		 * @param array  $field
		 * @param string $value
		 * @param array  $parent
		 */
		public function __construct( $field = array(), $value = '', $parent ) {
			$this->parent = $parent;
			$this->field  = $field;
			$this->value  = $value;
			if ( empty( $this->extension_dir ) ) {
				$this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
				$this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
			}
		}

		/**
		 * Field Render Function.
		 *
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function render() {

			$networks = $this->get_social_networks_list();
			$socials  = $this->process_social_networks( $this->value );

			// Set default args for this field to avoid bad indexes. Change this to anything you use.
			$defaults    = array();
			$this->field = wp_parse_args( $this->field, $defaults );

			if ( ! empty( $socials ) ) {
				$this->render_networks_list( $networks, $socials );
			} else {
				$this->render_empty_list( $networks );
			}
		}

		/**
		 * Enqueue Function.
		 *
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function enqueue() {
			wp_enqueue_script( 'pios-social-networks-fields', $this->extension_url . 'social_networks.js', array( 'jquery' ), null, true );
		}

		/**
		 * Get social networks list.
		 *
		 * @return array
		 */
		private function get_social_networks_list() {
			$ini      = wp_normalize_path( trailingslashit( __DIR__ ) . 'social-networks.ini' );
			$networks = parse_ini_file( $ini, true );

			ksort( $networks );

			return $networks;
		}

		/**
		 * Convert input array of user social networks to more suitable format.
		 *
		 * Process networks before render, because of Redux does not support sanitize field before save to DB.
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
		private function process_social_networks( $socials ) {
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

				$result[ $network ] = esc_url( $url );

			}, $socials['networks'], $socials['urls'] );

			return $result;
		}

		private function render_empty_list( $networks ) {
			$select_name = $this->field['name'] . $this->field['name_suffix'] . '[networks][]';
			$input_name  = $this->field['name'] . $this->field['name_suffix'] . '[urls][]';
			?>
			<div class="pios-social-networks-wrap">
				<div class="pios-social-group">
					<select name="<?php echo $select_name; ?>" class="pios-social-network">
						<?php
						foreach ( $networks as $network => $data ) :
							printf( '<option value="%1$s">%2$s</option>', $network, $data['name'] );
						endforeach;
						unset( $network, $data );
						?>
					</select>
					<input type="text" name="<?php echo $input_name; ?>" class="pios-social-url"
					       placeholder="<?php _e( 'Profile URL', 'appica' ); ?>">
				</div>
			</div>
			<button type="button" class="button button-primary pios-add-social-network"><?php _e( 'Add one more social network', 'appica' ); ?></button>
			<?php
		}

		private function render_networks_list( $networks, $socials ) {
			$select_name = $this->field['name'] . $this->field['name_suffix'] . '[networks][]';
			$input_name  = $this->field['name'] . $this->field['name_suffix'] . '[urls][]';
			?>
			<div class="pios-social-networks-wrap">
			<?php foreach ( (array) $socials as $user_network => $url ) : ?>

				<div class="pios-social-group">
					<select name="<?php echo $select_name; ?>" class="pios-social-network">
						<?php
						// Check, if this network was selected
						foreach ( $networks as $network => $data ) :
							$selected = ( $network === $user_network ) ? 'selected' : '';
							printf( '<option value="%1$s" %3$s>%2$s</option>', $network, $data['name'], $selected );
						endforeach;
						unset( $network, $data );
						?>
					</select>
					<input type="text" name="<?php echo $input_name; ?>" class="pios-social-url"
					       placeholder="<?php _e( 'Profile URL', 'domino' ); ?>" value="<?php echo $url; ?>">
				</div>

			<?php endforeach; ?>
			</div>
			<button type="button" class="button button-primary pios-add-social-network"><?php _e( 'Add one more social network', 'appica' ); ?></button>
			<?php
		}
	}

endif;