/**
 * Handles toggling the main navigation menu for small screens.
 *
 * Modified from _s theme: https://github.com/Automattic/_s/
 */
jQuery( document ).ready( function( $ ) {
	var $header = $( '#header' ),
	    timeout = false;

	$.fn.smallMenu = function() {
		$header.find( '#nav' ).removeClass( 'main-nav' ).addClass( 'main-small-nav' );
		$header.find( '#nav h1' ).removeClass( 'assistive-text' ).addClass( 'menu-toggle' );

		$( '.menu-toggle' ).unbind( 'click' ).click( function() {
			$header.find( '.menu, #searchform' ).toggle();
			$( this ).toggleClass( 'toggled-on' );
		} );
	};

	// Check viewport width on first load.
	if ( $( window ).width() < 600 )
		$.fn.smallMenu();

	// Check viewport width when user resizes the browser window.
	$( window ).resize( function() {
		var browserWidth = $( window ).width();

		if ( false !== timeout )
			clearTimeout( timeout );

		timeout = setTimeout( function() {
			if ( browserWidth < 600 ) {
				$.fn.smallMenu();
			} else {
				$header.find( '#nav' ).removeClass( 'main-small-nav' ).addClass( 'main-nav' );
				$header.find( '#nav h1' ).removeClass( 'menu-toggle' ).addClass( 'assistive-text' );
				$header.find( '.menu, #searchform' ).removeAttr( 'style' );
			}
		}, 200 );
	} );
} );
