<?php

/**
 * Theme setup.
 */
function cchmb_theme_setup() {
  add_theme_support( 'post-thumbnails', array( 'sermon' ) );
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
  wp_enqueue_script( 'small-menu', get_stylesheet_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120517', true );
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
