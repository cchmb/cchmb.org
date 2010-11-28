<?php


/**
 * Add additional widget area for the front page.
 */
function cchmb_widgets_init() {
  // Area 1, located at the top of the sidebar.
  register_sidebar( array(
    'name' => __( 'Front Page Widget Area', 'cchmb' ),
    'id' => 'front-page-widget-area',
    'description' => __( 'The front page widget area', 'cchmb' ),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );

  register_widget('Widget_Scroll');
  register_widget('Widget_Daily_Reading');
  register_widget('Widget_Sermons');
  register_widget('Widget_Social_Media');
}
add_action( 'widgets_init', 'cchmb_widgets_init' );


class Widget_Scroll extends WP_Widget {
  function __construct() {
    parent::__construct(false, 'Scroll', array('description'=>'A scroll image with text and a link'));
  }

  function form($instance) {
    $title = esc_attr($instance['title']);
    $link = esc_attr($instance['link']);
    ?>
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
            name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link:'); ?> 
          <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" 
            name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" />
        </label>
      </p>
    <?php
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['link'] = strip_tags($new_instance['link']);
        return $instance;

  }

  function widget($args, $instance) {
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    $link = $instance['link'];
    echo $before_widget;
    if ( $link ) {
      $title = '<a href="' . $link . '">' . $title . '</a>';
    }
    echo $title;
    echo $after_widget;
  }
}

class Widget_Daily_Reading extends WP_Widget {
  function __construct() {
    parent::__construct(false, 'Daily Reading', array('description'=>'Daily Bible Reading'));
  }

  function form($instance) {
    $title = esc_attr($instance['title']);
    $subtitle = esc_attr($instance['subtitle']);
    $link = esc_attr($instance['link']);
    ?>
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
            name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php _e('Sub-Title:'); ?> 
          <input class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" 
            name="<?php echo $this->get_field_name('subtitle'); ?>" type="text" value="<?php echo $subtitle; ?>" />
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Whole Year Bible Link:'); ?> 
          <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" 
            name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" />
        </label>
      </p>
    <?php
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['subtitle'] = strip_tags($new_instance['subtitle']);
    $instance['link'] = strip_tags($new_instance['link']);
        return $instance;

  }

  function widget($args, $instance) {
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    $subtitle = $instance['subtitle'];
    $link = $instance['link'];

    echo $before_widget;
    if ( $title ) {
      echo $before_title . $title . $after_title;
    }
    if ( $subtitle ) {
      echo '<p class="subtitle">' . $subtitle . '</p>';
    }
    if ( function_exists('cchmb_daily_reading') ) {
      echo '<div id="daily-reading">' . cchmb_daily_reading() . '</div>';
    }
    if ( $link ) {
      echo '<a class="whole-year" href="' . $link . '">Download Whole Year</a>';
    }
    echo $after_widget;
  }
}

class Widget_Sermons extends WP_Widget {
  function __construct() {
    parent::__construct(false, 'Sermons', array('description'=>'Recent Sermons'));
  }

  function form($instance) {
    $title = esc_attr($instance['title']);
    $number = (int) esc_attr($instance['number']);
    if ( ! $number ) $number = 5;
    ?>
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
            name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of sermons to show:'); ?> 
          <input size="3" id="<?php echo $this->get_field_id('number'); ?>" 
            name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </label>
      </p>
    <?php
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['number'] = strip_tags($new_instance['number']);
        return $instance;
  }

  function widget($args, $instance) {
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    $number = $instance['number'];
    if ( !$number ) $number = 5;

    $r = new WP_Query(array('post_type' => 'sermon', 'showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'caller_get_posts' => 1)); 
        if ($r->have_posts()) :
?>
        <?php echo $before_widget; ?>
        <?php if ( $title ) echo $before_title . $title . $after_title; ?>
        <ul> 
        <?php  while ($r->have_posts()) : $r->the_post(); ?>
        <li><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a></li>
        <?php endwhile; ?>
        </ul>
        <?php echo $after_widget; ?>
<?php
        // Reset the global $the_post as this query will have stomped on it
        wp_reset_postdata();

        endif;
  }
}

class Widget_Social_Media extends WP_Widget {
  function __construct() {
    $widget_ops = array('classname' => 'widget_social_media', 'description' => __('Social Media Links'));
    $control_ops = array('width' => 400, 'height' => 350);
    parent::__construct(false, __('Social Media', 'cchmb'), $widget_ops, $control_ops);
  }

  function form($instance) {
    $title = esc_attr($instance['title']);
    $links = esc_attr($instance['links']);
    ?>
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
            name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('links'); ?>"><?php _e('Links:'); ?>
          <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('links'); ?>" name="<?php echo $this->get_field_name('links'); ?>"><?php echo $links; ?></textarea>
        </label>
      </p>
    <?php
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['links'] = strip_tags($new_instance['links']);
    return $instance;
  }

  function widget($args, $instance) {
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    $links = array_filter( preg_split("/\n/", $instance['links']) );

    echo $before_widget;
    if ( $title ) {
      echo $before_title . $title . $after_title;
    }
    if ( $links ) {
      echo '<ul class="social-media">';
      foreach ($links as $link) {
        list($name, $url) = preg_split('/:/', $link, 2);
        $text = 'Find us on '. trim($name);
        $url = trim($url);
        $class = strtolower( trim( $name ) );
        echo '<li><a href="' . esc_url($url) . '" class="' . esc_attr($class) . '" title="' . esc_attr($text) . '">' . $text . '</a></li>';
      }
      echo '</ul>';
    }
    echo $after_widget;
  }
}
?>
