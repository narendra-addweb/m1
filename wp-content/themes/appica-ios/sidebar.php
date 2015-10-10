<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Appica2
 */

if ( ! is_active_sidebar( 'sidebar-blog' ) ) {
	return;
}
?><div class="widget-area sidebar space-bottom-3x" role="complementary">
	<?php dynamic_sidebar( 'sidebar-blog' ); ?>
</div>

