<?php
/**
 * Shortcode "Fancy text" output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

$a = shortcode_atts( array(
	'extra_class'     => '',
	'first_title'     => '',
	'first_subtitle'  => '',
	'first_percent'   => '',
	'first_color'     => '',
	'second_title'    => '',
	'second_subtitle' => '',
	'second_percent'  => '',
	'second_color'    => '',
	'third_title'     => '',
	'third_subtitle'  => '',
	'third_percent'   => '',
	'third_color'     => ''
), $atts );

$charts = array(
	array(
		'title'    => esc_html( $a['first_title'] ),
		'subtitle' => esc_html( $a['first_subtitle'] ),
		'percent'  => (int) trim( $a['first_percent'], '.,%' ),
		'color'    => $a['first_color']
	),
	array(
		'title'    => esc_html( $a['second_title'] ),
		'subtitle' => esc_html( $a['second_subtitle'] ),
		'percent'  => (int) trim( $a['second_percent'], '.,%' ),
		'color'    => $a['second_color']
	),
	array(
		'title'    => esc_html( $a['third_title'] ),
		'subtitle' => esc_html( $a['third_subtitle'] ),
		'percent'  => (int) trim( $a['third_percent'], '.,%' ),
		'color'    => $a['third_color']
	)
);

$classes = Appica_Helpers::get_class_set( array( 'bar-charts', $a['extra_class'] ) );

?><div class="<?php echo $classes; ?>">
	<div class="grid"></div>
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="row"><?php
				foreach( $charts as $i => $chart ) :
					if ( 0 === $chart['percent'] ) {
						continue;
					}
					// 1 - percent, 2 - title, 3 - subtitle, 4 - color
					printf(
						'<div class="col-lg-%5$d col-xs-4"><div class="chart" data-percentage="%1$d"><span class="bar" %4$s></span>%2$s%3$s</div></div>',
						$chart['percent'],
						( '' === $chart['title'] ) ? '' : "<h3>{$chart['title']}</h3>",
						( '' === $chart['subtitle'] ) ? '' : "<p>{$chart['subtitle']}</p>",
						( '' === $chart['color'] ) ? '' : "style=\"background-color: {$chart['color']};\"",
						( 0 === $i ) ? 3 : 4
					);
				endforeach;
			?></div>
		</div>
	</div>
</div>