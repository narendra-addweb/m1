<?php
/**
 * Shortcode "Gadgets Slideshow" output
 *
 * @since      1.0.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

$a = shortcode_atts( array(
	'title'       => '',
	'subtitle'    => '',
	'is_sl'       => 'disable', // enable | disable
	'sl_interval' => 3000, // slideshow interval
	'color'       => 'gold',
	'extra_class' => ''
), $atts );

$title       = esc_html( $a['title'] );
$subtitle    = esc_html( $a['subtitle'] );
$is_sl       = ( 'enable' === $a['is_sl'] ) ? 'true' : 'false';
$sl_interval = absint( $a['sl_interval'] );
$color       = esc_attr( $a['color'] );
$classes     = Appica_Helpers::get_class_set( array( 'feature-tabs', $a['extra_class'] ) );

$query = new WP_Query( array(
	'post_type'           => 'appica_slideshow',
	'post_status'         => 'publish',
	'posts_per_page'      => -1,
	'ignore_sticky_posts' => true
) );

$visible_when_stack = '';
$hidden_when_stack  = '';

// if title or subtitle not empty
if ( '' !== $title || '' !== $subtitle ) {
	$title    = ( '' === $title ) ? '' : "<h2>{$title}</h2>";
	$subtitle = ( '' === $subtitle ) ? '' : "<span>{$subtitle}</span>";

	$tpl = '<div class="block-heading %1$s">%2$s%3$s</div>';

	$visible_when_stack = sprintf( $tpl, 'visible-when-stack', $title, $subtitle );
	$hidden_when_stack  = sprintf( $tpl, 'hidden-when-stack', $title, $subtitle );

	unset( $title, $subtitle, $tpl );
}

$entries = array();
if ( $query->have_posts() ) :

	// Defaults media values for avoiding php warnings
	$media_defaults = array(
		'phone'  => 0,
		'tablet' => 0
	);

	while( $query->have_posts() ) : $query->the_post();

		$post_id = get_the_ID();

		$media = get_post_meta( $post_id, '_appica_slideshow_media', true );
		$media = wp_parse_args( (array) $media, $media_defaults );

		$entries['posts'][ $post_id ] = array(
			'title'      => get_the_title(),
			'excerpt'    => get_the_excerpt(),
			'transition' => get_post_meta( $post_id, '_appica_slideshow_transition', true )
		);

		$entries['icons'][ $post_id ]   = get_post_meta( $post_id, '_appica_slideshow_icon', true );
		$entries['phones'][ $post_id ]  = $media['phone'];
		$entries['tablets'][ $post_id ] = $media['tablet'];

		unset( $post_id, $media );
	endwhile;

	unset( $media_defaults );
endif;

wp_reset_postdata();
unset( $query );

/*
 * Start output
 */
?><div class="<?php echo $classes; ?>">

	<?php echo $visible_when_stack; ?>

	<div class="clearfix">
		<div class="devices">
			<div class="tablet">
				<img src="<?php Appica_Helpers::image_uri( "assets/img/ipad-{$color}.png" ); ?>" alt="iPad">
				<div class="mask">
					<ul class="screens"><?php
						/*
						 * Tablets
						 */
						$tablets = array_key_exists( 'tablets', $entries ) ? (array) $entries['tablets'] : array();
						$first   = null;
						if ( count( $tablets ) > 0 ) {
							reset( $tablets );
							$first = key( $tablets );
						}

						foreach ( $tablets as $post_id => $tablet ) :
							$tpl = '<li id="ts%1$s">%2$s</li>';
							if ( $first === $post_id ) {
								// add class .active.in for first element
								$tpl = '<li class="active in" id="ts%1$s">%2$s</li>';
							}

							printf( $tpl, $post_id, wp_get_attachment_image( $tablet, 'full' ) );
						endforeach;
						unset( $tablets, $tpl, $first, $post_id, $tablet );
					?></ul>
				</div>
			</div>
			<div class="phone">
				<img src="<?php Appica_Helpers::image_uri( "assets/img/iphone-{$color}.png" ); ?>" alt="iPhone">
				<div class="mask">
					<ul class="screens"><?php
						/*
						 * Phones
						 */
						$phones = array_key_exists( 'phones', $entries ) ? (array) $entries['phones'] : array();
						$first  = null;
						if ( count( $phones ) > 0 ) {
							reset( $phones );
							$first = key( $phones );
						}

						foreach( $phones as $post_id => $phone ) :
							$tpl = '<li id="ps%1$s">%2$s</li>';
							if ( $first === $post_id ) {
								$tpl = '<li class="active in" id="ps%1$s">%2$s</li>';
							}

							printf( $tpl, $post_id, wp_get_attachment_image( $phone, 'full' ) );
						endforeach;
						unset( $phones, $first, $tpl, $post_id, $phone );
					?></ul>
				</div>
			</div>
		</div>
		<div class="tabs text-center">
			<?php
			// Title
			echo $hidden_when_stack;

			/*
			 * Start .nav-tabs
			 */
			printf( '<ul class="nav-tabs" data-autoswitch="%1$s" data-interval="%2$s">', $is_sl, $sl_interval );

			$icons = array_key_exists( 'icons', $entries ) ? (array) $entries['icons'] : array();
			$first = null;
			if ( count( $icons ) > 0 ) {
				reset( $icons );
				$first = key( $icons );
			}

			foreach ( $icons as $post_id => $icon ) {
				// 1 - post_id, 2 - icon class
				$tpl = '<li><a href="#tab-%1$s" data-toggle="tab" data-tablet="#ts%1$s" data-phone="#ps%1$s" aria-expanded="false"><i class="%2$s"></i></a></li>';
				if ( $first === $post_id ) {
					$tpl = '<li class="active"><a href="#tab-%1$s" data-toggle="tab" data-tablet="#ts%1$s" data-phone="#ps%1$s" aria-expanded="false"><i class="%2$s"></i></a></li>';
				}

				printf( $tpl, $post_id, $icon );
			}
			unset( $icons, $first, $tpl, $post_id, $icon );

			echo '</ul>';

			/*
			 * Tabs content
			 */
			echo '<div class="tab-content">';

			$posts = array_key_exists( 'posts', $entries ) ? (array) $entries['posts'] : array();
			$first = null;
			if ( count( $posts ) > 0 ) {
				reset( $posts );
				$first = key( $posts );
			}

			foreach ( $posts as $post_id => $post ) {
				// 1 - post_id, 2 - title, 3 - excerpt, 4 - transition
				$tpl = '<div class="tab-pane transition fade %4$s" id="tab-%1$s">%2$s%3$s</div>';
				if ( $first === $post_id ) {
					$tpl = '<div class="tab-pane transition fade %4$s active in" id="tab-%1$s">%2$s%3$s</div>';
				}

				printf( $tpl, $post_id, "<h3>{$post['title']}</h3>", "<p>{$post['excerpt']}</p>", $post['transition'] );
			}
			unset( $posts, $first, $tpl, $posts_keys, $post_id, $post );

			echo '</div>'; // end .tab-content ?></div>
	</div>
</div>