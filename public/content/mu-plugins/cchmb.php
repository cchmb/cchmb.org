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
    global $post;
    $type = get_post_type( $post->ID );
    switch ($type) {
      case 'mbsb_sermon':
        // if a sermon doesn't have an image, use the image from the series
        $series_id = get_post_meta($post->ID, 'series', true);
        if ( has_post_thumbnail($series_id) ) {
          $tn_id = get_post_thumbnail_id($series_id);
          return wp_get_attachment_image($tn_id, $args['size'], false, $args['attr']);
        }
        break;
      case 'mbsb_series':
        //$url = 'http://placehold.it/1600x900';
        break;
      case 'mbsb_preacher':
        //$url = 'http://placehold.it/300x300';
        break;
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
