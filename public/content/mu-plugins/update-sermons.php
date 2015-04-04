<?php

add_action( 'admin_menu', function() {
  add_dashboard_page('Update URLs', 'Update URLs', 'publish_posts', 'sermon-browser_urls', 'sermons_update_urls');
});

function sermons_update_urls() {
  $posts = get_posts('post_type=mbsb_sermon&posts_per_page=-1');
  foreach ($posts as $post) {
    $attachments = get_post_meta($post->ID, 'attachments');
    foreach ($attachments as $attachment) {
      if (strpos($attachment['url'], 'http://media.cchmb.org/') === 0) {
        $new = $attachment;
        $new['url'] = str_replace('http://media.cchmb.org/', 'https://media.cchmb.org/', $new['url']);
        update_post_meta($post->ID, 'attachments', $new, $attachment);
?>
  <pre>old: <?php print_r($attachment); ?></pre>
  <pre>new: <?php print_r($new); ?></pre>
<?php
      }
    }
  }
}
