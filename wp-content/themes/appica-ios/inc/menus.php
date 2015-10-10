<?php
/**
 * Appica Menus Walkers
 *
 * @author 8guild
 * @package Appica 2
 */

/**
 * Customize the anchor menu
 *
 * @since 1.0.0
 */
class Appica_Anchor_Menu_Walker extends Walker_Nav_Menu {
	/**
	 * Count number of menu items
	 * @var int
	 */
	private static $i = 0;
	/**
	 * Number of displayed items per column from options
	 * @var int
	 */
	private static $num;

	/**
	 * Modify the element output: columns here!
	 *
	 * @uses Walker_Nav_Menu::start_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		// Get number of elements
		if ( null === self::$num ) {
			self::$num = (int) appica_get_option( 'offcanvas_anchor_el_num', 6 );
		}
		// Check for columns
		if ( self::$i !== 0 && self::$i % self::$num === 0 ) {
			$output .= '</ul><ul>'; // columns!
		}
		self::$i++;

		// Render menu item
		parent::start_el( $output, $item, $depth, $args, $id );
	}
}

/**
 * Anchor menu
 *
 * As the anchor menu designed specifically for the Front Page,
 * all links starting with # will lead to the home page with anchor.
 *
 * Also add class .scroll or .scrollup to link for smooth scrolling.
 *
 * @param array  $atts The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
 * @param object $item The current menu item.
 *
 * @return array
 */
function appica_nav_menu_link_attributes( $atts, $item ) {
	// Applied only the anchored links (started with #)
	if ( 'custom' !== $item->type
	     || false === strpos( $atts['href'], '#' )
	     || '#' !== substr( $atts['href'], 0, 1 )
	) {
		return $atts;
	}

	$is_front = is_front_page();

	// If NOT front page, prepend anchor with link to home..
	if ( false === $is_front ) {
		// clean home link
		$anchor  = ( '#' === $atts['href'] ) ? '' : $atts['href'];
		$atts['href'] = sprintf( '%1$s%2$s', home_url( '/' ), $anchor );
	}

	// ..but if IS front page - add class .scroll or .scrollup to <a>
	if ( $is_front ) {
		$atts['class'] = ( '#home' === $atts['href'] || '#' === $atts['href'] ) ? 'scrollup' : 'scroll';
	}

	return $atts;
}

add_filter( 'nav_menu_link_attributes', 'appica_nav_menu_link_attributes', 10, 2 );