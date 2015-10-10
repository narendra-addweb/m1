(function($) {
	'use strict';

	$( document ).ready( function () {

		/** App Gallery
		 *********************************************************/
		if ( $( '.app-gallery a' ).length > 0 ) {
			$( '.app-gallery a' ).magnificPopup( {
				type: 'image',
				mainClass: 'mfp-with-zoom', // this class is for CSS animation below
				gallery: {
					enabled: true
				},
				zoom: {
					enabled: true, // By default it's false, so don't forget to enable it

					duration: 300, // duration of the effect, in milliseconds
					easing: 'ease-in-out', // CSS transition easing function

					// The "opener" function should return the element from which popup will be zoomed in
					// and to which popup will be scaled down
					// By defailt it looks for an image tag:
					opener: function ( openerElement ) {
						// openerElement is the element on which popup was initialized, in this case its <a> tag
						// you don't need to add "opener" option if this code matches your needs, it's defailt one.
						return openerElement.is( 'img' ) ? openerElement : openerElement.find( 'img' );
					}
				}

			} );
		}


		/** Video Popup
		 *********************************************************/
		if ( $( '.video-popup' ).length > 0 ) {
			$( '.video-popup' ).magnificPopup( {
				type: 'iframe',
				removalDelay: 300,

				// Class that is added to popup wrapper and background
				// apply CSS animations just to this exact popup
				mainClass: 'mfp-fade'
			} );
		}


		/** Gallery
		 *********************************************************/
		// Images
		if ( $( '.gallery-item.image-item' ).length > 0 ) {
			$( '.gallery-item.image-item' ).magnificPopup( {
				type: 'image',
				gallery: {
					enabled: true
				},
				removalDelay: 300,
				mainClass: 'mfp-fade'
			} );
		}

		// Video
		if ( $( '.gallery-item.video-item' ).length > 0 ) {
			$( '.gallery-item.video-item' ).magnificPopup( {
				type: 'iframe',
				removalDelay: 300,
				mainClass: 'mfp-fade'
			} );
		}

		/**
		 * Pricing Plan switcher
		 */
		$( document ).on( 'ifClicked', '[name=plan]', function ( e ) {
			// get current plan
			var self = $( this ),
				term = self.data( 'term' );

			$( '.pricing-plan-title' ).each( function ( k, plan ) {
				var priceEl = $( plan ).find( '.price' );
				var periodEl = $( plan ).find( '.period' );
				var newPrice = priceEl.data( term );

				// Always change periods
				periodEl.text( '/ ' + term );

				// but if price not set - skip
				if ( 0 === newPrice.length ) {
					return;
				}

				priceEl.text( newPrice );
			} );
		} );

		/**
		 * Global callback function for handling AJAX errors
		 *
		 * @param xhr
		 * @param status
		 * @param error
		 *
		 * @since 1.3.0 added to Appica Core
		 */
		var onAjaxFail = function ( xhr, status, error ) {
			console.log( [ 'appica.core.ajax.error', status, error, xhr, xhr.responseText ] );
		};

		$( document ).on( 'click', '.appica-load-more-portfolio', function ( e ) {
			e.preventDefault();

			var button = $( this ),
				posts = button.data( 'posts' );

			var formdata = {
				action: 'appica_load_more_portfolio',
				nonce: appica.nonce,
				posts: posts
			};

			// Add loading class to button
			button.addClass( 'btn-loading' );

			$.post( appica.ajaxurl, formdata ).fail( onAjaxFail ).done( function( response ) {
				if ( true === response.success ) {
					// Some isotope magic: convert html string to jQuery Object
					var $posts = [];
					$.each( response.data, function ( index, post ) {
						var parsed = $.parseHTML( post );
						$posts.push( parsed[0] );
					} );

					// Update isotope
					appica.portfolio.append( $posts ).isotope( 'appended', $posts );

					// Re-init magnificPopup
					$('.popup-img').magnificPopup({
						type:'image',
						gallery:{
							enabled:true
						},
						removalDelay: 300,
						mainClass: 'mfp-fade'
					});

					// Remove button
					button.parent( '.text-center' ).remove();
				}
			} );
		} );

		/*
		 * Google Maps
		 */

		/**
		 * Set up marker on Google Map
		 *
		 * @param {object} geocoder Google Maps geocoder
		 * @param {object} map Google Map
		 * @param {string} title Marker title
		 * @param {string} address Marker address name
		 * @param {string} icon Marker image
		 */
		var gmSetupMarker = function ( geocoder, map, title, address, icon ) {
			geocoder.geocode( { address: address }, function ( results, status ) {
				if ( status == google.maps.GeocoderStatus.OK ) {
					map.setCenter( results[ 0 ].geometry.location );
					var marker = new google.maps.Marker( {
						map: map,
						position: results[ 0 ].geometry.location,
						icon: icon,
						title: title
					} );
				} else {
					alert( 'Geocode was not successful for the following reason: ' + status );
				}
			} );
		};

		/**
		 * Initialize Google Map
		 */
		var gmInit = function () {
			/**
			 * Google Map selector
			 * @type {jQuery Object}
			 */
			var gm = $( '.google-map' );
			/**
			 * User defined options
			 * @type {{title: *, location: *, icon: *, isZoom: boolean, zoom: *, isScroll: boolean}}
			 */
			var userOptions = {
				title: gm.data( 'title' ),
				location: gm.data( 'location' ),
				icon: gm.data( 'icon' ),
				isZoom: ( 1 === parseInt( gm.data( 'is-zoom' ), 10 ) ),
				zoom: gm.data( 'zoom' ),
				isScroll: ( 1 === parseInt( gm.data( 'is-scroll' ), 10 ) )
			};

			/**
			 * Default Google Maps styles, if custom not used
			 * @type {*[]}
			 */
			var gmDefaultStyles = [
				{
					"featureType": "all",
					"elementType": "all",
					"stylers": [ { "color": "#ffffff" }, { "visibility": "off" } ]
				},
				{
					"featureType": "all",
					"elementType": "geometry",
					"stylers": [ { "visibility": "off" } ]
				},
				{
					"featureType": "all",
					"elementType": "geometry.fill",
					"stylers": [ { "color": "#ffffff" }, { "visibility": "on" } ]
				},
				{
					"featureType": "all",
					"elementType": "geometry.stroke",
					"stylers": [ { "visibility": "off" } ]
				},
				{
					"featureType": "all",
					"elementType": "labels.text",
					"stylers": [ { "color": "#6300ff" }, { "visibility": "off" } ]
				},
				{
					"featureType": "administrative",
					"elementType": "all",
					"stylers": [ { "weight": "0.01" }, { "visibility": "off" } ]
				},
				{
					"featureType": "administrative.province",
					"elementType": "all",
					"stylers": [ { "visibility": "off" } ]
				},
				{
					"featureType": "administrative.province",
					"elementType": "geometry.fill",
					"stylers": [ { "visibility": "on" } ]
				},
				{
					"featureType": "administrative.province",
					"elementType": "labels.text.fill",
					"stylers": [ { "visibility": "on" }, { "color": "#ffffff" } ]
				},
				{
					"featureType": "administrative.locality",
					"elementType": "all",
					"stylers": [ { "visibility": "simplified" }, { "color": "#aa7cff" } ]
				},
				{
					"featureType": "landscape",
					"elementType": "all",
					"stylers": [ { "color": "#3a3a3a" }, { "visibility": "on" } ]
				},
				{
					"featureType": "landscape.natural.landcover",
					"elementType": "all",
					"stylers": [ { "color": "#c96363" }, { "visibility": "off" } ]
				},
				{
					"featureType": "landscape.natural.terrain",
					"elementType": "all",
					"stylers": [ { "color": "#533434" }, { "visibility": "off" } ]
				},
				{
					"featureType": "poi",
					"elementType": "all",
					"stylers": [ { "visibility": "on" }, { "color": "#3a3a3a" } ]
				},
				{
					"featureType": "poi",
					"elementType": "labels.text",
					"stylers": [ { "visibility": "off" } ]
				},
				{
					"featureType": "poi.park",
					"elementType": "all",
					"stylers": [ { "visibility": "on" } ]
				},
				{
					"featureType": "poi.park",
					"elementType": "geometry",
					"stylers": [ { "color": "#4cd964" } ]
				},
				{
					"featureType": "poi.park",
					"elementType": "labels.text",
					"stylers": [ { "visibility": "off" } ]
				},
				{
					"featureType": "road",
					"elementType": "all",
					"stylers": [ { "visibility": "simplified" }, { "weight": "0.5" } ]
				},
				{
					"featureType": "road",
					"elementType": "geometry.fill",
					"stylers": [ { "visibility": "on" }, { "color": "#464646" } ]
				},
				{
					"featureType": "road",
					"elementType": "geometry.stroke",
					"stylers": [ { "visibility": "off" } ]
				},
				{
					"featureType": "road",
					"elementType": "labels.text",
					"stylers": [ { "visibility": "off" }, { "weight": "0.01" } ]
				},
				{
					"featureType": "road.highway",
					"elementType": "all",
					"stylers": [ { "visibility": "on" } ]
				},
				{
					"featureType": "road.highway",
					"elementType": "geometry.fill",
					"stylers": [ { "weight": "2" }, { "color": "#ff9500" }, { "invert_lightness": true } ]
				},
				{
					"featureType": "road.highway",
					"elementType": "geometry.stroke",
					"stylers": [ { "visibility": "off" } ]
				},
				{
					"featureType": "road.highway",
					"elementType": "labels.text",
					"stylers": [ { "visibility": "off" } ]
				},
				{
					"featureType": "road.highway",
					"elementType": "labels.text.fill",
					"stylers": [ { "color": "#ff0000" }, { "visibility": "off" } ]
				},
				{
					"featureType": "road.highway",
					"elementType": "labels.icon",
					"stylers": [ { "visibility": "off" } ]
				},
				{
					"featureType": "water",
					"elementType": "all",
					"stylers": [ { "visibility": "on" }, { "color": "#ffffff" } ]
				},
				{
					"featureType": "water",
					"elementType": "geometry.stroke",
					"stylers": [ { "color": "#652323" }, { "visibility": "off" } ]
				}
			];

			// Init the geocoder
			var geocoder = new google.maps.Geocoder();
			var latlng = new google.maps.LatLng( -34.397, 150.644 );
			var mapOptions = {
				center: latlng,
				zoomControl: userOptions.isZoom,
				zoom: userOptions.zoom,
				scrollwheel: userOptions.isScroll,
				disableDefaultUI: true,
				styles: ( 'undefined' === typeof appica_gm_custom_style || appica_gm_custom_style.length === 0 ) ? gmDefaultStyles : appica_gm_custom_style
			};

			var map = new google.maps.Map( document.getElementById( 'map-canvas' ), mapOptions );

			// Set marker
			gmSetupMarker( geocoder, map, userOptions.title, userOptions.location, userOptions.icon );
		};

		if ( $( '#map-canvas' ).length > 0 ) {
			google.maps.event.addDomListener( window, 'load', gmInit );
		}

	} );
})( jQuery );