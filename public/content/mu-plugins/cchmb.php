<?php

// Handle [safe_email] shortcode which converts email address into spambot-safe link.
add_shortcode('safe_email', function($atts, $content=null) {
  return sprintf('<a href="mailto:%1$s">%1$s</a>', antispambot($content));
});

add_filter( 'opengraph_image', function( $image ) {
  if ( function_exists('genesis_get_image') ) {
    if ( $img = genesis_get_image('format=url') ) {
      $image = $img;
    }
  }
  return $image;
}, 9);

add_filter( 'opengraph_metadata', function( $metadata ) {
  if ( is_singular('ctc_sermon') && function_exists('ctfw_sermon_data') ) {
    extract( ctfw_sermon_data() );
    $metadata['og:type'] = 'article';

    if (strstr($video, 'youtube.com') !== false) {
      parse_str( parse_url( $video, PHP_URL_QUERY ) );
      $metadata['og:type'] = 'video.other';
      $metadata['og:video:url'] = 'https://www.youtube.com/embed/' . $v;
      $metadata['og:video:secure_url'] = 'https://www.youtube.com/embed/' . $v;
      $metadata['og:video:type'] = 'text/html';
      $metadata['og:video:width'] = '1280';
      $metadata['og:video:height'] = '720';
    } else if ( $audio ) {
      $metadata['og:audio:url'] = $audio;
      $metadata['og:audio:type'] = 'audio/mpeg';
    }
  }
  return $metadata;
});

// If hum is unable to resolve a shortcode, try to lookup by the "_original_post_id" post meta field.
add_filter( 'hum_redirect_b', function($url, $id) {
  if ( empty($url) ) {
    $original_id = get_post_meta($id, "_original_post_id", true);
    if ( $original_id ) {
      $permalink = get_permalink($original_id);
      if ( $permalink ) {
        $url = $permalink;
      }
    }
  }
  return $url;
}, 10, 2);

// display more sermon info on admin screen
add_filter('manage_ctc_sermon_posts_columns', function($columns) {
  $insert_array = [];
  if ( ctc_taxonomy_supported( 'sermons', 'ctc_sermon_series' ) ) $insert_array['ctc_sermon_series'] = esc_html_x( 'Series', 'sermons', 'church-theme-content' );
  if ( ctc_taxonomy_supported( 'sermons', 'ctc_sermon_speaker' ) ) $insert_array['ctc_sermon_speakers'] = esc_html_x( 'Speaker', 'sermons', 'church-theme-content' );
  $columns = ctc_array_merge_after_key( $columns, $insert_array, 'title' );
  return $columns;
});

// rewrite sermon audio URLs to use media.cchmb.org hostname rather than storage.googleapis.com
add_filter('sanitize_post_meta__ctc_sermon_audio', function($meta_value, $meta_key, $object_type) {
  if ( $meta_value ) {
    $meta_value = preg_replace('|^(https://)storage.googleapis.com/(media.cchmb.org/)|', "$1$2", $meta_value);
  }
  return $meta_value;
}, 10, 5);
