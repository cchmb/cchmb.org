/**
 * Handles toggling the main navigation menu for small screens.
 *
 * Modified from _s theme: https://github.com/Automattic/_s/
 */
jQuery( document ).ready( function( $ ) {
  var $header = $( '#header' ),
      timeout = false;

  var smallMenu = {
    init: function() {
      // nav menu for small screens
      $header.find( '#nav' ).removeClass( 'main-nav' ).addClass( 'main-small-nav' );
      $header.find( '#nav h1' ).removeClass( 'assistive-text' ).addClass( 'menu-toggle' );

      $( '.menu-toggle' ).unbind( 'click' ).click( function() {
        $header.find( '.menu, #searchform' ).toggle();
        $( this ).toggleClass( 'toggled-on' );
      } );
    },
    reset: function() {
      $header.find( '#nav' ).removeClass( 'main-small-nav' ).addClass( 'main-nav' );
      $header.find( '#nav h1' ).removeClass( 'menu-toggle' ).addClass( 'assistive-text' );
      $header.find( '.menu, #searchform' ).removeAttr( 'style' );
    }
  };

  var sermonNav = {
    init: function() {
      if ( $('#sermon-data-tabs').length > 0 ) return;

      // sermon nav for large screens
      var tabs = jQuery('<ul>', {'id':'sermon-data-tabs'}).insertBefore('#sermon-data');
      $('#sermon-data > section').each(function(idx, section) {
        var title = $('> :header:first', section);
        if (title && section.id) {
          $('<a>', {'href': '#' + section.id, 'text':title.text()})
            .appendTo('<li>').appendTo(tabs);
        }
        title.addClass('assistive-text');
      });
      $('#sermon-data-tabs').tabs('#sermon-data > section', { history: true});
    },
    reset: function() {
      $('#sermon-data-tabs').remove();
      $('#sermon-data > section').show()
        .find('> :header:first').removeClass('assistive-text');
    }
  };

  // Check viewport width on first load.
  if ( $( window ).width() < 600 ) {
    //smallMenu.init();
  } else {
    sermonNav.init();
  }

  // Check viewport width when user resizes the browser window.
  $( window ).resize( function() {
    var browserWidth = $( window ).width();

    if ( false !== timeout )
      clearTimeout( timeout );

    timeout = setTimeout( function() {
      if ( browserWidth < 600 ) {
        //smallMenu.init();
        sermonNav.reset();
      } else {
        //smallMenu.reset();
        sermonNav.init();
      }
    }, 200 );
  } );
} );
