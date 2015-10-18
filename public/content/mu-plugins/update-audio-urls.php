<?php
/*
Plugin Name: Update Audio URLs
Author: Will Norris
Author URI: http://willnorris.com/
*/

class Audio_URLs {

  function __construct() {
    add_action('admin_menu', array(__CLASS__, 'admin_menu'));
  }

  static function admin_menu() {
    $hookname = add_management_page('Update Audio URLs', 'Update Audio URLs', 
        'manage_options', 'audiourls', array(__CLASS__, 'admin_page'));
  }

  static function admin_page() {
    echo '<h1>Updating Audio URLs</h1>';

    $sermons = get_posts(array(
      'post_type' => 'mbsb_sermon',
      'posts_per_page' => -1,
    ));

    foreach ($sermons as $sermon) {

      $attachments = get_post_meta($sermon->ID, 'attachments', false);
      foreach ($attachments as $attachment) {
        if ($attachment['type'] == 'url') {
          if (preg_match('#media\.cchmb\.org/audio/Archives/#', $attachment['url'])) {
            printf('<p>Sermon <a href="%s">%d</a> has URL: <a href="%3$s">%3$s</a></p>', 
              get_permalink($sermon->ID), $sermon->ID, $attachment['url']);

            $resp = wp_remote_head($attachment['url']);
            $status = $resp['response']['code'];
            $location = $resp['headers']['location'];
            if ($status == 301 && $location) {
              printf('<p>New url is <a href="%1$s">%1$s</a></p>', $location);

              delete_post_meta($sermon->ID, 'attachments', $attachment);
              $attachment['url'] = $location;
              add_post_meta($sermon->ID, 'attachments', $attachment);
            }
            //break 2;
          }
        }
      }
    }
  }

}

new Audio_URLs;
