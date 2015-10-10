<?php
/**
 * Shortcode "Contacts" output
 *
 * Mapped params are in {@path appica-core/inc/vc-map.php} {@see $appica_contacts}
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

// this shortcode use $content

$a = shortcode_atts( array(
	'title'       => '',
	'subtitle'    => '',
	'extra_class' => '',
	'location'    => '',
	'is_zoom'     => 'disable', // enable | disable
	'zoom'        => 14,
	'is_scroll'   => 'disable', // enable | disable
	'marker'      => '',
	'gm_custom'   => '', // custom Google Map styles
), $atts );

// enqueue Google Maps API script
wp_enqueue_script( 'appica-google-maps' );

$c_class   = Appica_Helpers::get_class_set( array( 'fw-container', $a['extra_class'] ) );
$title     = ( '' === $a['title'] ) ? '' : esc_html( $a['title'] );
$subtitle  = ( '' === $a['subtitle'] ) ? '' : esc_html( $a['subtitle'] );
$location  = ( '' === $a['location'] ) ? '' : esc_html( $a['location'] );
$is_zoom   = ( 'enable' === $a['is_zoom'] );
$zoom      = is_numeric( $a['zoom'] ) ? absint( $a['zoom'] ) : 14;
$is_scroll = ( 'enable' === $a['is_scroll'] );
$custom    = ( '' === $a['gm_custom'] ) ? '' : urldecode( base64_decode( $a['gm_custom'] ) );
$marker    = ( '' === $a['marker'] ) ? '' : wp_get_attachment_image_src( absint( $a['marker'] ), 'full' );
$content   = do_shortcode( shortcode_unautop( $content ) );

// Prepare data attributes
$attributes = array(
	( '' === $title ) ? '' : "data-title=\"{$title}\"",
	( '' === $location ) ? '' : "data-location=\"{$location}\"",
	( '' === $marker ) ? '' : "data-icon=\"{$marker[0]}\"",
	( $is_zoom ) ? 'data-is-zoom="1"' : 'data-is-zoom="0"',
	"data-zoom=\"{$zoom}\"",
	( $is_scroll ) ? 'data-is-scroll="1"' : 'data-is-scroll="0"'
);

$data = implode( ' ', array_filter( $attributes ) );

$block_heading = '';
if ( '' !== $title || '' !== $subtitle ) {
	$block_heading .= '<div class="block-heading">';
	$block_heading .= ( '' === $title ) ? '' : "<h2>{$title}</h2>";
	$block_heading .= ( '' === $subtitle ) ? '' : "<span>{$subtitle}</span>";
	$block_heading .= '</div>';
}
?>
<div class="<?php echo $c_class; ?>">
	<div class="column w-60">
		<div class="google-map" <?php echo $data; ?>>
			<div id="map-canvas"></div>
		</div>
		<?php if ( '' !== $custom ) :

			echo "<script type='text/javascript'>\n";
			echo "var appica_gm_custom_style = {$custom}\n";
			echo "</script>\n";

		endif; ?>
	</div>
	<div class="column w-40">
		<div class="contact-info padding-top-2x padding-bottom">
			<?php echo $block_heading, $content; ?>
		</div>
	</div>
</div>