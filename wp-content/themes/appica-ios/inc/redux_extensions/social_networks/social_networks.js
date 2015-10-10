(function ( $ ) {
	"use strict";

	/**
	 * Clone social networks field.
	 */
	$( document ).on( 'click', '.pios-add-social-network', function ( e ) {
		e.preventDefault();
		var self = $( this );

		// Detect the container, based on clicked button / may be some buttons per one page
		var container = self.siblings( '.pios-social-networks-wrap' );
		// Clone first element
		var item = container.find( '.pios-social-group' ).first().clone();
		// Append this item to container..
		item.appendTo( container );
		// ..and reset the <input> and <select> field
		item.children( 'input:text' ).val( '' );
		item.children( 'select' ).find('option:eq(0)').prop('selected', true);
	} );

})( jQuery );