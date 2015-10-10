<?php
/**
 * The template part for displaying single post entry.
 *
 * @package Appica2
 */
?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	the_title( '<h1>', '</h1>' );

	the_content();

	wp_link_pages( array(
		'before'           => '<div class="paginate-links">',
		'after'            => '</div>',
		'nextpagelink'     => __( 'Next', 'appica' ),
		'previouspagelink' => __( 'Previous', 'appica' )
	) );

	appica_entry_footer();
	?>
</article>