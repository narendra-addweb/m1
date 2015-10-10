<?php
/**
 * Search form template
 *
 * @package Appica
 */
?>
<form role="search" method="get" class="search-box-static" action="<?php echo home_url( '/' ); ?>">
	<input type="text" name="s" class="search-field" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'appica' ); ?>"
	       value="<?php the_search_query(); ?>">
	<input type="hidden" name="post_type" value="post">
	<button type="submit" class="search-btn"><i class="search-icon"></i></button>
</form>