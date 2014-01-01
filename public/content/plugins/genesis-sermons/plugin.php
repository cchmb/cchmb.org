<?php
/*
 Plugin Name: Genesis Sermons
 Description: Display sermons-browser sermons in your Genesis theme.
 Author: Will Norris
 Author URI: http://willnorris.com/
 Version: 0.1
 License: Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0.html)
 */

add_filter('wp', 'cchmb_sermons_wp');
function cchmb_sermons_wp($wp) {
    if ( is_singular( 'mbsb_sermon' ) ) {
        remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
        add_action( 'genesis_sidebar', 'cchmb_after_sermon_sidebar' );

        add_filter('genesis_post_meta', '__return_empty_string' );

        remove_filter('the_content', 'mbsb_provide_content');
        add_filter('the_content', 'cchmb_sermon_content');
    }
}

add_action('widgets_init', 'cchmb_sermons_widgets_init');
function cchmb_sermons_widgets_init() {
    genesis_register_sidebar(array(
        'id' =>          'after_sermon',
        'name' =>        __('After Sermon', 'cchmb'),
        'description' => __('After Sermon', 'cchmb'),
    ));
}

function cchmb_sermon_content($content) {
    global $post;
    $sermon = new mbsb_sermon($post->ID);
    $description = $content;

    foreach ($sermon->attachments->get_attachments() as $k => $attachment) {
        if ($attachment->get_type() == "embed") {
            $video = $attachment->get_media_player();
        } else if (substr($attachment->get_mime_type(), 0, 5) == "audio") {
            $audio = do_shortcode('[audio src="' . $attachment->get_url() . '"]');
            $audio .= '<p class="download_link"><a href="' . $attachment->get_url() . '">Download audio file</a></p>';
        }
    }

    if ($video) {
        $content .= '<section class="sermon_video">'.$video.'</section>';
    }
    $content .= '<section class="sermon_audio"><h3>Audio only</h3>'.$audio.'</section>';

    $content .= $widgets;

    return $content;
}

function cchmb_after_sermon_sidebar() {
    dynamic_sidebar('after_sermon');
}

add_filter('genesis_site_layout', 'cchmb_site_layout');
function cchmb_site_layout($layout) {
    if ( is_singular( 'mbsb_sermon' ) ) {
        $layout = 'content-sidebar';
    }

    return $layout;
}
