<?php
/**
 * Theme custom widgets
 *
 * @author 8guild
 * @package Appica 2
 */

/**
 * Widget Recent Posts: custom image size for displaying featured images in widgets
 *
 * @since 1.0.0
 */
add_image_size( 'appica-wrp-thumbnail', 320, 225, true );

/**
 * Flush cached posts for widget, when any one modified
 *
 * @since 1.0.0
 */
function appica_flush_widget_cache() {
	wp_cache_delete( 'widget_recent_posts', 'widget' );
}

add_action( 'save_post', 'appica_flush_widget_cache' );
add_action( 'deleted_post', 'appica_flush_widget_cache' );
add_action( 'switch_theme', 'appica_flush_widget_cache' );

/**
 * Change excerpt length, used in widgets.
 *
 * @param int $length The number of words to display
 *
 * @since 1.0.0
 *
 * @return int
 */
function appica_widget_excerpt_length( $length ) {
	return 9;
}

/**
 * Reduce the length of custom excerpt (if specified manually) for widgets.
 *
 * @param string $excerpt Current excerpt
 *
 * @since 1.0.0
 *
 * @return string
 */
function appica_widget_trim_excerpt( $excerpt ) {
	if ( false === strpos( $excerpt, '...' ) && str_word_count( $excerpt ) > 9 ) {
		$excerpt = wp_trim_words( $excerpt, 9, ' ...' );
	}

	return $excerpt;
}

/**
 * Class Appica_Twitter
 *
 * @since 1.0.0
 */
class Appica_Twitter {
	/**
	 * Registered consumer key
	 * @var string
	 */
	private $consumer_key;
	/**
	 * Registered consumer secret
	 * @var string
	 */
	private $consumer_secret;
	/**
	 * Twitter nickname.
	 * @var string
	 */
	private $screen_name;
	/**
	 * Current widget params
	 * @var array
	 */
	private $widget;
	/**
	 * Transient name for Twitter Access Token
	 * @var string
	 */
	private $transient_access_token = 'appica_twitter_access_token';
	/**
	 * Transient name for Tweets
	 * @var string
	 */
	private $transient_tweets = 'appica_twitter_tweets';

	/**
	 * Constructor
	 *
	 * @param array $widget [optional] Current widget params
	 */
	public function __construct( $widget = array() ) {
		$this->widget = $widget;

		$this->screen_name     = appica_get_option( 'twitter_screen_name' );
		$this->consumer_key    = appica_get_option( 'twitter_consumer_key' );
		$this->consumer_secret = appica_get_option( 'twitter_consumer_secret' );
	}

	/**
	 * Return tweets.
	 *
	 * Uses WordPress Transients API and HTTP API
	 *
	 * @param int $number Number of tweets to request from Twitter API
	 *
	 * @return array
	 */
	public function get_tweets( $number = 5 ) {
		$number = absint( $number );

		// Check cache
		$tweets = get_transient( $this->transient_tweets );

		// if user requires more tweets than cached
		if ( false !== $tweets && count( $tweets ) < $number ) {
			delete_transient( $this->transient_tweets );
			$tweets = false;
		}

		if ( false === $tweets ) {
			// Do not check ssl to prevent SSL certificate problems
			add_filter( 'https_ssl_verify', '__return_false' );
			// Get auth token
			$authorization = $this->authorization();

			// If unsuccessful authorization or user not fill screen name
			if ( '' === $authorization || '' === $this->screen_name ) {
				return array();
			}

			$args = array(
				'httpversion' => '1.1',
				'headers'     => array( 'Authorization' => $authorization )
			);

			$uri = http_build_query( array(
				'screen_name'     => $this->screen_name,
				'count'           => $number,
				'exclude_replies' => false,
				'include_rts'     => true
			) );
			$url = "https://api.twitter.com/1.1/statuses/user_timeline.json?{$uri}";

			$response = wp_remote_get( $url, $args );

			if ( is_wp_error( $response ) ) {
				return array();
			}

			$tweets = json_decode( wp_remote_retrieve_body( $response ), true );
			// Handle errors, do not show errors, just return empty array
			if ( is_array( $tweets ) && array_key_exists( 'errors', $tweets ) && 0 !== count( $tweets['errors'] ) ) {
				return array();
			}

			// Cache tweets for 15 minutes
			set_transient( $this->transient_tweets, $tweets, 15 * MINUTE_IN_SECONDS );

			remove_filter( 'https_ssl_verify', '__return_false' );
		}

		// if user requires less tweets than cached
		if ( count( $tweets ) > $number ) {
			$tweets = array_slice( $tweets, 0, $number );
		}

		return $tweets;
	}

	/**
	 * Display single tweet
	 *
	 * @param array $tweet Single tweet data
	 *
	 * @return string
	 */
	public function display_tweet( $tweet ) {
		$template = '<div class="tweet" data-tweet-id="%1$s">%2$s%3$s</div>';

		$body   = sprintf( '<p>%s</p>', $this->parse_tweet( $tweet['text'], $tweet['entities'] ) );
		$author = sprintf(
			'<a href="%1$s" class="author" target="_blank">@%2$s</a>',
			"https://twitter.com/{$tweet['user']['screen_name']}",
			$tweet['user']['screen_name']
		);

		return sprintf( $template, $tweet['id_str'], $author, $body );
	}

	/**
	 * Get user screen name / Twitter nickname
	 *
	 * @return string
	 */
	public function get_screen_name() {
		return $this->screen_name;
	}

	/**
	 * Returns the Twitter Application-only authentication bearer token.
	 *
	 * Uses WordPress Transients API and HTTP API
	 *
	 * @return string
	 */
	private function authorization() {
		// Check cache
		$token = get_transient( $this->transient_access_token );
		if ( false === $token ) {

			// if user not fill required fields
			if ( '' === $this->consumer_key || '' === $this->consumer_secret ) {
				return '';
			}

			$credentials = base64_encode( "{$this->consumer_key}:{$this->consumer_secret}" );

			$args = array(
				'method'      => 'POST',
				'httpversion' => '1.1',
				'body'        => array( 'grant_type' => 'client_credentials' ),
				'headers'     => array(
					'Authorization' => "Basic {$credentials}",
					'Content-Type'  => 'application/x-www-form-urlencoded;charset=UTF-8'
				)
			);

			$response = wp_remote_post( 'https://api.twitter.com/oauth2/token', $args );
			$keys     = json_decode( wp_remote_retrieve_body( $response ) );
			$token    = $keys->access_token;

			// Store token for 1 day
			set_transient( $this->transient_access_token, $token, DAY_IN_SECONDS );
		}

		return "Bearer {$token}";
	}

	/**
	 * Parse the tweet body, make hashtags, mentions, links clickable.
	 *
	 * @param string $text Raw tweet body
	 * @param array  $entities All hashtags, mentions, links, media attached to tweet
	 *
	 * @return string
	 */
	private function parse_tweet( $text, $entities = array() ) {

		if ( ! empty( $entities['urls'] ) ) {
			$text = $this->parse_tweet_urls( $text, $entities['urls'] );
		}

		if ( ! empty( $entities['hashtags'])) {
			$text = $this->parse_tweet_hashtags( $text, $entities['hashtags'] );
		}

		if ( ! empty($entities['user_mentions'])) {
			$text = $this->parse_tweet_mentions( $text, $entities['user_mentions'] );
		}

		return $text;
	}

	/**
	 * Make URLs clickable
	 *
	 * @param string $text Tweet body
	 * @param array  $urls Array of URLs, using in tweet
	 *
	 * @return string
	 */
	private function parse_tweet_urls( $text, $urls = array() ) {
		$search  = array();
		$replace = array();
		foreach ( $urls as $k => $url ) {
			$search[ $k ]  = $url['url'];
			$replace[ $k ] = sprintf( '<a href="%1$s" target="_blank">%2$s</a>', $url['url'], $url['display_url'] );
		}
		unset( $url );

		return str_replace( $search, $replace, $text );
	}

	/**
	 * Make hashtags clickable
	 *
	 * @param string      $text Tweet body
	 * @param array $hashtags Array of hashtags, used in tweet body
	 *
	 * @return string
	 */
	private function parse_tweet_hashtags( $text, $hashtags = array() ) {
		$search  = array();
		$replace = array();
		foreach ( $hashtags as $k => $hashtag ) {
			$h = $hashtag['text'];

			$search[ $k ]  = "#{$h}";
			$replace[ $k ] = sprintf(
				'<a href="%1$s" target="_blank">%2$s</a>', "https://twitter.com/hashtag/{$h}", "#{$h}"
			);
		}
		unset( $hashtag, $h );

		return str_replace( $search, $replace, $text );
	}

	/**
	 * Make mentions clickable
	 *
	 * @param string $text     Tweet body
	 * @param array  $mentions Array of mentions, used in tweet
	 *
	 * @return mixed
	 */
	private function parse_tweet_mentions( $text, $mentions = array() ) {
		$search  = array();
		$replace = array();
		foreach ( $mentions as $k => $mention ) {
			$screen_name = $mention['screen_name'];

			$search[ $k ]  = "@{$screen_name}";
			$replace[ $k ] = sprintf(
				'<a href="%1$s" target="_blank">%2$s</a>', "https://twitter.com/{$screen_name}", "@{$screen_name}"
			);
		}
		unset( $mention, $screen_name );

		return str_replace( $search, $replace, $text );
	}

}

/**
 * Class Appica_Flickr
 *
 * @since 1.0.0
 */
class Appica_Flickr {
	/**
	 * Flickr API key
	 * @var string
	 */
	private $api_key;
	/**
	 * Flickr response format
	 * @var string
	 */
	private $format = 'json';
	/**
	 * Flickr API endpoint URL
	 * @var string
	 */
	private $endpoint = 'https://api.flickr.com/services/rest/';
	/**
	 * Transient name for Flickr user NSID
	 * @var string
	 */
	private $transient_user_id = 'appica_flickr_user_id';
	/**
	 * Transient name for Flickr photos
	 * @var string
	 */
	private $transient_photos = 'appica_flickr_photos';


	public function __construct() {
		$this->api_key = appica_get_option( 'flickr_api_key' );
	}

	/**
	 * Return user's public Flickr photos
	 *
	 * We provided various settings for requesting Flickr API: by username or globally by tag(s), or by username and tag(s)
	 * So, if user have some widgets with DIFFERENT settings, he can see the same photos in both places. Why?
	 * Because of cache. We cache photos after first API call, if old cache expires.
	 *
	 * So, we decided to add unique slug to transient name
	 *
	 * @param string $username
	 * @param string $tags
	 * @param int    $per_page Photos per one page
	 *
	 * @return array
	 */
	public function get_photos( $username = '', $tags = '', $per_page = 9 ) {
		$slug = $this->get_unique_slug( "{$username}_{$tags}" );
		/**
		 * @var string Flickr request transient name
		 */
		$transient_photos = "{$this->transient_photos}_{$slug}";

		// check cache once per hour
		$photos = get_transient( $transient_photos );

		// if user requires more photos than cached
		if ( false !== $photos && count( $photos ) < $per_page ) {
			delete_transient( $this->transient_photos );
			$photos = false;
		}

		if ( false === $photos ) {
			// store user_id
			$user_id = $this->get_user_id( $username );

			// Build query
			$args = array(
				'safe_search'  => 1,
				'content_type' => 7,
				'per_page'     => $per_page,
				'method'       => 'flickr.photos.search'
			);

			if ( '' !== $user_id && false !== $user_id ) {
				$args['user_id'] = $user_id;
			}

			if ( '' !== $tags ) {
				$args['tags'] = $tags;
			}

			$photos = array();
			$response = $this->request( $args );

			if ( 'fail' === $response['stat'] ) {
				return array();
			}

			if ( 'ok' === $response['stat'] ) {
				$photos = $response['photos']['photo'];
				set_transient( $transient_photos, $photos, HOUR_IN_SECONDS );
			}
		}

		// if user requires less photos than cached
		if ( count( $photos ) > $per_page ) {
			$photos = array_slice( $photos, 0, $per_page );
		}

		return $photos;
	}

	/**
	 * Return Flickr photo source link
	 *
	 * @param array $photo Flickr photo data
	 *
	 * @return string
	 */
	public function get_photo_src( $photo ) {
		// 1 - farm, 2 - server, 3 - photo-id, 4 - photo secret
		$tpl = 'https://farm%1$s.staticflickr.com/%2$s/%3$s_%4$s_s.jpg';

		return esc_url( sprintf( $tpl, $photo['farm'], $photo['server'], $photo['id'], $photo['secret'] ) );
	}

	/**
	 * Return link to Flickr photo
	 *
	 * @param array $photo Flickr photo data
	 *
	 * @return string
	 */
	public function get_photo_href( $photo ) {
		// 1 - user-id, 2 - photo-id
		$tpl = 'https://www.flickr.com/photos/%1$s/%2$s';

		return esc_url( sprintf( $tpl, $photo['owner'], $photo['id'] ) );
	}

	/**
	 * Return Flickr NSID by user name
	 *
	 * @param string $username Flickr user name
	 *
	 * @return string
	 */
	private function get_user_id( $username = '' ) {
		if ( '' === $username ) {
			return '';
		}

		$slug = $this->get_unique_slug( $username );
		$transient_user_id = "{$this->transient_user_id}_{$slug}";

		$user_id = get_transient( $transient_user_id );
		if ( false === $user_id ) {

			$args = array(
				'method'   => 'flickr.people.findByUsername',
				'username' => $username
			);

			$response = $this->request( $args );

			if ( 'fail' === $response['stat'] ) {
				return array();
			}

			if ( 'ok' === $response['stat'] ) {
				$user_id  = $response['user']['nsid'];
				set_transient( $transient_user_id, $user_id, HOUR_IN_SECONDS );
			}
		}

		return $user_id;
	}

	/**
	 * Request Flickr endpoint
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	private function request( array $args ) {
		if ( '' === $this->api_key ) {
			return array( 'stat' => 'fail' );
		}

		$defaults = array(
			'api_key'        => $this->api_key,
			'format'         => $this->format,
			'nojsoncallback' => 1
		);

		$uri = http_build_query( wp_parse_args( $args, $defaults ) );
		$url = "{$this->endpoint}?{$uri}";

		$request = wp_remote_get( $url, array( 'httpversion' => '1.1' ) );
		if ( is_wp_error( $request ) ) {
			return array( 'stat' => 'fail' );
		}

		$response = json_decode( wp_remote_retrieve_body( $request ), true );

		return $response;
	}

	/**
	 * Return unique slug for transient (8 characters)
	 *
	 * @param string $slug Any custom string for slug
	 *
	 * @return string
	 */
	private function get_unique_slug( $slug ) {
		return substr( md5( $slug ), 0, 8 );
	}
}

/**
 * "Recent Posts" widget class.
 *
 * @since 1.0.0
 */
class Appica_Widget_Recent_Posts extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_recent_entries',
			'description' => __( 'Your sites most recent Posts.', 'appica' )
		);
		parent::__construct( 'appica-recent-posts', __( 'Appica Recent Posts', 'appica' ), $widget_ops );

		$this->alt_option_name = 'widget_recent_entries';
	}

	public function widget( $args, $instance ) {
		$default = array(
			'title'   => '',
			'number'  => 4,
			'show_ex' => 1
		);
		$instance = wp_parse_args( (array) $instance, $default );

		$title   = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$show_ex = (bool) $instance['show_ex'];
		$number  = absint( $instance['number'] );

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 1.0.0
		 * @see   WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$query = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $number,
			'post_status'         => 'publish',
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true
		) ) );

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'], $title, $args['after_title'];
		}

		if ( $query->have_posts() ) {

			/**
			 * Add the temporary filter to change the excerpt length.
			 * Not need in other places, so filter will be removed after WP_Query Loop.
			 *
			 * @since 1.0.0
			 */
			add_filter( 'excerpt_length', 'appica_widget_excerpt_length', 999 );
			/**
			 * Temporary filter to trim the length of excerpt, if custom excerpt specified.
			 * Remove after WP_Query Loop
			 *
			 * @since 1.0.0
			 */
			add_filter( 'wp_trim_excerpt', 'appica_widget_trim_excerpt' );

			/**
			 * @var array Allowed overlay values
			 */
			$overlays = array( 'primary', 'success', 'info', 'warning', 'danger' );

			while ( $query->have_posts() ) {
				$query->the_post();

				// Post overlay
				$overlay  = '';
				$settings = get_post_meta( get_the_ID(), '_appica_post_settings', true );
				if ( is_array( $settings )
				     && array_key_exists( 'overlay', $settings )
				     && in_array( $settings['overlay'], $overlays, true )
				) {
					$overlay = $settings['overlay'];
					$overlay = "bg-{$overlay}";
				}

				// By default $style has empty value
				$style = '';
				if ( has_post_thumbnail() ) {
					$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'appica-wrp-thumbnail' );
					$style     = sprintf( 'style="background-image: url(%s);"', esc_url( $thumbnail[0] ) );
				}

				// 1 - permalink, 2 - overlay, 3 - style (optional) if featured image exists
				printf( '<a href="%1$s" class="featured-post %2$s" %3$s>', get_the_permalink(), $overlay, $style );
				?>
					<div class="content">
						<div class="arrow"><i class="flaticon-arrow413"></i></div>
						<?php the_title( '<h3>', '</h3>' ); if ( $show_ex ) : the_excerpt(); endif; ?>
					</div>
				</a><?php
			}

			wp_reset_postdata();

			// removes unnecessary filters
			remove_filter( 'excerpt_length', 'appica_widget_excerpt_length', 999 );
			remove_filter( 'wp_trim_excerpt', 'appica_widget_trim_excerpt' );

		} // end WP_Query::have_posts()

		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']   = strip_tags( trim( $new_instance['title'] ) );
		$instance['number']  = absint( $new_instance['number'] );
		$instance['show_ex'] = ( ! empty( $new_instance['show_ex'] ) ) ? 1 : 0;

		return $instance;
	}

	public function form( $instance ) {
		$default = array(
			'title'   => '',
			'number'  => 4,
			'show_ex' => 1
		);
		$instance = wp_parse_args( (array) $instance, $default );

		$title   = esc_attr( $instance['title'] );
		$number  = absint( $instance['number'] );
		$show_ex = (bool) $instance['show_ex'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'appica' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show', 'appica' ); ?></label>
			<input type="text" size="3" id="<?php echo $this->get_field_id( 'number' ); ?>"
			       name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $number; ?>">
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_ex ); ?>
			       id="<?php echo $this->get_field_id( 'show_ex' ); ?>"
			       name="<?php echo $this->get_field_name( 'show_ex' ); ?>">
			<label for="<?php echo $this->get_field_id( 'show_ex' ); ?>"><?php _e( 'Display post excerpt?', 'appica' ); ?></label>
		</p>
		<?php
	}
}

/**
 * "Categories" widget class
 *
 * @since 1.0.0
 */
class Appica_Widget_Categories extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_categories',
			'description' => __( 'A list of categories.', 'appica' )
		);
		parent::__construct( 'appica-categories', __( 'Appica Categories', 'appica' ), $widget_ops );
	}

	public function widget( $args, $instance ) {
		// Empty title by default
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		// Default values
		$default = array(
			'title'        => '',
			'count'        => 0,
			'home_url'     => 1
		);
		$instance = wp_parse_args( (array) $instance, $default );

		$c = (int) $instance['count'];
		$u = (int) $instance['home_url'];

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'], $title, $args['after_title'];
		}

		$cat_args = array(
			'title_li'   => '',
			'orderby'    => 'name',
			'show_count' => $c
		);

		// Home URL.
		if ( $u ) {
			// If static page used, get the permalink to Home page
			if ( 'page' === get_option( 'show_on_front') ) {
				$url = esc_url( get_permalink( get_option( 'page_for_posts' ) ) );
			} else {
				$url = esc_url( home_url( '/' ) );
			}

			/**
			 * Filter the site's blog Home page URL.
			 * Link must lead to blog index, not front page, if static one used.
			 *
			 * @since 1.0.0
			 *
			 * @param string $url Link to Home page URL
			 */
			$url = apply_filters( 'appica_widget_categories_home_url', $url );
		}

		$home_class = ( $u ) ? 'with-grid-btn' : '';
		?>
		<div class="categories <?php echo $home_class; ?>">
			<?php if ( $u ) : ?>
				<a href="<?php echo $url; ?>" class="grid-btn">
					<span></span>
					<span></span>
				</a>
			<?php endif; ?>
			<ul>
			<?php
			/**
			 * Filter the arguments for the "Categories" widget.
			 *
			 * @since 1.0.0
			 *
			 * @param array $cat_args An array of "Categories" widget options.
			 */
			wp_list_categories( apply_filters( 'widget_categories_args', $cat_args ) );
			?>
			</ul>
		</div>
		<?php

		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance  = $old_instance;

		$instance['title']    = strip_tags( trim( $new_instance['title'] ) );
		$instance['count']    = ! empty( $new_instance['count'] ) ? 1 : 0;
		$instance['home_url'] = ! empty( $new_instance['home_url'] ) ? 1 : 0;

		return $instance;
	}

	public function form( $instance ) {
		$default  = array(
			'title'    => '',
			'count'    => 0,
			'home_url' => 1,
		);
		$instance = wp_parse_args( (array) $instance, $default );

		$title    = esc_attr( $instance['title'] );
		$count    = (bool) $instance['count'];
		$home_url = (bool) $instance['home_url'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'appica' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>">
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'count' ); ?>"
			       name="<?php echo $this->get_field_name( 'count' ); ?>" <?php checked( $count ); ?>>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Show post counts', 'appica' ); ?></label>

			<br>

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'home_url' ); ?>"
		           name="<?php echo $this->get_field_name( 'home_url' ); ?>"<?php checked( $home_url ); ?>>
			<label for="<?php echo $this->get_field_id( 'home_url' ); ?>"><?php _e( 'Display link to Home', 'appica' ); ?></label>
		</p>
		<?php
	}
}

/**
 * "Twitter Feed" widget
 *
 * @since 1.0.0
 */
class Appica_Widget_Twitter_Feed extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_twitter_feed',
			'description' => __( 'Tweets timeline feed', 'appica' )
		);
		parent::__construct( 'appica-twitter-feed', __( 'Appica Twitter Feed', 'appica' ), $widget_ops );
	}

	public function widget( $args, $instance ) {
		$default = array(
			'title'     => '',
			'number'    => 4,
			'is_follow' => 1,
			'follow_us' => __( 'Follow us on Twitter', 'appica' )
		);
		$instance = wp_parse_args( (array) $instance, $default );

		$title     = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$number    = absint( $instance['number'] );
		$is_follow = (bool) $instance['is_follow'];
		$follow_us = esc_attr( $instance['follow_us'] );

		// Get instance of Appica_Twitter class
		$twitter = new Appica_Twitter( $instance );
		// Get tweets
		$tweets = $twitter->get_tweets( $number );

		// Start the widget output
		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'], $title, $args['after_title'];
		}

		?><div class="twitter-feed"><?php
			foreach( $tweets as $tweet ) :
				echo $twitter->display_tweet( $tweet );
			endforeach;
			unset( $tweet );

			if ( $is_follow ) :
				$screen_name = $twitter->get_screen_name();
				printf(
					'<a href="%1$s" class="link text-smaller text-uppercase" target="_blank">%2$s</a>',
					esc_url( "https://twitter.com/{$screen_name}" ), $follow_us
				);
			endif;
		?></div><?php

		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']     = strip_tags( trim( $new_instance['title'] ) );
		$instance['number']    = absint( $new_instance['number'] );
		$instance['is_follow'] = ! empty( $new_instance['is_follow'] ) ? 1 : 0;
		$instance['follow_us'] = sanitize_text_field( $new_instance['follow_us'] );

		return $instance;
	}

	public function form( $instance ) {
		$default  = array(
			'title'     => '',
			'number'    => 4,
			'is_follow' => 1,
			'follow_us' => __( 'Follow us on Twitter', 'appica' )
		);
		$instance = wp_parse_args( (array) $instance, $default );

		$title     = esc_attr( $instance['title'] );
		$number    = absint( $instance['number'] );
		$is_follow = (bool) $instance['is_follow'];
		$follow_us = esc_attr( $instance['follow_us'] );
		?>
		<p><?php _e( 'To make this widget works do not forget to fill Twitter credentials in global settings', 'appica' ); ?></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'appica' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of tweets to show', 'appica' ); ?></label>
			<input type="number" min="1" size="3" id="<?php echo $this->get_field_id( 'number' ); ?>"
			       name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $number; ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'is_follow' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $is_follow ); ?>
				       id="<?php echo $this->get_field_id( 'is_follow' ); ?>"
				       name="<?php echo $this->get_field_name( 'is_follow' ); ?>">
				<?php _e( 'Display follow link?', 'appica' ); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'follow_us' ); ?>"><?php _e( 'Follow us text', 'appica' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'follow_us' ); ?>"
			       name="<?php echo $this->get_field_name( 'follow_us' ); ?>" value="<?php echo $follow_us; ?>">
		</p>
		<?php
	}
}

/**
 * "Twitter/Blog Feed" widget
 *
 * @since 1.0.0
 */
class Appica_Widget_Twitter_Blog_Feed extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_twitter_blog_feed',
			'description' => __( 'Combined Twitter & Blog feed with tabbed navigation', 'appica' )
		);
		parent::__construct( 'appica-twitter-blog-feed', __( 'Appica Twitter/Blog Feed', 'appica' ), $widget_ops );
	}

	public function widget( $args, $instance ) {
		$default = array(
			'title'     => '',
			't_num'     => 4,
			'p_num'     => 4,
			'is_follow' => 1,
			'follow_us' => __( 'Follow us on Twitter', 'appica' ),
			'show_ex'   => 1
		);
		$instance = wp_parse_args( (array) $instance, $default );

		$title     = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$t_num     = absint( $instance['t_num'] );
		$p_num     = absint( $instance['t_num'] );
		$is_follow = (bool) $instance['is_follow'];
		$follow_us = esc_attr( $instance['follow_us'] );
		$show_ex   = (bool) $instance['show_ex'];

		// Get instance of Appica_Twitter class
		$twitter = new Appica_Twitter( $instance );

		// Prepare tweets
		$tweets = $twitter->get_tweets( $t_num );
		$is_tweets = ( count( $tweets ) > 0 );

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 1.0.0
		 * @see   WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$query = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $p_num,
			'post_status'         => 'publish',
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true
		) ) );

		// Start the widget output
		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'], $title, $args['after_title'];
		}
		?>
		<div class="offcanvas-tabs">
			<ul class="nav-tabs clearfix">
				<li class="active"><a href="#<?php echo $this->get_field_id( 'tab-twitter' ); ?>" data-toggle="tab">Twitter</a></li>
				<li><a href="#<?php echo $this->get_field_id( 'tab-blog' ); ?>" data-toggle="tab"><?php _e( 'Blog', 'appica' ); ?></a></li>
			</ul>
			<div class="tab-content">

				<div class="tab-pane fade in active" id="<?php echo $this->get_field_id( 'tab-twitter' ); ?>">
					<?php if ( $is_tweets ) : ?>
					<div class="twitter-feed">
						<?php
						foreach( $tweets as $tweet ) :
							echo $twitter->display_tweet( $tweet );
						endforeach;
						unset( $tweet );

						if ( $is_follow ) :
							$screen_name = $twitter->get_screen_name(); ?>
							<a href="<?php echo esc_url( "https://twitter.com/{$screen_name}" ); ?>"
							   class="link light-color text-smaller text-uppercase" target="_blank"><?php echo $follow_us; ?></a>
						<?php endif; ?>
					</div>
					<?php else :
						echo '<p class="no-entries">', __( 'No entries', 'appica' ), '</p>';
					endif; // end is_tweets check ?>
				</div>

				<div class="tab-pane fade" id="<?php echo $this->get_field_id( 'tab-blog' ); ?>">

					<?php if ( $query->have_posts() ) :

						/**
						 * Add the temporary filter to change the excerpt length.
						 * Not need in other places, so filter will be removed after WP_Query Loop.
						 *
						 * @since 1.0.0
						 */
						add_filter( 'excerpt_length', 'appica_widget_excerpt_length', 999 );
						/**
						 * Temporary filter to trim the length of excerpt, if custom excerpt specified.
						 * Remove after WP_Query Loop
						 *
						 * @since 1.0.0
						 */
						add_filter( 'wp_trim_excerpt', 'appica_widget_trim_excerpt' );

						?>
						<div class="offcanvas-posts">
						<?php while ( $query->have_posts() ) : $query->the_post(); ?>
							<a href="<?php the_permalink(); ?>" class="post">
								<?php the_title(); if( $show_ex ) : the_excerpt(); endif; ?>
							</a>
						<?php endwhile; wp_reset_postdata(); ?>
						</div>
						<?php

						remove_filter( 'excerpt_length', 'appica_widget_excerpt_length', 999 );
						remove_filter( 'wp_trim_excerpt', 'appica_widget_trim_excerpt' );

					else:
						echo '<p class="no-entries">', __( 'No entries', 'appica' ), '</p>';
					endif; // end have_posts() check ?>
				</div>

			</div>
		</div>
		<?php

		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']     = strip_tags( trim( $new_instance['title'] ) );
		$instance['t_num']     = absint( $new_instance['t_num'] );
		$instance['p_num']     = absint( $new_instance['p_num'] );
		$instance['is_follow'] = ( ! empty( $new_instance['is_follow'] ) ) ? 1 : 0;
		$instance['follow_us'] = sanitize_text_field( $new_instance['follow_us'] );
		$instance['show_ex']   = ( ! empty( $new_instance['show_ex'] ) ) ? 1 : 0;

		return $instance;
	}

	public function form( $instance ) {
		$default = array(
			'title'     => '',
			't_num'     => 4,
			'p_num'     => 4,
			'is_follow' => 1,
			'follow_us' => __( 'Follow us on Twitter', 'appica' ),
			'show_ex'   => 1
		);
		$instance = wp_parse_args( (array) $instance, $default );

		$title     = esc_attr( $instance['title'] );
		$t_num     = absint( $instance['t_num'] );
		$p_num     = absint( $instance['t_num'] );
		$is_follow = (bool) $instance['is_follow'];
		$follow_us = esc_attr( $instance['follow_us'] );
		$show_ex   = (bool) $instance['show_ex'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'appica' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>">
		</p>
		<p><?php _e( 'To make this widget works do not forget to fill Twitter credentials in global settings', 'appica' ); ?></p>
		<p>
			<label for="<?php echo $this->get_field_id( 't_num' ); ?>"><?php _e( 'Number of tweets to show', 'appica' ); ?></label>
			<input type="number" min="1" id="<?php echo $this->get_field_id( 't_num' ); ?>"
			       name="<?php echo $this->get_field_name( 't_num' ); ?>" value="<?php echo $t_num; ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'p_num' ); ?>"><?php _e( 'Number of posts to show', 'appica' ); ?></label>
			<input type="number" min="1" id="<?php echo $this->get_field_id( 'p_num' ); ?>"
			       name="<?php echo $this->get_field_name( 'p_num' ); ?>" value="<?php echo $p_num; ?>">
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $is_follow ); ?>
			       id="<?php echo $this->get_field_id( 'is_follow' ); ?>"
			       name="<?php echo $this->get_field_name( 'is_follow' ); ?>">
			<label for="<?php echo $this->get_field_id( 'is_follow' ); ?>"><?php _e( 'Display follow link?', 'appica' ); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'follow_us' ); ?>"><?php _e( 'Follow us text', 'appica' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'follow_us' ); ?>"
			       name="<?php echo $this->get_field_name( 'follow_us' ); ?>" value="<?php echo $follow_us; ?>">
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_ex ); ?>
			       id="<?php echo $this->get_field_id( 'show_ex' ); ?>"
			       name="<?php echo $this->get_field_name( 'show_ex' ); ?>">
			<label for="<?php echo $this->get_field_id( 'show_ex' ); ?>"><?php _e( 'Display post excerpt?', 'appica' ); ?></label>
		</p>
	<?php
	}
}

/**
 * Widget "Off-canvas Blog"
 *
 * Clone of "Recent Posts" widget, but styled for off-canvas navigation
 *
 * @since 1.0.0
 */
class Appica_Widget_Offcanvas_Blog extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_offcanvas_blog',
			'description' => __( 'Your sites most recent Posts for Off-Canvas navigation.', 'appica' )
		);
		parent::__construct( 'appica-offcanvas-blog', __( 'Appica Off-canvas Blog', 'appica' ), $widget_ops );
	}

	public function widget( $args, $instance ) {
		$default = array(
			'title'   => '',
			'number'  => 4,
			'show_ex' => 1
		);
		$instance = wp_parse_args( (array) $instance, $default );

		$title   = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$show_ex = (bool) $instance['show_ex'];
		$number  = absint( $instance['number'] );

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 1.0.0
		 * @see   WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$query = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $number,
			'post_status'         => 'publish',
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true
		) ) );

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'], $title, $args['after_title'];
		}

		?><div class="offcanvas-tabs"><div class="offcanvas-posts"><?php
		if ( $query->have_posts() ) {

			/**
			 * Add the temporary filter to change the excerpt length.
			 * Not need in other places, so filter will be removed after WP_Query Loop.
			 *
			 * @since 1.0.0
			 */
			add_filter( 'excerpt_length', 'appica_widget_excerpt_length', 999 );
			/**
			 * Temporary filter to trim the length of excerpt, if custom excerpt specified.
			 * Remove after WP_Query Loop
			 *
			 * @since 1.0.0
			 */
			add_filter( 'wp_trim_excerpt', 'appica_widget_trim_excerpt' );

			while ( $query->have_posts() ) : $query->the_post(); ?>
				<a href="<?php the_permalink(); ?>" class="post">
					<?php the_title();
					if ( $show_ex ) : the_excerpt(); endif; ?>
				</a>
			<?php endwhile;
			wp_reset_postdata();

			remove_filter( 'excerpt_length', 'appica_widget_excerpt_length', 999 );
			remove_filter( 'wp_trim_excerpt', 'appica_widget_trim_excerpt' );

		} else {
			echo '<p>', __( 'No entries', 'appica' ), '</p>';
		}

		?></div></div><?php

		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']   = strip_tags( trim( $new_instance['title'] ) );
		$instance['number']  = absint( $new_instance['number'] );
		$instance['show_ex'] = ( ! empty( $new_instance['show_ex'] ) ) ? 1 : 0;

		return $instance;
	}

	public function form( $instance ) {
		$default = array(
			'title'   => '',
			'number'  => 4,
			'show_ex' => 1
		);
		$instance = wp_parse_args( (array) $instance, $default );

		$title   = esc_attr( $instance['title'] );
		$number  = absint( $instance['number'] );
		$show_ex = (bool) $instance['show_ex'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'appica' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show', 'appica' ); ?></label>
			<input type="number" min="1" id="<?php echo $this->get_field_id( 'number' ); ?>"
			       name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $number; ?>">
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_ex ); ?>
			       id="<?php echo $this->get_field_id( 'show_ex' ); ?>"
			       name="<?php echo $this->get_field_name( 'show_ex' ); ?>">
			<label for="<?php echo $this->get_field_id( 'show_ex' ); ?>"><?php _e( 'Display post excerpt?', 'appica' ); ?></label>
		</p>
		<?php
	}
}

/**
 * Widget "Flickr Feed"
 *
 * @since 1.0.0
 */
class Appica_Widget_Flickr_Feed extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_flickr_feed',
			'description' => __( 'Yours most recent photos from Flickr.', 'appica' )
		);
		parent::__construct( 'appica-flickr-feed', __( 'Appica Flickr Feed', 'appica' ), $widget_ops );
	}

	public function widget( $args, $instance ) {
		$default  = array(
			'title'     => '',
			'username'  => '',
			'tags'      => '',
			'per_page'  => 9,
			'is_follow' => 1,
			'follow_us' => __( 'Follow us on Flickr', 'appica' )
		);
		$instance = wp_parse_args( (array) $instance, $default );

		$title     = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$username  = esc_attr( $instance['username'] );
		$tags      = esc_attr( $instance['tags'] );
		$per_page  = absint( $instance['per_page'] );
		$is_follow = (bool) $instance['is_follow'];
		$follow_us = esc_attr( $instance['follow_us'] );

		$flickr = new Appica_Flickr();
		$photos = $flickr->get_photos( $username, $tags, $per_page );

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'], $title, $args['after_title'];
		}

		?><div class="offcanvas-instagram"><?php

		if ( 0 !== count( $photos ) ) {

			?><div class="instgr-row clearfix"><?php

			$i = 1;
			foreach ( (array) $photos as $photo ) :
				$href = $flickr->get_photo_href( $photo );
				$src  = $flickr->get_photo_src( $photo );
				// 1 - photo href, 2 - photo src, 3 - title
				printf( '<a href="%1$s" target="_blank"><img src="%2$s" alt="%3$s"></a>', $href, $src, $photo['title'] );

				// Each 3 photos wrap to row, but except last photo
				if ( 0 === $i % 3 && 0 !== $i % $per_page ) {
					?></div><div class="instgr-row clearfix"><?php
				}

				$i++;

			endforeach;
			unset( $photo, $i, $href, $src );

			?></div><?php

			// Show "Follow" text & link
			if ( $is_follow && '' !== $username ) :
				$_url = esc_url( "https://www.flickr.com/photos/{$username}/" );
				printf( '<a href="%1$s" class="link light-color text-smaller text-uppercase" target="_blank">%2$s</a>', $_url, $follow_us );
				unset( $_url );
			endif;

		} else {
			echo '<p class="no-entries">', __( 'No entries', 'appica' ), '</p>';
		}

		?></div><?php

		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']     = strip_tags( trim( $new_instance['title'] ) );
		$instance['username']  = sanitize_text_field( trim( $new_instance['username'] ) );
		$instance['tags']      = sanitize_text_field( trim( $new_instance['tags'] ) );
		$instance['per_page']  = absint( $new_instance['per_page'] );
		$instance['is_follow'] = ( ! empty( $new_instance['is_follow'] ) ) ? 1 : 0;
		$instance['follow_us'] = sanitize_text_field( $new_instance['follow_us'] );

		return $instance;
	}

	public function form( $instance ) {
		$default  = array(
			'title'     => '',
			'username'  => '',
			'tags'      => '',
			'per_page'  => 9,
			'is_follow' => 1,
			'follow_us' => __( 'Follow us on Flickr', 'appica' )
		);
		$instance = wp_parse_args( (array) $instance, $default );

		$title     = esc_attr( $instance['title'] );
		$username  = esc_attr( $instance['username'] );
		$tags      = esc_attr( $instance['tags'] );
		$per_page  = absint( $instance['per_page'] );
		$is_follow = (bool) $instance['is_follow'];
		$follow_us = esc_attr( $instance['follow_us'] );
		?><p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'appica' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>">
		</p>
		<p><?php _e( '', 'appica' ); ?></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e( 'Flickr Username', 'appica' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>"
			       name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $username; ?>">
		</p>
		<p class="description" style="padding: 0;"><?php _e( 'Your Flickr user name', 'appica' ); ?></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'tags' ); ?>"><?php _e( 'Flickr Tags', 'appica' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'tags' ); ?>"
			       name="<?php echo $this->get_field_name( 'tags' ); ?>" value="<?php echo $tags; ?>" />
		</p>
		<p class="description" style="padding: 0;"><?php _e( 'A comma-delimited list of tags. Photos with one or more of the tags listed will be returned. You can exclude results that match a term by prepending it with a "-" character.', 'appica' ); ?></p>
		<br>
		<p class="description" style="padding: 0;"><?php _e( 'To search photos globally by tag just not provide a Flickr name.', 'appica' ); ?></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'per_page' ); ?>"><?php _e( 'Number of photos to show', 'appica' ); ?></label>
			<input type="number" min="1" max="500" id="<?php echo $this->get_field_id( 'per_page' ); ?>"
			       name="<?php echo $this->get_field_name( 'per_page' ); ?>" value="<?php echo $per_page; ?>">
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $is_follow ); ?>
			       id="<?php echo $this->get_field_id( 'is_follow' ); ?>"
			       name="<?php echo $this->get_field_name( 'is_follow' ); ?>">
			<label for="<?php echo $this->get_field_id( 'is_follow' ); ?>"><?php _e( 'Display "follow us" link?', 'appica' ); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'follow_us' ); ?>"><?php _e( 'Follow us text', 'appica' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'follow_us' ); ?>"
			       name="<?php echo $this->get_field_name( 'follow_us' ); ?>" value="<?php echo $follow_us; ?>">
		</p><?php
	}
}