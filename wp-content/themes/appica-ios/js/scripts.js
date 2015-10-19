/*
 * Appica 2 HTML5 Theme v1.0
 * Copyright 2015 8Guild.com
 * All scripts for iOS Version
 */

(function ( $ ) {
	'use strict';

	$( document ).ready( function () {

		/* Disable default link behavior for dummy links that have href='#'
		 *********************************************************************/
		$('a[href=#]').click(function(e){
			e.preventDefault();
		});


		// Cashing variables
		var intro = $( '.intro' );
		var content = $( '.content-wrap' );
		var footer = $( '.footer' );


		// Checking with Modernizr if it is touch device
		// Touch Devices
		if ( Modernizr.touch ) {

			// Window load event
			$( window ).load( function () {
				/** Keeping Logo and Phone at the bottom of teh page
				 **********************************************************/
				$( '.intro .column-wrap' ).css( 'height', $( window ).height() );
			} );

			// Window resize event
			$( window ).resize( function () {
				/** Keeping Logo and Phone at the bottom of teh page
				 **********************************************************/
				$( '.intro .column-wrap' ).css( 'height', $( window ).height() );
			} );

			/** Sticky Navbar
			 *******************************************/
			$( window ).on( 'load', function () {
				if ( $( '.navbar-sticky' ).length > 0 ) {
					var sticky = new Waypoint.Sticky( {
						element: $( '.navbar-sticky' )[ 0 ]
					} );
				}
			} );

			// No-touch Devices
		} else {

			// Window load event
			$( window ).load( function () {

				/** Keeping Logo and Phone at the bottom of teh page
				 **********************************************************/
				$( '.intro .column-wrap' ).css( 'height', $( window ).height() );

				/** Pushing Content down to the height equal to Intro height + height necessary to finish animation
				 ******************************************************************************************************/
				if ( intro.length > 0 ) {
					content.css( 'margin-top', intro.height() * 1.5 );
				}

				/** Content 'margin-bottom' equals footer height to reveal footer
				 ********************************************************************************/
				if ( $( '.fixed-footer' ).length > 0 ) {
					content.css( 'margin-bottom', footer.outerHeight() );
				}

				// Animation delay for intro features
				$( '.intro-features .icon-block' ).each( function () {
					var transDelay = $( this ).data( 'transition-delay' );
					$( this ).css( { 'transition-delay': transDelay + 'ms' } );
				} );

				// Detecting various OS / devices / browsers and adding classes to <html>
				Detectizr.detect( { detectScreen: false } );
			} );

			// Window resize event
			$( window ).resize( function () {

				/** Keeping Logo and Phone at the bottom of teh page
				 **********************************************************/
				$( '.intro .column-wrap' ).css( 'height', $( window ).height() );

				/** Pushing Content down to the height equal to Intro section height + height necessary to finish animation
				 *************************************************************************************************************/
				if ( intro.length > 0 ) {
					content.css( 'margin-top', intro.height() * 1.5 );
				}

				/** Content 'margin-bottom' equals footer height to reveal footer
				 ********************************************************************************/
				if ( $( '.fixed-footer' ).length > 0 ) {
					content.css( 'margin-bottom', footer.outerHeight() );
				}
			} );


			/** Animating Intro Section
			 *********************************/
			$( window ).scroll( function () {
				if ( $( window ).scrollTop() > 15 ) {
					intro.addClass( 'transformed' );
				} else {
					intro.removeClass( 'transformed' );
				}
			} );


			/** Sticky Navbar and Footer
			 *******************************************/
			$( window ).on( 'load', function () {
				if ( $( '.navbar-sticky' ).length > 0 ) {
					var waypoint = new Waypoint.Sticky( {
						element: $( '.navbar-sticky' )[ 0 ],
						handler: function ( direction ) {
							if ( direction == 'down' ) {
								footer.addClass( 'footer-fixed-bottom' );
								intro.addClass( 'transparent' );
							} else {
								footer.removeClass( 'footer-fixed-bottom' );
								intro.removeClass( 'transparent' );
							}
						}
					} );
				}
			} );

		} // Modernizr End


		/** Off-Canvas Navigation Open/Close
		 *******************************************/
		var openOffcanvas = $( '[data-offcanvas="open"]' );
		var closeOffcanvas = $( '[data-offcanvas="close"]' );
		var offcanvasNav = $( '.offcanvas-nav' );
		openOffcanvas.click( function () {
			openOffcanvas.addClass( 'nav-open' );
			offcanvasNav.addClass( 'open' );
			$( 'body' ).append( '<div class="offcanvas-backdrop"></div>' );
		} );
		closeOffcanvas.click( function () {
			openOffcanvas.removeClass( 'nav-open' );
			offcanvasNav.removeClass( 'open' );
			$( '.offcanvas-backdrop' ).remove();
		} );
		$( document ).on( 'click', '.offcanvas-backdrop', function () {
			openOffcanvas.removeClass( 'nav-open' );
			offcanvasNav.removeClass( 'open' );
			$( '.offcanvas-backdrop' ).remove();
		} );

		/** Searchbox Expand
		 *********************************************************/
		var searchBox = $( '.search-box' );
		var searchInput = $( '#search-field' );
		$( 'body' ).on( 'click', function () {
			if ( searchInput.val() == '' ) {
				searchBox.removeClass( 'open' );
			} else {
				// Do nothing for now
			}
		} );
		searchBox.click( function ( e ) {
			e.stopPropagation();
		} );
		$( '.search-box .search-toggle' ).click( function () {
			$( this ).parent().toggleClass( 'open' );
			setTimeout( function () {
				if ( $( '#search-field' ).length > 0 ) {
					$( '#search-field' ).focus();
				}
			}, 500 );
		} );

		/** Feature Tabs (Changing screens of Tablet and Phone)
		 *********************************************************/
		$( '.feature-tabs .nav-tabs li a' ).on( 'click', function () {
			var currentPhoneSlide = $( this ).data( "phone" );
			var currentTabletSlide = $( this ).data( "tablet" );
			$( ".devices .phone .screens li" ).removeClass( "active" );
			$( ".devices .tablet .screens li" ).removeClass( "active" );
			$( currentPhoneSlide ).addClass( "active" );
			$( currentTabletSlide ).addClass( "active" );
		} );

		/** Feature Tabs Autoswitching
		 *********************************************************/
		if ( $( '.feature-tabs .nav-tabs[data-autoswitch="true"]' ).length > 0 ) {
			var changeInterval = $( '.feature-tabs .nav-tabs' ).data( 'interval' );
			var tabCarousel = setInterval( function () {
				var tabs = $( '.feature-tabs .nav-tabs > li' ),
					active = tabs.filter( '.active' ),
					next = active.next( 'li' ),
					toClick = next.length ? next.find( 'a' ) : tabs.eq( 0 ).find( 'a' );

				toClick.trigger( 'click' );
			}, changeInterval );
		}


		/** Form Validation
		 *********************************************************/
		if ( $( '#comment-form' ).length > 0 ) {
			$( '#comment-form' ).validate();
		}
		if ( $( '#form-demo' ).length > 0 ) {
			$( '#form-demo' ).validate();
		}
		if ( $( '#subscribe-form' ).length > 0 ) {
			$( '#subscribe-form' ).validate();
		}

		/** Custom Horizontal Scrollbar for Gallery/Blog (Home Page)
		 **************************************************************/
		$( window ).load( function () {
			$( '.scroller' ).mCustomScrollbar( {
				axis: "x",
				theme: "dark",
				scrollInertia: 300,
				advanced: { autoExpandHorizontalScroll: true }
			} );
		} );


		/** Custom Vertical Scrollbar for Off-Canvas Navigation
		 **************************************************************/
		var navBodyScroll = $( '.nav-body .overflow' );
		$( window ).load( function () {
			navBodyScroll.height( $( window ).height() - $( '.nav-head' ).height() - 80 );
			navBodyScroll.mCustomScrollbar( {
				theme: "dark",
				scrollInertia: 300,
				scrollbarPosition: "outside"
			} );
		} );
		$( window ).resize( function () {
			navBodyScroll.height( $( window ).height() - $( '.nav-head' ).height() - 80 );
		} );

		/** Portfolio Lightbox
		 *********************************************************/
		if($('.popup-img').length > 0) {
			$('.popup-img').magnificPopup({
				type:'image',
				gallery:{
					enabled:true
				},
				removalDelay: 300,
				mainClass: 'mfp-fade'
			});
		}

		/** Masony Grid (Isotope) + Filtering
		 *********************************************************/
		$( window ).load( function () {
			if ( $( '.masonry-grid' ).length > 0 ) {
				appica.masonry = $( '.masonry-grid' ).isotope( {
					itemSelector: '.item',
					masonry: {
						columnWidth: '.grid-sizer',
						gutter: '.gutter-sizer'
					}
				} );
			}
			if($('.portfolio-grid').length > 0) {
				appica.portfolio = $('.portfolio-grid').isotope({
					itemSelector: '.grid-item',
					masonry: {
						columnWidth: '.grid-sizer',
						gutter: '.gutter-sizer'
					}
				});
			}
			if ( $( '.filter-grid' ).length > 0 ) {
				var grid = $( '.filter-grid' );
				$( '.nav-filters' ).on( 'click', 'a', function ( e ) {
					e.preventDefault();
					$( '.nav-filters li' ).removeClass( 'active' );
					$( this ).parent().addClass( 'active' );
					var filterValue = $( this ).attr( 'data-filter' );
					grid.isotope( { filter: filterValue } );
				} );
			}
		} );

		/** Bar Charts
		 *********************************************************/
		$( window ).load( function () {
			$( '.bar-charts .chart' ).each( function () {
				var percentage = $( this ).data( 'percentage' );
				$( this ).find( '.bar' ).css( 'height', percentage + '%' );
			} );
		} );


		///////////////////////////////////////////////////////////////////////
		/////////  INTERNAL ANCHOR LINKS SCROLLING (NAVIGATION)  //////////////
		//////////////////////////////////////////////////////////////////////

		$( ".scroll" ).click( function ( event ) {
			event.preventDefault();
			//var $elemOffsetTop = $(this).data('offset-top');
			var el = $( this.hash );
			var is_sticky = ( $( '.navbar-sticky' ).length > 0 );
			var offset = ( is_sticky ) ? 94 : -3;


			if ( el.length > 0 ) {
				$( 'html' ).velocity( "scroll", {
					offset: el.offset().top - offset,
					duration: 1000,
					easing: 'easeOutExpo'
				} );
			}
		} );
		$( '.scrollup' ).click( function ( e ) {
			e.preventDefault();
			$( 'html' ).velocity( "scroll", { offset: 0, duration: 1400, mobileHA: false } );
		} );


		//SCROLL-SPY
		// Cache selectors
		var lastId,
			topMenu = $( ".scroll-nav" ),
			topMenuHeight = topMenu.outerHeight(), // All list items
			menuItems = topMenu.find( "a" ), // Anchors corresponding to menu items
			scrollItems = menuItems.map( function () {
				var item = $( $( this ).attr( "href" ) );
				if ( item.length ) {
					return item;
				}
			} );

		// Bind to scroll
		$( window ).scroll( function () {
			// Get container scroll position
			var fromTop = $( this ).scrollTop() + topMenuHeight + 200;

			// Get id of current scroll item
			var cur = scrollItems.map( function () {
				if ( $( this ).offset().top < fromTop )
					return this;
			} );
			// Get the id of the current element
			cur = cur[ cur.length - 1 ];
			var id = cur && cur.length ? cur[ 0 ].id : "";

			if ( lastId !== id ) {
				lastId = id;
				// Set/remove active class
				menuItems
					.parent().removeClass( "active" )
					.end().filter( "[href=#" + id + "]" ).parent().addClass( "active" );
			}
		} );

	} );

	/**
	 * Twitter share window
	 *
	 * @uses Twitter Web Intents
	 * @link https://dev.twitter.com/web/tweet-button/web-intent
	 */
	$( document ).on( 'click', '.appica-twitter-share', function ( e ) {
		e.preventDefault();
		var self = $( this ),
			query = {
				text: self.data( 'text' ),
				url: self.data( 'url' )
			};

		var uri = $.param( query );
		window.open( 'http://twitter.com/intent/tweet?' + uri, 'twitter', 'menubar=no,toolbar=no,resizable=yes,scrollbars=no,status=0,location=0,height=380,width=660' );
	} );

	/**
	 * Google+ share
	 *
	 * @link https://developers.google.com/+/web/share/#sharelink
	 */
	$( document ).on( 'click', '.appica-google-share', function ( e ) {
		e.preventDefault();
		var self = $( this ),
			query = { url: self.data( 'url' ) };

		var uri = $.param( query );
		window.open( 'https://plus.google.com/share?' + uri, 'googleplus', 'menubar=no,toolbar=no,resizable=yes,scrollbars=no,height=600,width=600' );
	} );

	/**
	 * Facebook share
	 *
	 * @link https://developers.google.com/+/web/share/#sharelink
	 */
	$( document ).on( 'click', '.appica-facebook-share', function ( e ) {
		e.preventDefault();
		var self = $( this ),
			query = { u: self.data( 'url' ) };

		var uri = $.param( query );
		window.open( 'https://www.facebook.com/sharer/sharer.php?' + uri, 'facebook', 'menubar=yes,toolbar=yes,resizable=yes,scrollbars=yes,height=600,width=600' );
	} );

	/**
	 * Pinterest share
	 */
	$( document ).on( 'click', '.appica-pinterest-share', function ( e ) {
		e.preventDefault();
		var self = $( this ),
			query = {
				url: self.data( 'url' ),
				media: self.data( 'media' ),
				description: self.data( 'description' )
			};

		var uri = $.param( query );
		window.open( 'https://pinterest.com/pin/create/button/?' + uri, 'pinterest', 'menubar=no,toolbar=no,resizable=yes,scrollbars=no,height=600,width=600' );
	} );


	/**
	 * Change the state of "Load More" button due some conditions.
	 *
	 * Button will be hidden if there are no records for loading.
	 * Or just update the text on the button with new number of entries that have to being loaded.
	 * This function can be used with multiple buttons with same structure and logic.
	 *
	 * @param {jQuery Object} button
	 * @param {int} total
	 * @param {int} page
	 * @param {int} perpage
	 *
	 * @returns {boolean}
	 */
	var updateMoreBtn = function ( button, total, page, perpage ) {
		var num = total - ( page * perpage );
		if ( num <= 0 || total <= perpage ) {
			button.hide();
			return false;
		}
		num = ( num > perpage ) ? perpage : num;

		// replace the text with new value
		var $count = button.find( '.count' );
		var countText = $count.text();
		$count.text( countText.replace( perpage, num ) );
	};

	/**
	 * Global callback function for handling AJAX errors
	 *
	 * @param xhr
	 * @param status
	 * @param error
	 */
	var onAjaxFail = function ( xhr, status, error ) {
		console.log( [ 'appica.ajax.error', status, error, xhr, xhr.responseText ] );
	};

	/**
	 * Convert loaded posts through AJAX from raw HTML to jQuery Object.
	 * Used for "Load More" and "Infinite Scroll" function.
	 *
	 * @param {Array} data Array of posts, raw HTML
	 * @returns {Array}
	 */
	var parsePosts = function( data ) {
		var posts = [];

		$.each( data, function ( index, post ) {
			var parsed = $.parseHTML( post );
			posts.push( parsed[0] );
		} );

		return posts;
	};

	/**
	 * Returns value of the parameter from serialized array.
	 *
	 * @param {string} search Query string param name
	 * @param {Array} array Serialized array
	 * @returns {string}
	 */
	var getParamFromSerializedArray = function ( search, array ) {
		var value = '';

		$.each( array, function ( key, param ) {
			if ( search == param.name ) {
				value = param.value;
				return false;
			}
		} );

		return value;
	};

	/**
	 * Load More Posts handler
	 */
	$( document ).on( 'click', '.load-more-posts', function ( e ) {
		e.preventDefault();

		// more is "Load More" button
		var more = $( this ),
			total = more.data( 'total' ),
			page = more.data( 'page' ),
			perpage = more.data( 'per-page' );

		var formdata = {
			action: 'appica_load_more_posts',
			nonce: appica.nonce,
			page: page
		};

		$.post( appica.ajaxurl, formdata ).fail( onAjaxFail ).done( function ( response ) {
			if ( true == response.success ) {
				// Update page
				more.data( 'page', page + 1 );
				// and button
				updateMoreBtn( more, total, page, perpage );
				// Some isotope magic: convert html string to jQuery Object
				var $posts = [];
				$.each( response.data, function ( index, post ) {
					var parsed = $.parseHTML( post );
					$posts.push( parsed[0] );
				} );

				appica.masonry.append( $posts ).isotope( 'appended', $posts );
				setTimeout( function () {
					appica.masonry.isotope( 'layout' );
				}, 100 );
			} else {
				alert( response.data );
			}
		} );
	} );

	/**
	 * Infinite Scroll
	 */
	$( window ).on( 'load', function () {
		var infiniteContainer = $( '#appica-infinite-scroll' );
		if ( infiniteContainer.length > 0 ) {

			// Load posts handler
			var waypointHandlerForInfiniteScroll = function(direction) {
				if ( 'up' === direction ) {
					return false;
				}

				var infinite = $( '#appica-infinite-scroll' ),
					maxPages = infinite.data( 'max-pages' ),
					page = infinite.data( 'page' );

				// Do not load posts, if no more pages
				if ( page > maxPages ) {
					return false;
				}

				var formdata = {
					action: 'appica_load_more_posts',
					nonce: appica.nonce,
					page: page
				};

				$.post( appica.ajaxurl, formdata ).fail( onAjaxFail ).done( function( response ) {
					if ( false === response.success ) {
						return false;
					}

					// Update page
					infinite.data( 'page', page + 1 );
					// Get posts objects
					var posts = parsePosts( response.data );
					// Update isotope
					appica.masonry.append( posts ).isotope( 'appended', posts );
					setTimeout( function () {
						appica.masonry.isotope( 'layout' );
					}, 100 );
					// refresh waypoint for allow further loading
					Waypoint.refreshAll();
				} );
			};

			// Init Waypoint
			$( '.masonry-grid' ).waypoint( waypointHandlerForInfiniteScroll, {
				offset: 'bottom-in-view'
			} );
		}
	} );

	/**
	 * AJAXify WP Comments
	 */
	$( document ).on( 'submit', '#comment-form', function( e ) {
		e.preventDefault();
		var form = $( this ),
			formurl = form.attr( 'action' ),
			formdata = form.serializeArray(),
			parent_id = parseInt( getParamFromSerializedArray( 'comment_parent', formdata ), 10 ),
			is_child = (0 !== parent_id ),
			order = getParamFromSerializedArray( 'comments_order', formdata );

		$.post( formurl, formdata ).fail( onAjaxFail ).done( function ( response ) {
			if ( false === response.success ) {
				console.log( [ 'appica.comment.response', response ] );
			}

			// Anyway increase number of comments on posting
			var cnh = $('#comments-count' ); // commentsNumHolder
			var ccn = parseInt( cnh.text().match( /(\d+)/ )[ 1 ], 10 ); // currentCommentsNum
			var ncn = cnh.text().replace( /(\d+)/, ccn + 1 ); // newCommentsNum
			cnh.text( ncn );

			// Clear comment field
			$( '[name=comment]' ).val( '' );

			var comment = response.data.comment;
			var comment_id = response.data.comment_id;
			// Child comment can only be appended..
			if ( is_child ) {
				$( '#comment-' + parent_id ).append( comment );
				// Click to "Cancel reply" to restore form
				$( '#cancel-comment-reply-link' ).click();
			} else {
				var comments = $( '#comments-list' );
				// ..but top-level comment can be appended or prepended to comment list, depending on settings
				('asc' == order) ? comments.append( comment ) : comments.prepend( comment );
			}

			// Scroll to comment
			var new_comment_selector = '#comment-' + comment_id;
			var new_comment_element = $( new_comment_selector );
			if ( new_comment_element.length > 0 ) {
				$( 'html' ).velocity( "scroll", {
					offset: $( new_comment_selector ).offset().top - 200,
					duration: 1000,
					easing: 'easeOutExpo'
				} );
			}
		} );
	} );

	/**
	 * Shortcode "vc_row" custom css to <head>
	 */
	$( document ).ready( function () {
		var $rows = $( '.fw-bg.overlay' );
		var style = [];
		if ( 0 === $rows.length ) {
			return;
		}

		$.each( $rows, function ( k, row ) {
			var $row = $( row );
			style.push( $row.data( 'overlay' ) );
		} );

		var css = style.join( "\n" );
		$( 'head' ).append( '<style type="text/css" class="appica-vc_row-overlay-styles">' + css + '</style>' );
	} );

	/**
	 * Shortcode "vc_tabs" fix for Appica.
	 * Add .active.in for each first .tab-pane inside .tab-content
	 */
	$( window ).on( 'load', function () {
		$( '.tab-content .tab-pane:first-child' ).each( function( key, tab ) {
			var tabEl = $( tab );
			var is_active = tabEl.hasClass( 'active' );
			if ( false === is_active ) {
				tabEl.addClass( 'active in' );
			}
		} );
	} );

	/**
	 * Disable Contact Form 7 autocomplete
	 */
	$( window ).on( 'load', function () {
		$( '.wpcf7-form' ).attr( 'autocomplete', 'off' ).attr( 'autosuggest', 'off' );
	} );

	/**
	 * Remove readonly attr on focus from sign-in form
	 */
	$( document ).ready( function () {
		$( '#si_email, #si-password' ).on( 'focus', function () {
			$( this ).removeAttr( 'readonly' );
		} );
	} );

})( jQuery );

