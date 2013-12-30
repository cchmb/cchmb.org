<?php

// add shortlink to admin bar
add_action('admin_bar_menu', 'wp_admin_bar_shortlink_menu', 90);

add_filter( 'genesis_footer_creds_text', 'cchmb_creds_text' );
function cchmb_creds_text($text) {
  $text     = sprintf( '[footer_copyright before="%s "]', __( 'Copyright', 'genesis' ) );
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
