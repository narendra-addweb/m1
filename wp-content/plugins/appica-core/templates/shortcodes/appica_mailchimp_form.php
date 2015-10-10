<?php
/**
 * Shortcode "MailChimp Form" output
 *
 * @since      1.2.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

$a = shortcode_atts( array(
	'title'       => '',
	'action'      => '',
	'label_name'  => '',
	'label_email' => '',
	'label_btn'   => '',
	'orientation' => ''
), $atts );

$title       = ( '' === $a['title'] ) ? '' : esc_html( $a['title'] );
$action      = ( '' === $a['action'] ) ? '' : esc_url_raw( htmlspecialchars_decode( $a['action'] ) );
$orientation = esc_attr( $a['orientation'] );
$label_name  = ( '' === $a['label_name'] ) ? __( 'Name', 'appica' ) : esc_html( $a['label_name'] );
$label_email = ( '' === $a['label_email'] ) ? __( 'Email', 'appica' ) : esc_html( $a['label_email'] );
$label_btn   = ( '' === $a['label_btn'] ) ? __( 'Subscribe', 'appica' ) : esc_html( $a['label_btn'] );

// Build MC AntiSPAM
if ( '' === $action ) {
	$appica_options = (array) get_option( 'appica_options', array() );

	if ( count( $appica_options ) > 0
	     && array_key_exists( 'socials_mailchimp', $appica_options )
	     && '' !== $appica_options['socials_mailchimp']
	) {
		$action = $appica_options['socials_mailchimp'];
	}
}

// If action not set, just exit
if ( '' === $action ) {
	return;
}

$request_uri = parse_url( htmlspecialchars_decode( $action ), PHP_URL_QUERY );
parse_str( $request_uri , $c );
$mc_antispam = sprintf( 'b_%1$s_%2$s', $c['u'], $c['id'] );

unset( $request_uri, $c );

// Title
if ( '' !== $title ) {
	$title = sprintf( '<h3 class="space-bottom-2x">%s</h3>', $title );
}

?><form method="post" action="<?php echo $action; ?>" autocomplete="off" target="_blank">
	<?php echo $title; ?>
	<input type="hidden" name="<?php echo $mc_antispam; ?>" tabindex="-1" value="">

	<?php if ( 'horizontal' === $orientation ) : ?>
		<div class="col-sm-6">
			<div class="form-group">
				<label for="si-name" class="sr-only"><?php echo $label_name; ?></label>
				<input type="text" class="form-control" name="NAME" id="si-name" placeholder="<?php echo $label_name; ?>" required>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label for="si_email" class="sr-only"><?php echo $label_email; ?></label>
				<input type="email" class="form-control" name="EMAIL" id="si_email" placeholder="<?php echo $label_email; ?>" required>
			</div>
		</div>
		<div class="col-sm-12 clearfix">
			<button type="submit" class="btn btn-primary btn-ghost pull-right"><?php echo $label_btn; ?></button>
		</div>
	<?php else: ?>
		<div class="form-group">
			<label for="si-name" class="sr-only"><?php echo $label_name; ?></label>
			<input type="text" class="form-control" name="NAME" id="si-name" placeholder="<?php echo $label_name; ?>" required>
		</div>
		<div class="form-group">
			<label for="si_email" class="sr-only"><?php echo $label_email; ?></label>
			<input type="email" class="form-control" name="EMAIL" id="si_email" placeholder="<?php echo $label_email; ?>" required>
		</div>
		<div class="clearfix">
			<button type="submit" class="btn btn-primary btn-ghost pull-right"><?php echo $label_btn; ?></button>
		</div>
	<?php endif; ?>
</form>