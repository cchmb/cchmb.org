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
      $header.find( '.menu, #searchform' ).hide();

      // nav menu for small screens
      $( '#menu-toggle' ).unbind( 'click' ).click( function(e) {
        e.preventDefault();
        $header.find( '.menu, #searchform' ).slideToggle();
        $( this ).toggleClass( 'expanded' );
      } );
    },

    reset: function() {
      $header.find( '.menu, #searchform' ).removeAttr( 'style' );
    },

    test: function() {
      return $header.find('.menu > li').first().css('float') == 'none';
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

  if (smallMenu.test()) {
    smallMenu.init();
  }

  // Check viewport width on first load.
  if ( $( window ).width() < 600 ) {
  } else {
    sermonNav.init();
  }

  // Check viewport width when user resizes the browser window.
  $( window ).resize( function() {
    var browserWidth = $( window ).width();

    if ( false !== timeout )
      clearTimeout( timeout );

    timeout = setTimeout( function() {
      if (smallMenu.test()) {
        smallMenu.init();
      } else {
        smallMenu.reset();
      }
      if ( browserWidth < 600 ) {
        sermonNav.reset();
      } else {
        sermonNav.init();
      }
    }, 200 );
  } );
} );
