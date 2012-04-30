<?php


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



