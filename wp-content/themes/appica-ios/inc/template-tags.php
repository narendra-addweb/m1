<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Appica
 * @author 8guild
 */

if ( ! function_exists( 'the_archive_title' ) ) :
	/**
	 * Shim for `the_archive_title()`.
	 *
	 * Display the archive title based on the queried object.
	 *
	 * @todo Remove this function when WordPress 4.3 is released.
	 *
	 * @param string $before Optional. Content to prepend to the title. Default empty.
	 * @param string $after  Optional. Content to append to the title. Default empty.
	 */
	function the_archive_title( $before = '', $after = '' ) {
		if ( is_category() ) {
			$title = sprintf( __( 'Category: %s', 'appica' ), single_cat_title( '', false ) );
		} elseif ( is_tag() ) {
			$title = sprintf( __( 'Tag: %s', 'appica' ), single_tag_title( '', false ) );
		} elseif ( is_author() ) {
			$title = sprintf( __( 'Author: %s', 'appica' ), '<span class="vcard">' . get_the_author() . '</span>' );
		} elseif ( is_year() ) {
			$title = sprintf( __( 'Year: %s', 'appica' ), get_the_date( _x( 'Y', 'yearly archives date format', 'appica' ) ) );
		} elseif ( is_month() ) {
			$title = sprintf( __( 'Month: %s', 'appica' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'appica' ) ) );
		} elseif ( is_day() ) {
			$title = sprintf( __( 'Day: %s', 'appica' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'appica' ) ) );
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title = _x( 'Asides', 'post format archive title', 'appica' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = _x( 'Galleries', 'post format archive title', 'appica' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = _x( 'Images', 'post format archive title', 'appica' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = _x( 'Videos', 'post format archive title', 'appica' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = _x( 'Quotes', 'post format archive title', 'appica' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = _x( 'Links', 'post format archive title', 'appica' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = _x( 'Statuses', 'post format archive title', 'appica' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = _x( 'Audio', 'post format archive title', 'appica' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = _x( 'Chats', 'post format archive title', 'appica' );
			}
		} elseif ( is_post_type_archive() ) {
			$title = sprintf( __( 'Archives: %s', 'appica' ), post_type_archive_title( '', false ) );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
			$title = sprintf( __( '%1$s: %2$s', 'appica' ), $tax->labels->singular_name, single_term_title( '', false ) );
		} else {
			$title = __( 'Archives', 'appica' );
		}

		/**
		 * Filter the archive title.
		 *
		 * @param string $title Archive title to be displayed.
		 */
		$title = apply_filters( 'get_the_archive_title', $title );

		if ( ! empty( $title ) ) {
			echo $before . $title . $after;
		}
	}

endif;

if ( ! function_exists( 'the_archive_description' ) ) :
	/**
	 * Shim for `the_archive_description()`.
	 *
	 * Display category, tag, or term description.
	 *
	 * @todo Remove this function when WordPress 4.3 is released.
	 *
	 * @param string $before Optional. Content to prepend to the description. Default empty.
	 * @param string $after  Optional. Content to append to the description. Default empty.
	 */
	function the_archive_description( $before = '', $after = '' ) {
		$description = apply_filters( 'get_the_archive_description', term_description() );

		if ( ! empty( $description ) ) {
			/**
			 * Filter the archive description.
			 *
			 * @see term_description()
			 *
			 * @param string $description Archive description to be displayed.
			 */
			echo $before . $description . $after;
		}
	}

endif;

/**
 * Check if Google Fonts is used
 *
 * @since 1.0.0
 *
 * @return bool
 */
function appica_is_google_font() {
	return ( true === (bool) appica_get_option( 'typography_is_google', false ) );
}

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function appica_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'appica_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'appica_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so appica_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so appica_categorized_blog should return false.
		return false;
	}
}

/**
 * Prints HTML to posts footer with meta information for the categories, tags, published date,
 * social sharings and comments. Allowed only for posts.
 *
 * This template tag call inside The Loop, so, you can use other tags.
 *
 * @since 1.0.0
 */
function appica_entry_footer() {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	/**
	 * Filter the categories view, displayed in single post entry footer.
	 *
	 * @since 1.0.0
	 * @link http://codex.wordpress.org/Function_Reference/get_the_category_list
	 *
	 * @param string $categories Categories list, separated by comma
	 */
	$categories = apply_filters( 'appica_get_the_category_list', get_the_category_list( ', ' ) );
	/**
	 * Filter the tags view, displayed in single post entry footer.
	 *
	 * @since 1.0.0
	 * @link http://codex.wordpress.org/Function_Reference/get_the_tags
	 *
	 * @param array $tags Array of tags
	 */
	$tags = apply_filters( 'appica_get_the_tags', get_the_tags() );

	$by_author = sprintf(
		_x( 'by %s', 'post author', 'appica' ),
		'<span class="author vcard"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);
	?>
	<div class="post-meta space-top-2x">
		<div class="column"><?php appica_posted_on(); ?></div>
		<div class="column">
			<div class="social-buttons text-right">
				<a href="#comments" class="comment-count scroll" data-offset-top="125"><i
						class="flaticon-chat26"></i><?php comments_number( '0', '1', '%' ) ?></a>
				<?php appica_social_share_buttons(); ?>
			</div>
		</div>
	</div>
	<div class="post-meta space-top space-bottom">
		<?php if ( ( $categories && appica_categorized_blog() ) ) : ?>
			<div class="column">
				<span><?php _e( 'In', 'appica' ); ?> </span><?php echo $categories; ?>
				<?php echo $by_author; ?>
			</div>
		<?php endif; ?>
		<?php if ( $tags ) : ?>
			<div class="column text-right">
				<?php echo $tags; ?>
			</div>
		<?php endif; ?>
	</div><?php
}


/**
 * Prints entry meta data to post footer.
 *
 * Similar to {@see appica_entry_footer}, excepts comments counter, social share buttons and tags are not displayed.
 * This template tag call inside The Loop, so, you can use other tags.
 *
 * @since 1.0.0
 */
function appica_entry_footer_wo_social() {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	/**
	 * Filter the categories view, displayed in single post entry footer.
	 *
	 * @since 1.0.0
	 * @link  http://codex.wordpress.org/Function_Reference/get_the_category_list
	 *
	 * @param string $categories Categories list, separated by comma
	 */
	$categories = apply_filters( 'appica_get_the_category_list', get_the_category_list( ', ' ) );

	$by_author = sprintf(
		_x( 'by %s', 'post author', 'appica' ),
		'<span class="author vcard"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	/*
	 * Edge case: no title.
	 * In that case wrap a date into permalink.
	 */
	$post_title  = get_the_title();
	$is_no_title = ( '' === $post_title );
	?>
	<div class="post-meta space-top-2x">
		<div class="column">
			<span><?php _e( 'In', 'appica' ); ?> </span><?php echo $categories; ?>
			<?php echo $by_author; ?>
		</div>
		<div class="column text-right">
			<?php if ( $is_no_title ) : ?>
				<a href="<?php the_permalink()?>">
					<span><?php appica_posted_on(); ?></span>
				</a>
			<?php else: ?>
				<span><?php appica_posted_on(); ?></span>
			<?php endif; ?>

		</div>
	</div><?php
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since 1.0.0
 */
function appica_posted_on() {
	$time_string = '<time class="entry-date" datetime="%1$s">%2$s</time>';

	$date_c  = esc_attr( get_the_date( 'c' ) );
	$date    = esc_html( get_the_date() );
	$mdate_c = esc_attr( get_the_modified_date( 'c' ) );
	$mdate   = esc_html( get_the_modified_date() );

	$time_string = sprintf( $time_string, $date_c, $date, $mdate_c, $mdate );

	echo $time_string;
}

/**
 * Render the share button in Single Post.
 *
 * This function is used in Loop.
 *
 * @since 1.0.0
 */
function appica_social_share_buttons() {
	// Prepare the content for share
	$text = esc_attr( get_the_title() );
	$url = esc_url( get_the_permalink() );

	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id() );
	$img = $thumb[0];
	?>
	<a href="#" class="sb-twitter appica-twitter-share" data-text="<?php echo $text; ?>" data-url="<?php echo $url; ?>">
		<i class="bi-twitter"></i>
	</a>
	<a href="#" class="sb-google-plus appica-google-share" data-url="<?php echo $url; ?>"><i class="bi-gplus"></i></a>
	<a href="#" class="sb-facebook appica-facebook-share" data-url="<?php echo $url; ?>"><i class="bi-facebook"></i></a>
	<a href="#" class="sb-pinterest appica-pinterest-share"
	   data-url="<?php echo $url; ?>"
	   data-media="<?php echo $img; ?>"
	   data-description="<?php echo $text; ?>">
		<i class="bi-pinterest-circled"></i>
	</a>
	<?php
}

/**
 * Show "Custom Post Title", if exists
 *
 * @since 1.0.0
 *
 * @param bool $echo Display or just return
 *
 * @return string Custom Post Title
 */
function appica_custom_post_title( $echo = true ) {
	$custom_title = '';

	if ( is_single() ) {
		global $post;

		$post_type = appica_get_settings_meta_box_slug( $post->post_type );
		$settings  = get_post_meta( $post->ID, "_{$post_type}_settings", true );
		if ( is_array( $settings )
		     && array_key_exists( 'custom_title', $settings )
		     && '' !== $settings['custom_title']
		) {
			$custom_title = sprintf( '<h2>%s</h2>', $settings['custom_title'] );
		}
	}

	if ( $echo ) {
		echo $custom_title;
	}

	return $custom_title;
}

/**
 * Show "Custom Page Title", if enabled
 *
 * @since 1.0.0
 *
 * @param bool $echo   Display or return value
 * @param bool $markup Display .page-heading markup
 *
 * @return string|void Exit or title value
 */
function appica_custom_page_title( $echo = true, $markup = false ) {
	if ( is_front_page() ) {
		return;
	}

	$title = '';

	if ( is_page() ) {
		global $post;

		// Show title by default
		$title = sprintf( '<h2>%s</h2>', $post->post_title );

		// Hide title, if disabled
		$settings = get_post_meta( $post->ID, '_appica_page_settings', true );
		if ( is_array( $settings )
		     && array_key_exists( 'is_title', $settings )
		     && 0 === (int) $settings['is_title']
		) {
			$title = '';
		}
	}

	if ( $markup && '' !== $title ) {
		$title = sprintf( '<div class="page-heading text-right"><div class="container">%s</div></div>', $title );
	}

	if ( $echo ) {
		echo $title;
	}

	return $title;
}

/**
 * Show "Custom Page Title" for blog Home page
 *
 * @since 1.0.0
 *
 * @param bool $echo Echo or return title
 *
 * @return string Title
 */
function appica_blog_page_title( $echo = true ) {
	$title = '';

	if ( ! is_home() ) {
		return $title;
	}

	$page_id = (int) get_option( 'page_for_posts' );
	if ( 0 === $page_id ) {
		return $title;
	}

	$post     = get_post( $page_id );
	$settings = get_post_meta( $page_id, '_appica_page_settings', true );
	if ( is_array( $settings )
	     && array_key_exists( 'is_title', $settings )
	     && 1 === (int) $settings['is_title']
	) {
		$title = sprintf( '<h2>%s</h2>', $post->post_title );
	}

	if ( $echo ) {
		echo $title;
	}

	return $title;
}

/**
 * Show pagination links for posts, archives, search results etc
 *
 * @since 1.0.0
 */
function appica_paginate_links() {
	$navigation = '';
	$navigation .= '<div class="pagination space-top-3x space-bottom-3x">';
	$navigation .= paginate_links( array(
		'type'      => 'plain',
		'prev_text' => '<i class="flaticon-arrow395"></i>' . _x( 'Newer', 'navigation', 'appica' ),
		'next_text' => _x( 'Older', 'navigation', 'appica' ) . '<i class="flaticon-move13"></i>'
	) );
	$navigation .= '</div>';

	echo $navigation;
}

/**
 * Show favicon
 *
 * @since 1.0.0
 */
function appica_favicon() {
	$favicon = appica_get_option( 'global_favicon' );

	if ( is_array( $favicon ) && array_key_exists( 'url', $favicon ) && '' !== $favicon['url'] ) {
		$url = $favicon['url'];
		echo "<link rel=\"shortcut icon\" href=\"{$url}\" type=\"image/x-icon\">";
		echo "<link rel=\"icon\" href=\"{$url}\" type=\"image/x-icon\">";
	}
}

/**
 * Show preloader
 *
 * @since 1.0.0
 */
function appica_preloader() {
	$logo = appica_get_option( 'global_preloader_logo' );
	$text = appica_get_option( 'global_preloader_text' );

	$html = '';

	$html .= '<div id="preloader">';

	$logo = ( is_array( $logo ) && array_key_exists( 'url', $logo ) && '' !== $logo['url'] ) ? $logo['url'] : '';
	$text = ( '' === $text ) ? '' : esc_html( $text );

	if ( '' !== $logo || '' !== $text ) {
		$html .= '<div class="logo">';
		$html .= ( '' === $logo ) ? '' : sprintf( '<img src="%s">', $logo );
		$html .= ( '' === $text ) ? '' : "<span>{$text}</span>";
		$html .= '</div>';
	}

	$html .= '</div>';

	echo $html;
}

/**
 * Subscribe form modal dialog
 *
 * @since 1.0.0
 */
function appica_subscribe_modal_form() {
	$action = appica_get_option( 'socials_mailchimp' );

	if ( '' === $action ) {
		return;
	}

	$label = appica_get_option( 'socials_subscribe_label', __( 'Subscribe', 'appica' ) );

	// MailChimp prepare Anti-Spam
	$request_uri = parse_url( htmlspecialchars_decode( $action ), PHP_URL_QUERY );
	parse_str( $request_uri , $c );
	$mc_antispam = sprintf( 'b_%1$s_%2$s', $c['u'], $c['id'] );

	unset( $request_uri, $c );

	?><div class="modal fade" id="subscribe-page">
		<div class="modal-dialog">
			<div class="container">
				<div class="modal-form">
					<form method="post" action="<?php echo esc_url( $action ); ?>" id="subscribe-form" autocomplete="off" target="_blank">
						<h3 class="modal-title space-bottom-2x"><?php echo $label; ?></h3>
						<div class="form-group">
							<label for="si-name" class="sr-only"><?php __( 'Name', 'appica' ); ?></label>
							<input type="text" class="form-control" name="NAME" id="si-name" placeholder="Name" required>
							<span class="error-label"></span>
							<span class="valid-label"></span>
						</div>
						<div class="form-group space-top-2x">
							<label for="si_email" class="sr-only"><?php __( 'Email', 'appica' ); ?></label>
							<input type="email" class="form-control" name="EMAIL" id="si_email" placeholder="Email" required>
							<span class="error-label"></span>
							<span class="valid-label"></span>
						</div>
						<div style="position: absolute; left: -5000px;">
							<input type="text" name="<?php echo $mc_antispam; ?>" tabindex="-1" value="">
						</div>
						<div class="space-top-2x clearfix">
							<button type="button" class="btn-round btn-ghost btn-danger pull-left" data-dismiss="modal"><i class="flaticon-cross37"></i></button>
							<button type="submit" class="btn-round btn-ghost btn-success pull-right"><i class="flaticon-correct7"></i></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div><?php
}

/**
 * Display navigation when applicable, according to settings.
 *
 * @since 1.0.0
 */
function appica_posts_navigation() {
	if ( $GLOBALS['wp_query']->max_num_pages <= 1 ) {
		return;
	}

	$type = appica_get_option( 'global_blog_pagination', 'pagination' );

	// Count total number of published posts
	$total    = (int) wp_count_posts()->publish;
	$per_page = (int) get_option( 'posts_per_page' );
	// Determine current page
	$paged = get_query_var( 'paged' );
	$paged = ( $paged ) ? $paged : 1;

	$navigation = '';

	switch ( $type ) {
		case 'infinite-scroll':
			$navigation .= _appica_infinite_scroll( $total, $per_page, $paged );
			break;

		case 'load-more':
			$navigation .= '<div class="pagination space-top-3x space-bottom-3x">';
			$navigation .= _appica_load_more_posts( $total, $per_page, $paged );
			$navigation .= '</div>';
			break;

		default:
			$navigation .= '<div class="pagination space-top-3x space-bottom-3x">';
			$navigation .= paginate_links( array(
				'type'      => 'plain',
				'prev_text' => '<i class="flaticon-arrow395"></i>' . _x( 'Newer', 'navigation', 'appica' ),
				'next_text' => _x( 'Older', 'navigation', 'appica' ) . '<i class="flaticon-move13"></i>'
			) );
			$navigation .= '</div>';
			break;
	}

	echo $navigation;
}

/**
 * Load More posts button for blog navigation
 *
 * @since  1.0.0
 * @access private
 *
 * @param int $total    Total number of published posts
 * @param int $per_page Posts per page. See settings.
 * @param int $paged    Current page
 *
 * @return string
 */
function _appica_load_more_posts( $total, $per_page, $paged ) {
	// Determine number of post to be loaded
	$number = $total - ( $paged * $per_page );

	// Hide "Load More" button if number of entries less than zero,
	// e.g. user load last page and other conditions not fired
	if ( $number <= 0 ) {
		return '';
	}

	// If number of posts greater, than per_page option - show per_per value
	// Variable is needed to show value, if it less than per_page
	$number = ( $number > $per_page ) ? $per_page : $number;
	$more = sprintf( __( '%s more', 'appica' ), $number );

	// 1 - more, 2 - current page, 3 - total posts count
	$template = '<a href="#" data-page="%2$s" data-total="%3$s" data-per-page="%4$s" class="load-more load-more-posts">'
	            . '<span class="count">%1$s</span><span class="shape"></span>'
	            . '</a>';

	// Paged always have to be + 1, because we need to load next page, not current
	$navigation = sprintf( $template, $more, $paged + 1, $total, $per_page );

	return $navigation;
}

/**
 * Infinite Scroll for blog navigation
 *
 * @since  1.0.0
 * @access private
 *
 * @param int $total    Total number of published posts
 * @param int $per_page Posts per page. See settings.
 * @param int $paged    Current page
 *
 * @return string
 */
function _appica_infinite_scroll( $total, $per_page, $paged ) {
	// Determine number of post to be loaded
	$number = $total - ( $paged * $per_page );

	// Hide "Load More" button if number of entries less than zero,
	// e.g. user load (not first) page and other conditions not fired
	if ( $number <= 0 ) {
		return '';
	}

	// 1 - total, 2 - per page, 3 - current page
	$template = '<div class="hidden" id="appica-infinite-scroll" data-total="%1$s" data-per-page="%2$s" data-page="%3$s" data-max-pages="%4$s"></div>';

	// Paged always have to be + 1, because we need to load next page, not current
	return sprintf( $template, $total, $per_page, $paged + 1, $GLOBALS['wp_query']->max_num_pages );
}

/**
 * Print social networks. Same code for all places.
 * Set up social networks list in "Socials" setting section.
 *
 * @since 1.0.0
 */
function appica_the_socials() {
	/**
	 * @var array Allowed targets for socials links
	 */
	$allowed_targets = array( '_blank', '_self' );

	$networks = appica_get_social_networks_list();
	$socials  = appica_get_option( 'socials_networks' );
	$socials  = appica_process_social_networks( $socials );
	$target   = appica_get_option( 'socials_networks_target', '_blank' );
	$target   = ( in_array( $target, $allowed_targets, true ) ) ? $target : '_blank';

	echo '<div class="social-buttons">';

	foreach( (array) $socials as $network => $url ) {
		printf(
			'<a href="%1$s" class="%3$s" target="%4$s"><i class="%2$s"></i></a>',
			esc_url( $url ), esc_attr( $networks[ $network ]['icon'] ),
			esc_attr( $networks[ $network ]['helper'] ), $target
		);
	}

	echo '</div>';
}

/**
 * Add extra classes to page wrapper
 *
 * @since 1.0.0
 *
 * @return string
 */
function appica_get_page_wrapper() {
	$wrapper = array();
	/**
	 * @var bool Save the result of function call to increase the performance (a little)
	 */
	$is_front_page = is_front_page();

	// Always wrap a page into .container to cover the cases, when option doesn't exists
	// for all pages, except Front page
	if ( false === $is_front_page ) {
		$wrapper[] = 'container';
	}

	// Wrap the page with class .content-wrap,
	// if "Sticky navbar" is enabled, user is on front page and intro screen is disabled.
	if ( $is_front_page
	     && false === appica_is_intro()
	     && true === (bool) appica_get_option( 'navbar_is_sticky', false )
	) {
		$wrapper[] = 'content-wrap';
	}

	// But, if "layout" option exists and set to "fluid" in page settings meta box
	// remove the .container class
	$post_type     = appica_get_settings_meta_box_slug( 'page' );
	$page_settings = get_post_meta( get_the_ID(), "_{$post_type}_settings", true );
	if ( is_array( $page_settings )
	     && array_key_exists( 'layout', $page_settings )
	     && 'fluid' === $page_settings['layout']
	) {
		$key = array_search( 'container', $wrapper, true );
		if ( $key !== false ) {
			unset( $wrapper[ $key ] );
		}
		unset( $key );
	}

	return $wrapper;
}

/**
 * Check if search form enabled for post type.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function appica_is_search() {
	if ( is_single() || is_page() ) {
		global $post;

		$post_type = appica_get_settings_meta_box_slug( $post->post_type );
		$settings  = get_post_meta( $post->ID, "_{$post_type}_settings", true );
		if ( is_array( $settings ) && array_key_exists( 'search', $settings) ) {
			return ( 1 === (int) $settings['search'] );
		}
	}

	return false;
}

/**
 * Check if search form or custom post title is enabled.
 * Otherwise, add class to .page-heading to reduce height.
 *
 * @param string $title     Custom post title
 * @param bool   $is_search Is search enabled
 */
function appica_is_page_heading( $title = '', $is_search = false ) {
	$title     = (string) $title;
	$is_search = (bool) $is_search;

	$classes = array();

	if ( '' === $title && true === $is_search ) {
		$classes[] = 'no-title';
	}

	if ( '' === $title && false === $is_search ) {
		$classes[] = 'no-content';
	}

	echo implode( ' ', $classes );
}

/**
 * Get sidebar position in Single Post
 *
 * @since 1.0.0
 *
 * @return string
 */
function appica_sidebar_position() {
	if ( is_single() ) {
		global $post;

		$post_type = appica_get_settings_meta_box_slug( $post->post_type );
		$settings  = get_post_meta( $post->ID, "_{$post_type}_settings", true );
		if ( is_array( $settings ) && array_key_exists( 'sidebar', $settings ) ) {
			return $settings['sidebar'];
		}
	}

	return 'left';
}

/**
 * Get class set of Single post content column, based on sidebar position
 *
 * @since 1.0.0
 *
 * @param string $position Sidebar position: left | right | none
 * @param bool   $echo Echo or just return value. Default is TRUE
 *
 * @return string Class set
 */
function appica_content_column_classes( $position = 'left', $echo = true ) {
	$classes = 'col-lg-8 col-lg-offset-1 col-lg-push-3 col-sm-8 col-sm-push-4 padding-bottom';

	if ( 'none' === $position ) {
		$classes = 'col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 padding-bottom';
	} elseif ( 'right' === $position ) {
		$classes = 'col-lg-8 col-sm-8 padding-bottom';
	}

	if ( $echo ) {
		echo $classes;
	}

	return $classes;
}

/**
 * Wrapper for {@see get_sidebar()} on steroids. Based on sidebar position option.
 *
 * @since 1.0.0
 *
 * @param string|null $name The name of the specialised sidebar.
 * @param string $position Sidebar position: left | right | none
 *
 * @return void
 */
function appica_post_sidebar( $name = null, $position = 'left' ) {
	$classes = '';

	if ( 'none' === $position ) {
		return;
	} elseif ( 'right' === $position ) {
		$classes .= 'col-lg-3 col-lg-offset-1 col-sm-4';
	} else {
		$classes .= 'col-lg-3 col-lg-pull-9 col-sm-4 col-sm-pull-8';
	}

	echo "<div class=\"{$classes}\">";
	get_sidebar( $name );
	echo '</div>';
}

/**
 * Returns the URI for provided image. This function is designed primarily for images,
 * but it is allowable for any other assets type you need: images, fonts, js, css etc.
 *
 * @param string $asset Relative path to image, e.g. "image/logo.png"
 * @param bool   $echo Echoed or not the value. Default is TRUE.
 *
 * @return string
 */
function appica_image_uri( $asset, $echo = true  ) {
	$asset = ltrim( $asset, '/' );
	$uri = trailingslashit( get_template_directory_uri() ) . $asset;

	if ( $echo ) {
		echo $uri;
	}

	return $uri;
}

/**
 * Check if Featured Video used.
 * Video has priority over Featured Image.
 *
 * @author 8guild
 * @since 1.0.0
 *
 * @return bool
 */
function appica_has_featured_video() {
	$meta = get_post_meta( get_the_ID(), '_appica_featured_video', true );

	if ( ! empty( $meta )) {
		return true;
	}

	return false;
}

/**
 * Show Featured Video
 *
 * @author 8guild
 * @since 1.0.0
 */
function appica_the_featured_video() {
	$url = get_post_meta( get_the_ID(), '_appica_featured_video', true );
	$embed = wp_oembed_get( $url );

	if ( false !== $embed ) {
		echo $embed;
	}
}

/**
 * Body extra classes
 *
 * @since 1.0.0
 *
 * @return string
 */
function appica_body_extra_class() {
	$classes = array( 'gray-bg' );

	// remove gray from front-page
	if ( is_front_page() && ! is_home() ) {
		$key = array_search( 'gray-bg', $classes, true );
		unset( $classes[ $key ], $key );
	}

	if ( is_front_page() && ! is_home() && true === (bool) appica_get_option( 'navbar_is_sticky', false ) ) {
		$classes[] = 'fixed-footer';
	}

	return implode( ' ', $classes );
}

/**
 * Echoes and return class set for navbar
 *
 * @since 1.0.0
 *
 * @return string
 */
function appica_the_sticky_navbar() {
	$classes = array( 'navbar', 'gray' );

	if ( true === (bool) appica_get_option( 'navbar_is_sticky', false ) ) {
		$classes[] = ( is_front_page() ) ? 'navbar-sticky' : 'navbar-fixed-top';
	}

	$classes = implode( ' ', $classes );

	echo $classes;

	return $classes;
}

/**
 * Check if footer app info is enabled
 *
 * @since 1.0.0
 *
 * @return bool
 */
function appica_is_footer_app() {
	return ( true === (bool) appica_get_option( 'footer_is_app' ) );
}

/**
 * Check if footer logo is used
 *
 * @since 1.0.0
 *
 * @return bool
 */
function appica_is_footer_logo() {
	$logo = appica_get_option( 'footer_logo' );

	return ( is_array( $logo ) && '' !== $logo['url'] );
}

/**
 * Display footer logo
 *
 * @since 1.0.0
 */
function appica_footer_logo() {
	$logo = appica_get_option( 'footer_logo' );

	if ( is_front_page() ) {
		printf( '<a href="#" class="scrollup"><img src="%s"></a>', esc_url( $logo['url'] ) );
	} else {
		printf( '<a href="%1$s"><img src="%2$s"></a>', home_url( '/' ), esc_url( $logo['url'] ) );
	}
}

/**
 * Show footer app name
 *
 * @since 1.0.0
 */
function appica_footer_app_name() {
	$name = appica_get_option( 'footer_app_name' );

	if ( '' !== $name ) {
		echo "<h2>{$name}</h2>";
	}
}

/**
 * Print footer app tagline
 *
 * @since 1.0.0
 */
function appica_footer_app_tagline() {
	$is_app_tagline = appica_get_option( 'footer_is_app_tagline' );

	if ( true === (bool) $is_app_tagline ) {
		printf( '<p>%s</p>', appica_get_option( 'footer_app_tagline' ) );
	}
}

/**
 * Print footer app content rating
 *
 * @since 1.0.0
 */
function appica_footer_app_content_rating() {
	$is_content_rating = appica_get_option( 'footer_app_content_rating' );

	if ( true === (bool) $is_content_rating ) {
		printf( '<span>%s</span>', appica_get_option( 'footer_app_content_rating' ) );
	}
}

/**
 * Display footer app rating stars and counter
 *
 * @since 1.0.0
 */
function appica_footer_app_rating() {
	$is_app_rating = appica_get_option( 'footer_is_app_rating' );

	if ( false === (bool) $is_app_rating ) {
		return;
	}

	$rating = round( appica_get_option( 'footer_app_rating' ) );
	echo '<div class="rating">';
	for ( $i = 0; $i < $rating; $i++ ) {
		echo '<i class="bi-star"></i>';
	}

	// check rating counter
	$is_ratings_counter = appica_get_option( 'footer_is_app_ratings_counter' );
	if ( true === (bool) $is_ratings_counter ) {
		printf( '<span>(%s)</span>', appica_get_option( 'footer_app_ratings_counter' ) );
	}

	echo '</div>';
}

/**
 * Echoes the footer copyright contents
 *
 * @since 1.0.0
 */
function appica_the_copyright() {
	echo appica_get_option( 'footer_copyright' );
}

/**
 * Check if footer device is enabled
 *
 * @since 1.0.0
 *
 * @return bool
 */
function appica_is_footer_device() {
	return ( true === (bool) appica_get_option( 'footer_is_device' ) );
}

/**
 * Check if footer nav location is enabled
 *
 * @since 1.0.0
 *
 * @return bool
 */
function appica_is_footer_nav() {
	return ( true === (bool) appica_get_option( 'footer_is_nav', true ) );
}

/**
 * Check if user add image to footer device screen
 *
 * @since 1.0.0
 *
 * @return bool
 */
function appica_is_footer_device_screen() {
	$attachment = appica_get_option( 'footer_device_screen' );

	return ( is_array( $attachment ) && '' !== $attachment['url'] );
}

/**
 * Show footer device image
 *
 * @since 1.0.0
 */
function appica_footer_device_screen() {
	$attachment = appica_get_option( 'footer_device_screen' );

	printf( '<img src="%s">', $attachment['url'] );
}

/**
 * Show navbar logo and title
 *
 * @since 1.0.0
 */
function appica_navbar_logo() {
	$logo  = appica_get_option( 'navbar_logo' );
	$title = appica_get_option( 'navbar_title' );
	$title = ( '' === $title ) ? '' : esc_html( $title );

	if ( is_array( $logo ) && array_key_exists( 'url', $logo ) && '' !== $logo['url'] ) {
		$logo = sprintf( '<img src="%1$s" alt="%2$s">', esc_url( $logo['url'] ), $title );
	} else {
		$logo = sprintf( '<img src="%1$s" alt="%2$s">', appica_image_uri( 'img/logo-small.png', false ), $title );
	}

	if ( is_front_page() && ! is_home() ) {
		printf( '<a href="#" class="logo scrollup">%1$s %2$s</a>', (string) $logo, $title );
	} else {
		printf( '<a href="%1$s" class="logo">%2$s %3$s</a>', home_url( '/' ), (string) $logo, $title );
	}
}

/**
 * Render navbar social networks
 *
 * Based on social networks list from "Socials" options
 *
 * @uses appica_get_social_networks_list()
 * @uses appica_process_social_networks()
 *
 * @since 1.0.0
 */
function appica_navbar_socials() {
	$is_social = appica_get_option( 'navbar_is_social' );

	if ( false === (bool) $is_social ) {
		return;
	}

	appica_the_socials();
}

/**
 * Print navbar download button and helper text
 *
 * @since 1.0.0
 */
function appica_navbar_download_button() {
	$is_btn = appica_get_option( 'navbar_is_download' );
	if ( false === (bool) $is_btn ) {
		echo '<div class="btn invisible">&nbsp;</div>';
		return;
	}

	$helper = appica_get_option( 'navbar_download_helper' );
	$text   = appica_get_option( 'navbar_download_button_text' );
	$url    = appica_get_option( 'navbar_download_button_url' );

	if ( '' !== $helper ) {
		printf( '<span>%s</span>', esc_html( $helper ) );
	}

	// just a bit safer
	$url = ( '' === $url ) ? '#' : $url;

	printf(
		'<a href="%1$s" class="btn btn-ghost btn-primary icon-left" target="_blank"><i class="bi-apple"></i> %2$s</a>',
		esc_url( $url ), esc_html( $text )
	);
}

/**
 * Print navbar subscribe/login button
 *
 * @since 1.0.0
 */
function appica_navbar_subscribe() {
	$is_subscribe = appica_get_option( 'navbar_is_subscribe' );

	if ( false === (bool) $is_subscribe ) {
		return;
	}

	$text = appica_get_option( 'socials_subscribe_label', __( 'Subscribe', 'appica' ) );

	printf( '<a href="#" class="action-btn" data-toggle="modal" data-target="#subscribe-page">%s</a>', esc_html( $text ) );
}

/**
 * Check if Off-canvas navigation is enabled
 *
 * @since 1.0.0
 *
 * @return bool
 */
function appica_is_offcanvas_search() {
	$is_search = appica_get_option( 'offcanvas_is_search' );

	return ( true === (bool) $is_search );
}

/**
 * Print social networks list in Off-Canvas navigation
 *
 * @since 1.0.0
 */
function appica_offcanvas_socials() {
	$is_social = appica_get_option( 'offcanvas_is_socials' );

	if ( false === (bool) $is_social ) {
		return;
	}

	appica_the_socials();
}

/**
 * Show logo, title, subtitle in Off-canvas navigation
 *
 * @since 1.0.0
 */
function appica_offcanvas_logo() {
	$logo     = appica_get_option( 'offcanvas_logo' );
	$title    = appica_get_option( 'offcanvas_title' );
	$subtitle = appica_get_option( 'offcanvas_subtitle' );

	$is_front_page = is_front_page();

	$home_url = ( $is_front_page ) ? '#' : home_url( '/' );
	$classes  = ( $is_front_page ) ? 'offcanvas-logo scrollup' : 'offcanvas-logo';

	$icon = ( is_array( $logo ) && '' !== $logo['url'] )
		? sprintf( '<div class="icon"><img src="%s"></div>', $logo['url'] )
		: '';

	$title = ( '' !== $title || '' !== $subtitle )
		? sprintf( '<div class="title">%1$s%2$s</div>', esc_html( $title ), '<span>' . esc_html( $subtitle ) . '</span>' )
		: '';

	printf( '<a href="%1$s" class="%4$s">%2$s%3$s</a>', $home_url, $icon, $title, $classes );
}

/**
 * Show download button in Off-Canvas navigation
 *
 * @since 1.0.0
 */
function appica_offcanvas_button() {
	$is_button = appica_get_option( 'offcanvas_is_download' );

	if ( false === (bool) $is_button ) {
		return;
	}

	$label = appica_get_option( 'offcanvas_download_label', __( 'Download', 'appica' ) );
	$url   = appica_get_option( 'offcanvas_download_url' );
	$url   = ( '' === $url ) ? '#' : $url;

	printf(
		'<a href="%1$s" class="btn btn-ghost btn-light icon-left"><i class="bi-apple"></i> %2$s</a>',
		esc_url( $url ), esc_html( $label )
	);
}

/**
 * Show subscribe link in Off-canvas navigation
 *
 * @since 1.0.0
 */
function appica_offcanvas_subscribe() {
	$is_subscribe = appica_get_option( 'offcanvas_is_subscribe' );

	if ( false === (bool) $is_subscribe ) {
		return;
	}

	$text = appica_get_option( 'socials_subscribe_label', __( 'Subscribe', 'appica' ) );

	printf( '<a href="#" class="text-smaller text-warning" data-toggle="modal" data-target="#subscribe-page">%s</a>', esc_html( $text ) );
}

/**
 * Check if Intro section is enabled
 * Note: intro can appear only on front page
 *
 * @since 1.0.0
 *
 * @return bool
 */
function appica_is_intro() {
	return ( is_front_page() && ! is_home() && true === (bool) appica_get_option( 'intro_is_enabled' ) );
}

/**
 * Print Intro background image
 *
 * @since 1.0.0
 */
function appica_intro_background() {
	$bg = appica_get_option( 'intro_background' );

	if ( is_array( $bg) && '' !== $bg['url'] ) {
		printf( 'style="background-image: url(%s);"', esc_url( $bg['url'] ) );
	}
}

/**
 * Check if Intro overlay is used
 *
 * @since 1.0.0
 *
 * @return bool
 */
function appica_is_intro_gradient() {
	return ( true === (bool) appica_get_option( 'intro_is_overlay', true ) );
}

/**
 * Show Intro: logo, title & subtitle if exists
 *
 * Do not print any html, if options not filled
 *
 * @since 1.0.0
 */
function appica_intro_logo() {
	$logo     = appica_get_option( 'intro_logo' );
	$title    = appica_get_option( 'intro_title' );
	$subtitle = appica_get_option( 'intro_subtitle' );

	$logo = ( is_array( $logo ) && '' !== $logo['url'] ) ? sprintf( '<img src="%1$s" alt="%2$s">', $logo['url'], $title ) : '';
	$title = ( '' !== $title ) ? esc_html( $title ) : '';
	$subtitle = ( '' !== $subtitle ) ? '<span>' . esc_html( $subtitle ) . '</span>' : '';

	if ( '' !== $logo || '' !== $title || '' !== $subtitle ) {
		printf( '<h1 class="logo">%1$s%2$s%3$s</h1>', $logo, $title, $subtitle );
	}
}

/**
 * Show Intro screen image (inside device)
 *
 * @since 1.0.0
 */
function appica_intro_screen() {
	$screen = appica_get_option( 'intro_iphone_screen' );

	echo '<div class="phone">';

	if ( is_array( $screen ) && array_key_exists( 'url', $screen ) && '' !== $screen['url'] ) {
		printf( '<img src="%s">', $screen['url'] );
	}

	echo '</div>';
}

/**
 * Show social networks in Intro screen
 *
 * @since 1.0.0
 */
function appica_intro_socials() {
	$is_social = appica_get_option( 'intro_is_social' );

	if ( false === (bool) $is_social ) {
		return;
	}

	appica_the_socials();
}

/**
 * Show Intro subscribe
 *
 * @since 1.0.0
 */
function appica_intro_subscribe() {
	$is_subscribe = appica_get_option( 'intro_is_subscribe' );

	if ( false === (bool) $is_subscribe ) {
		return;
	}

	$text = appica_get_option( 'socials_subscribe_label', __( 'Subscribe', 'appica' ) );

	printf( '<a href="#" data-toggle="modal" data-target="#subscribe-page">%s</a>', esc_html( $text ) );
}

/**
 * Show intro "Scroll for more" button
 *
 * @since 1.0.0
 */
function appica_intro_scroll() {
	$is_scroll = appica_get_option( 'intro_is_scroll' );

	if ( false === (bool) $is_scroll) {
		return;
	}

	$anchor = appica_get_option( 'intro_scroll_anchor' );
	$anchor = '#' . esc_attr( trim( $anchor, '#' ) );
	$text   = appica_get_option( 'intro_scroll_text' );
	$text   = ( '' === $text ) ? '' : esc_html( $text );

	printf(
		'<a href="%1$s" class="scroll-more scroll" data-offset-top="80"><i class="icon"></i><span>%2$s</span></a>',
		$anchor, $text
	);
}

/**
 * Show Intro download button
 *
 * @since 1.0.0
 */
function appica_intro_download() {
	$is_download = appica_get_option( 'intro_is_download' );

	if ( false === (bool) $is_download ) {
		return;
	}

	$helper = appica_get_option( 'intro_download_helper' );
	$helper = ( '' === $helper ) ? '' : '<p>' . esc_html( $helper ) . '</p>';
	$text   = appica_get_option( 'intro_download_text' );
	$text   = ( '' === $text ) ? '' : '<span>' . esc_html( $text ) . '</span>';
	$url    = appica_get_option( 'intro_download_url' );
	$url    = ( '' === $url ) ? '#' : esc_url( $url );

	$tpl = '<div class="download">%1$s<a href="%2$s" class="btn btn-default btn-app-store"><i class="bi-apple"></i><div>%3$s App Store</div></a></div>';
	printf( $tpl , $helper, $url, $text );

}

/**
 * Show Intro features
 *
 * @since 1.0.0
 */
function appica_intro_features() {
	$is_features = appica_get_option( 'intro_is_features' );

	if ( false === (bool) $is_features ) {
		return;
	}

	$features = array(
		array(
			'transition' => '100',
			'icon'       => appica_get_option( 'intro_feature_1_icon' ),
			'title'      => appica_get_option( 'intro_feature_1_title' ),
			'desc'       => appica_get_option( 'intro_feature_1_desc' )
		),
		array(
			'transition' => '300',
			'icon'       => appica_get_option( 'intro_feature_2_icon' ),
			'title'      => appica_get_option( 'intro_feature_2_title' ),
			'desc'       => appica_get_option( 'intro_feature_2_desc' )
		),
		array(
			'transition' => '500',
			'icon'       => appica_get_option( 'intro_feature_3_icon' ),
			'title'      => appica_get_option( 'intro_feature_3_title' ),
			'desc'       => appica_get_option( 'intro_feature_3_desc' )
		)
	);

	$html = '';
	$html .= '<div class="intro-features">';

	foreach ( $features as $feature ) {
		if ( '' === $feature['icon'] || '' === $feature['title'] || '' === $feature['desc'] ) {
			continue;
		}

		$html .= '<div class="icon-block icon-block-horizontal light-color" data-transition-delay="' . $feature['transition'] .'">';
		$html .= ( '' === $feature['icon'] ) ? '' : '<div class="icon"><i class="' . esc_attr( $feature['icon'] ) . '"></i></div>';
		$html .= '<div class="text">';
		$html .= ( '' === $feature['title'] ) ? '' : '<h3>' . esc_html( $feature['title'] ) . '</h3>';
		$html .= ( '' === $feature['desc'] ) ? '' : '<p>' . stripslashes( $feature['desc'] ) . '</p>';
		$html .= '</div>'; // close .text
		$html .= '</div>'; // close .icon-block

	}
	unset( $feature );

	$html .= '</div>';

	echo $html;
}

/**
 * Get the type of Intro Screen
 *
 * @since 1.3.0
 *
 * @return string
 */
function appica_intro_type() {
	$default = 'appshowcase';
	$type    = appica_get_option( 'intro_type', $default );

	return ( '' === (string) $type ) ? $default : $type;
}

/**
 * Display the Revolution Slider
 *
 * @since 1.3.0
 */
function appica_intro_revslider() {
	$alias = appica_get_option( 'intro_revslider' );
	if ( false === (bool) $alias || false === function_exists( 'putRevSlider' ) ) {
		return;
	}

	putRevSlider( $alias, 'homepage' );
}