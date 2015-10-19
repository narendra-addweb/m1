<?php
/**
 * Redux custom field, called "icon".
 *
 * This field allow user to choose the icon from icons list.
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

if ( ! class_exists( 'ReduxFramework_icon' ) ) :

	class ReduxFramework_icon {
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
		public function __construct( $field = array(), $value = '', $parent = null ) {
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
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function render() {
			$field_name = $this->field['name'] . $this->field['name_suffix'];
			$pack = esc_attr( $this->field['pack'] );

			$displayPreview = 'none';
			$displayRemove = 'none';
			$preview = '';
			if ( ! empty( $this->value ) ) {
				$displayPreview = 'block';
				$displayRemove = 'inline';
				$preview = "<i class=\"{$this->value}\"></i>";
			}
			?>
			<div class="pios-icon-wrapper">
				<input type="hidden" name="<?php echo $field_name; ?>" class="pios-icon-val" value="<?php echo $this->value; ?>">
				<div class="pios-icon-preview" style="display: <?php echo $displayPreview; ?>;"><?php echo $preview; ?></div>
				<button type="button" class="button pios-icon-select"
				        data-pack="<?php echo $pack; ?>"><?php _e( 'Select', 'appica' ); ?></button>
				<button type="button" class="button pios-icon-remove"
				        style="display: <?php echo $displayRemove; ?>;"><?php _e( 'Remove', 'appica' ); ?></button>
			</div>
			<?php
		}
	}

endif;