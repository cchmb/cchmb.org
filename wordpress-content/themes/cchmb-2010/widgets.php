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
?>
