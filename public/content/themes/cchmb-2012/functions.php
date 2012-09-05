<?php

/**
 * Theme setup.
 */
function cchmb_theme_setup() {
  add_theme_support( 'post-thumbnails', array( 'page', 'post', 'sermon' ) );
}
add_action('after_setup_theme', 'cchmb_theme_setup');


/**
 * Customize display of the main nav menu.
 */
function cchmb_page_menu_args($args) {
  $args['show_home'] = false;
  return $args;
}
add_filter( 'wp_page_menu_args', 'cchmb_page_menu_args', 11 );


/**
 * Exclude certain pages from the main nav menu.
 */
function cchmb_list_pages_excludes($excludes) {
  if (get_option('show_on_front') == 'page') {
    $excludes[] = get_option('page_on_front');
    $excludes[] = get_option('page_for_posts');
  }

  return $excludes;
}
add_filter('wp_list_pages_excludes', 'cchmb_list_pages_excludes');


/**
 * Return {theme_dir/css/screen.css as the stylesheet_uri.
 */
function cchmb_stylesheet_uri( $stylesheet_uri ) {
  $stylesheet_dir_uri = get_stylesheet_directory_uri();
  $stylesheet_uri = $stylesheet_dir_uri . '/css/screen.css';
  return $stylesheet_uri;
}
add_filter('stylesheet_uri', 'cchmb_stylesheet_uri');


/**
 * Setup scripts added by the theme.
 */
function cchmb_scripts() {
  wp_enqueue_script( 'cchmb.js', get_stylesheet_directory_uri() . '/js/cchmb.js', array( 'jquery' ), '20120909', true );
  wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Open+Sans|Gruppo', null, null );
}
add_action( 'wp_enqueue_scripts', 'cchmb_scripts' );


/**
 * Add content to the page's <head> element.
 */
function cchmb_head() {
  echo '<meta name="viewport" content="width=device-width, initial-scale=1">
';
}
add_action( 'wp_head', 'cchmb_head' );


/**
 * Be silent if comments are closed.
 */
function cchmb_comments_closed($comments) {
  return '';
}
add_filter( 'pdx_comments_closed', 'cchmb_comments_closed' );


/**
 * Register javascript.
 */
function cchmb_js() {
  if ( WP_DEBUG ) {
    $jquery_tools = get_stylesheet_directory_uri() . '/js/jquery.tools.min.js';
  } else {
    $jquery_tools = 'http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js';
  }
  wp_enqueue_script('jquery-tools', $jquery_tools, false, null, true);
}
add_action('wp', 'cchmb_js');


/**
 * Show all sermons on the series archive page.
 */
function cchmb_pre_get_posts( $wp_query ) {
  if ( $wp_query->is_tax('sermon_series') ) {
    $wp_query->query_vars['posts_per_page'] = -1;
    $wp_query->query_vars['order'] = 'ASC';
  }
}
add_action('pre_get_posts', 'cchmb_pre_get_posts');


function cchmb_default_sermon_series_thumbnail_id( $thumbnail_id ) {
  if ( !$thumbnail_id ) {
    $attachments = get_posts('post_type=attachment&name=sermon-series-default');
    if ($attachments) {
      $thumbnail_id = $attachments[0]->ID;
    }
  }
  return $thumbnail_id;
}
add_filter('sermon_series_thumbnail_id', 'cchmb_default_sermon_series_thumbnail_id');


function cchmb_default_sermon_thumbnail_id( $meta_value, $object_id, $meta_key, $single ) {
  if ( '_thumbnail_id' == $meta_key && 'sermon' == get_post_type($object_id) ) {
    // $meta_value will always be null, so manually check if the sermon has a thumbnail
    $metadata = get_post_meta($object_id);
    if ( !isset($metadata['_thumbnail_id']) ) {
      $series_id = get_primary_sermon_series($object_id);
      $meta_value = get_sermon_series_thumbnail_id($series_id);
    }
  }
  return $meta_value;
}
add_filter('get_post_metadata', 'cchmb_default_sermon_thumbnail_id', 10, 4);
