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
    register_widget( 'CCHMB_Sermon_Series_Widget' );

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
    global $post;
    if ( is_singular( 'mbsb_sermon' ) ) {
        $layout = 'content-sidebar';
    }

    return $layout;
}

class CCHMB_Sermon_Series_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'cchmb_sermon_series',
            __('Sermon Series', 'cchmb'),
            array('description' => __('List sermons in the current series', 'cchmb'))
        );

        $this->defaults = array(
          'title'           => 'Others in series: %s',
          'show_image'      => 0,
          'image_alignment' => '',
          'image_size'      => '',
          'order'      => 'desc',
        );

        $widget_ops = array(
          'classname'   => 'sermon-series',
          'description' => __( 'Displays other sermons in the same series', 'cchmb' ),
        );

        $control_ops = array(
          'id_base' => 'sermon-series',
          'width'   => 200,
          'height'  => 250,
        );

        parent::__construct( 'sermon-series', __( 'Sermon Series', 'cchmb' ), $widget_ops, $control_ops );
    }

    public function widget( $args, $instance ) {
        global $wp_query;

        $id = get_queried_object_id();
        $series_id = get_post_meta($id, 'series', true);
        $series_title = $series_id == 0 ? '(no series)' : get_the_title($series_id);

        extract($args);

        //* Merge with defaults
        $instance = wp_parse_args( (array) $instance, $this->defaults );

        echo $before_widget;

        if ( ! empty( $instance['title'] ) )
          echo $before_title . sprintf( apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ), $series_title ) . $after_title;

        $wp_query = new WP_Query( array(
            'post_type' => 'mbsb_sermon',
            'meta_key' => 'series',
            'meta_value' => $series_id,
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => $instance['order'],
        ) );

        echo '<ul>';
        if ( have_posts() ) : while ( have_posts() ) : the_post();
            echo do_shortcode( sprintf( '<li><a href="%s" title="%s">%s</a> [post_date]</li>', get_permalink(), the_title_attribute( 'echo=0' ), get_the_title() ));
        endwhile;
        endif;
        echo '</ul>';

        //* Restore original query
        wp_reset_query();

        echo $after_widget;
    }

    public function update( $new_instance, $old_instance ) {
        $new_instance['title'] = strip_tags( $new_instance['title'] );
        $new_instance['order'] = strip_tags( $new_instance['order'] );
        return $new_instance;
    }

    public function form( $instance ) {
        //* Merge with defaults
        $instance = wp_parse_args( (array) $instance, $this->defaults );

        ?>
        <p>
          <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'genesis' ); ?>:</label>
          <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
        </p>

        <hr class="div" />

        <p>
        <input id="<?php echo $this->get_field_id( 'show_image' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'show_image' ); ?>" value="1"<?php checked( $instance['show_image'] ); ?> />
        <label for="<?php echo $this->get_field_id( 'show_image' ); ?>"><?php _e( 'Show Featured Image', 'genesis' ); ?></label>
        </p>

        <p>
        <label for="<?php echo $this->get_field_id( 'image_size' ); ?>"><?php _e( 'Image Size', 'genesis' ); ?>:</label>
        <select id="<?php echo $this->get_field_id( 'image_size' ); ?>" class="genesis-image-size-selector" name="<?php echo $this->get_field_name( 'image_size' ); ?>">
            <option value="thumbnail">thumbnail (<?php echo absint( get_option( 'thumbnail_size_w' ) ); ?>x<?php echo absint( get_option( 'thumbnail_size_h' ) ); ?>)</option>
            <?php
            $sizes = genesis_get_additional_image_sizes();
            foreach ( (array) $sizes as $name => $size )
            echo '<option value="' . esc_attr( $name ) . '" ' . selected( $name, $instance['image_size'], FALSE ) . '>' . esc_html( $name ) . ' (' . absint( $size['width'] ) . 'x' . absint( $size['height'] ) . ')</option>';
            ?>
        </select>
        </p>

        <hr class="div" />
        <p>
        <label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Sort Order', 'genesis' ); ?>:</label>
        <select id="<?php echo $this->get_field_id( 'order' ); ?>" class="genesis-sort-order-selector" name="<?php echo $this->get_field_name( 'order' ); ?>">
            <option value="asc" <?php selected( 'asc', $instance['order'], FALSE ) ?>><?php _e('Oldest First', 'genesis') ?></option>
            <option value="desc" <?php selected( 'desc', $instance['order'], FALSE ) ?>><?php _e('Newest First', 'genesis') ?></option>
        </select>
        </p>

        <?php
    }
}
