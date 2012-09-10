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
      $('#menu-toggle').removeClass( 'expanded' );

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


  /**
   * Attempt at responsive media for the entry header image.  For smaller
   * screens, the small image (indicated by the 'data-image-small' attribute)
   * is loaded.  On browser resizes, we switch out to the larger image
   * (indicated by the 'data-image-large' attribute) if the screen is large
   * enough.  We never switch back down to the smaller image though, since at
   * that point, the bandwidth has already been spent to load the larger image.
   */
  var loadHeaderImage = function() {
    // display header images
    $('header[data-image-small]').each(function(index, header) {
      var imageSize = $(window).width() < 600 ?  'image-small' : 'image-large';
      var imageUrl = $(header).data(imageSize);

      var img = $(this).find('> img');
      if ( img.length == 0 ) {
        img = $('<img>').prependTo(header);
      } else if (imageSize == 'image-small') {
        // if we've already loaded a header image, there's no use 
        // switching it out with the smaller image at this point.
        return;
      }

      img.attr('src', imageUrl);
    });
  };


  var sermonNav = {
    init: function() {
      if ( $('#sermon-data-tabs').length > 0 ) return;

      // sermon nav for large screens
      var tabs = jQuery('<ul>', {'id':'sermon-data-tabs'}).insertBefore('#sermon-data');
      $('#sermon-data > section').each(function(idx, section) {
        var title = $('> :header:first', section);
        if (title && section.id) {
          var li = $('<li>').appendTo(tabs);
          $('<a>', {'href': '#' + section.id, 'text':title.text()}).appendTo(li);
        }
        title.addClass('assistive-text');
      });
      $('#sermon-data-tabs').tabs('#sermon-data > section', { history: true});
    },

    reset: function() {
      $('#sermon-data-tabs').remove();
      $('#sermon-data > section').show()
        .find('> :header:first').removeClass('assistive-text');
    },

    test: function() {
      return $( window ).width() > 600;
    }
  };


  // initial load

  if (smallMenu.test()) {
    smallMenu.init();
  }

  if (sermonNav.test()) {
    sermonNav.init();
  }

  loadHeaderImage();


  // on resize
  $( window ).resize( function() {
    if ( false !== timeout )
      clearTimeout( timeout );

    timeout = setTimeout( function() {
      if (smallMenu.test()) {
        smallMenu.init();
      } else {
        smallMenu.reset();
      }

      if (sermonNav.test()) {
        sermonNav.init();
      } else {
        sermonNav.reset();
      }

      loadHeaderImage();
    }, 200 );
  } );

} );
