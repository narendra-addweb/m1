<?php
/**
 * Shortcode "Pricing Plans" output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

$a = shortcode_atts( array(
	'is_switcher' => 'yes', // yes | no
	'title'       => '',
	'subtitle'    => '',
	'first_name'  => '',
	'second_name' => '',
	'extra_class' => ''
), $atts );

$extra       = Appica_Helpers::get_class_set( $a['extra_class'] );
$tax         = 'appica_pricings_terms';
$is_switcher = ( 'yes' === $a['is_switcher'] );

if ( '' !== $extra ) {
	printf( '<div class="%s">', $extra );
}

/*
 * Plan switcher
 */
if ( $is_switcher ) {
	$title    = esc_html( $a['title'] );
	$subtitle = esc_html( $a['subtitle'] );
	$terms    = get_terms( $tax, array(
		'hierarchical' => false,
		'hide_empty'   => false,
	) );

	$plan_switcher = '';
	if ( ! is_wp_error( $terms ) && is_array( $terms ) && 0 !== count( $terms ) ) {
		$plan_switcher .= '<div class="pricing-plan-switcher text-right">';
		$plan_switcher .= ( '' === $title ) ? '' : "<div class=\"label\">{$title}</div>";

		$switcher_tpl = '<label class="radio-inline radio-alt size-lg"><input type="radio" name="plan" id="plan-%1$s" data-term="%1$s" %3$s> %2$s</label>';
		foreach( (array) $terms as $k => $term ) {
			$checked = ( 0 === $k ) ? 'checked' : '';
			$plan_switcher .= sprintf( $switcher_tpl, $term->slug, $term->name, $checked );
		}
		unset( $term, $k, $checked, $switcher_tpl );

		$plan_switcher .= ( '' === $subtitle ) ? '' : "<span>{$subtitle}</span>";
		$plan_switcher .= '</div>';
	}

	echo $plan_switcher;

	unset( $title, $subtitle, $terms, $plan_switcher );
}

/*
 * WP_Query
 */
$query = new WP_Query( array(
	'post_type'           => 'appica_pricings',
	'post_status'         => 'publish',
	'posts_per_page'      => -1,
	'ignore_sticky_posts' => true
) );

if ( $query->have_posts() ) :
	echo '<div class="row space-top-3x">';

	while ( $query->have_posts() ) :
		$query->the_post();

		$post_id = get_the_ID();

		$icon   = get_post_meta( $post_id, '_appica_pricings_icon', true );
		$button = get_post_meta( $post_id, '_appica_pricings_button', true );
		$plan   = get_post_meta( $post_id, '_appica_pricings_price', true );

		echo '<div class="col-sm-4 pricing-plan">';

		if ( '' !== $icon ) {
			echo "<div class=\"icon\"><i class=\"{$icon}\"></i></div>";
		}

		echo '<div class="pricing-plan-title">';
		the_title( '<span class="name">', '</span>' );

		/*
		 * Data set of prices for JS
		 *
		 * Also, get first term and price to show something on page load
		 */
		$_data_set    = '';
		$_first_price = '';
		$_first_term  = '';
		if ( '' !== $plan && array_key_exists( 'terms', $plan ) && 0 !== count( $plan['terms'] ) ) {
			$_first_price = reset( $plan['terms'] );
			$_first_term  = '/ ' . key( $plan['terms'] );

			$_data = array();
			foreach( (array) $plan['terms'] as $term => $price ) {
				$_data[] = sprintf( 'data-%1$s="%2$s"', $term, $price );
			}
			unset( $term, $price );

			$_data_set = implode( ' ', (array) $_data );
			unset( $_data );
		}

		printf( '<i class="price" %2$s>%1$s</i>', $_first_price, $_data_set );
		printf( '<span class="period">%s</span>', $_first_term );

		echo '</div>'; // .pricing-plan-title

		if ( '' !== $button ) {
			$url     = ( '' === $button['url'] ) ? '#' : esc_url( $button['url'] );
			$text    = ( '' === $button['text'] ) ? '' : esc_html( $button['text'] );
			$target  = ( '' === $button['target'] ) ? '' : sprintf( 'target="%s"', $button['target'] );
			$classes = Appica_Helpers::get_class_set( array(
				'btn',
				'btn-block',
				'btn-plus',
				( 'yes' === $button['active'] ) ? 'btn-light' : ''
			) );

			printf( '<a href="%1$s" class="%3$s" %4$s>%2$s</a>', $url, $text, $classes, $target );
			unset( $url, $text, $target, $classes );
		}

		echo '<div class="pricing-plan-description">';
		the_content();
		echo '</div></div>'; // end .pricing-plan-description && .pricing-plan

		unset( $icon, $button, $plans );
	endwhile;

	echo '</div>';
endif;
wp_reset_postdata();

if ( '' !== $extra ) {
	echo '</div>';
}