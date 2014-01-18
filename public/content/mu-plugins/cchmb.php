<?php

// add shortlink to admin bar
add_action('admin_bar_menu', 'wp_admin_bar_shortlink_menu', 90);

add_filter( 'genesis_footer_creds_text', 'cchmb_creds_text' );
function cchmb_creds_text($text) {
  $text     = sprintf( '[footer_copyright after=" %s"]', 'Calvary Chapel Half Moon Bay' );
  return $text;
}

/**
 * Handle 'safe_email' shortcode which converts email address into spambot-safe
 * link.
 */
function safe_email($atts, $content=null) {
    return sprintf('<a href="mailto:%1$s">%1$s</a>', antispambot($content));
}
add_shortcode('safe_email', 'safe_email');

add_filter( 'genesis_get_image', function( $output, $args, $id ) {
  if ( $output == '' ) {
    $type = get_post_type( $id );
    if ( $type == 'mbsb_sermon' || $type == 'mbsb_series' ) {
      $url = 'http://placehold.it/1600x900';
    } else if ($type == 'mbsb_preacher' ) {
      $url = 'http://placehold.it/300x300';
    }

    if ( $url ) {
      if ( $args['format'] == 'html' ) {
        $output = '<img src="' . $url . '" />';
      } else if ( $args['format'] == 'url' ) {
        $output = $url;
      } else {
        $output = str_replace( home_url(), '', $url );
      }
    }
  }

  return $output;
}, 99, 3);
