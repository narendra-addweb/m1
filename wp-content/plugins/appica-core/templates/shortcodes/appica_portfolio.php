<?php
/**
 * Shortcode "Portfolio" output
 *
 * @since      1.3.0
 *
 * @author     8guild
 * @package    Appica
 * @subpackage Core
 */

$a = shortcode_atts( array(
	'num'         => 'all',
	'is_filters'  => 'enable', // enable | disable
	'lm_text'     => __( 'Load More Portfolio', 'appica' ),
	'extra_class' => ''
), $atts );

$is_all     = ( 'all' === strtolower( $a['num'] ) );
$num        = ( $is_all ) ? -1 : absint( $a['num'] );
$num        = ( is_numeric( $num ) ) ? $num : -1;
$is_filters = ( 'enable' === $a['is_filters'] );
$lm_text    = esc_html( $a['lm_text'] );
$extr_class = Appica_Helpers::get_class_set( $a['extra_class'] );

// Wrapper
if ( '' !== $extr_class ) {
	echo "<div class=\"{$extr_class}\">";
}

// Filters
if ( $is_filters ) {
	$filters    = '';
	$categories = get_terms( 'appica_portfolio_category', array( 'hierarchical' => false ) );
	if ( ! is_wp_error( $categories ) && count( (array) $categories ) > 0 ) {
		$filters .= '<ul class="nav-filters space-bottom">';
		$filters .= sprintf(
			'<li class="active"><a data-filter="*" href="#">%s</a></li>', __( 'Show All', 'appica' )
		);
		foreach ( (array) $categories as $category ) {
			$filters .= sprintf(
				'<li><a data-filter=".%1$s" href="#">%2$s</a></li>', $category->slug, $category->name
			);
		}
		$filters .= '</ul>';
		unset( $category, $categories );
	}

	// Display filters
	echo $filters;
}

// Loop
$query = new WP_Query( array(
	'post_type'           => 'appica_portfolio',
	'post_status'         => 'publish',
	'posts_per_page'      => $num,
	'ignore_sticky_posts' => true
) );

if ( $query->have_posts() ) : ?>

	<div class="portfolio-grid filter-grid">
		<div class="grid-sizer"></div>
		<div class="gutter-sizer"></div>

		<?php
		$posts = array();

		while( $query->have_posts() ) :
			$query->the_post();

			$posts[] = get_the_ID();
			get_template_part( 'content', 'portfolio' );

		endwhile;

		// list of loaded posts
		$posts = implode( ',', $posts );
		?>

	</div>

	<?php if ( false === $is_all ) : ?>
		<div class="text-center">
			<a href="#" class="btn btn-ghost btn-primary icon-left appica-load-more-portfolio" data-posts="<?php echo $posts; ?>">
				<i class="flaticon-arrow408"></i>
				<?php echo $lm_text; ?>
			</a>
		</div>
	<?php endif; ?>

<?php else:
	get_template_part( 'content', 'none' );
endif;

wp_reset_postdata();
unset( $query );

// .end Wrapper
if ( '' !== $extr_class ) {
	echo '</div>';
}