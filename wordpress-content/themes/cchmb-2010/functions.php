<?php

include_once dirname(__FILE__) . '/daily-bible-reading.php';
include_once dirname(__FILE__) . '/widgets.php';

function cchmb_head() {
	/*
	<!--[if lt IE 9]>
		<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js">IE7_PNG_SUFFIX=".png";</script>
	<![endif]-->
	 */
?>
	<link href="http://fonts.googleapis.com/css?family=OFL+Sorts+Mill+Goudy+TT:regular|Vollkorn" rel="stylesheet" type="text/css">
<?php
}
add_action('wp_head', 'cchmb_head');

function cchmb_fix_ie() {
	if ( $GLOBALS['is_IE'] ) {
		//wp_register_style();
		
		// Dean Edward's IE9
		wp_register_script('ie9.js', 'http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js');
		add_action('wp_footer', 'cchmb_fix_ie_footer');
	}
}
add_action('wp', 'cchmb_fix_ie', 91);


/* hide header image */
add_filter('twentyten_header_image_width', create_function('', 'return 0;'));
add_filter('twentyten_header_image_height', create_function('', 'return 0;'));

function cchmb_fix_ie_footer() {
?>
	<!--[if lt IE 9]>
		<?php wp_print_scripts('ie9.js'); ?>
	<![endif]-->
<?php
}


function cchmb_gallery_setup() {
	wp_enqueue_script('jquery.showcase', get_stylesheet_directory_uri() . '/js/jquery.showcase.min.js', array('jquery'), false, true);
}
add_filter('wp', 'cchmb_gallery_setup');


function cchmb_cleanup_slideshow_images($attr, $attachment) {
	$attr['alt'] = $attr['title'];
	$caption = trim(strip_tags( $attachment->post_excerpt ));
	if ( $caption ) {
		$attr['data-caption'] = $caption;
	}
	return $attr;
}


function cchmb_slideshow($attr, $content = null) {
	global $post;
	$attachments = get_children( array('post_parent'=>$post->ID, 'post_type'=>'attachment', 'post_mime_type'=>'image', 'orderby'=>'menu_order', 'order'=>'ASC') );

	$slideshow = '';
	if ( $attachments ) {
		$i = 1;
		ob_start();
?>
		<div id="slideshow-container"><div id="slideshow">
<?php 
		add_filter('wp_get_attachment_image_attributes', 'cchmb_cleanup_slideshow_images', 10, 2);
		foreach ($attachments as $attachment) {
			$image = wp_get_attachment_image($attachment->ID, 'large');
			$link = $attachment->post_content;
			echo '
			<a' . ($link ? ' href="'.$link.'"' : '') . '>' . $image . '</a>';
		}
		remove_filter('wp_get_attachment_image_attributes', 'cchmb_cleanup_slideshow_images', 10, 2);
?>
		</div></div>
		<script type="text/javascript">
			jQuery(function() {
				jQuery('#slideshow img').attr('alt', function(index, attr) {
					var caption = jQuery(this).attr('data-caption');
					if ( caption == null ) {
						caption = '';
					}
					return caption;
				});
				jQuery("#slideshow").showcase({
					animation: { 
						interval: 7000,
						stopOnHover: true,
						speed: 500
					},
					titleBar: { 
						autoHide: false,
						position: 'top',
						cssClass: 'slideshow-title',
						css: {
							opacity: '0.8'
						}
					},
					navigator: { 
						position: 'bottom-left',
						css: { 'margin-left': '25px' },
						showNumber: true,
						item: {
							cssClass: 'slideshow-button',
							cssClassSelected: 'slideshow-selected',
							cssClassHover: 'slideshow-hover'
						}
					}
				});
				var toggler = jQuery('<div></div>').addClass('slideshow-toggle');
				var togglerLink = jQuery('<a></a>').attr('href', '#').html('II')
				togglerLink.toggle(function() {
					jQuery('#slideshow').pause();
					jQuery(this).html('&#x25BA;');
					return false;
				}, function() {
					jQuery('#slideshow').go();
					jQuery(this).html('II');
					return false;
				});
				toggler.append(togglerLink);
				jQuery('#slideshow #navigator').before(toggler);
			});
		</script>
<?php
		$slideshow = ob_get_contents();
		ob_end_clean();
	}

	return $slideshow;
}
add_shortcode('slideshow', 'cchmb_slideshow');


function cchmb_links() {
}
add_shortcode('links', 'cchmb_links');


/**
*  * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
*   */
function cchmb_page_menu_args( $args ) {
	$args['show_home'] = false;
	return $args;
}
add_filter('wp_page_menu_args', 'cchmb_page_menu_args', 20);


function cchmb_get_slideshow() {
	$slideshow = '
	<div id="slideshow-container">
		<div id="slideshow">
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="389" height="254" id="flashbanner" align="middle"
				codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0">
				<param name="allowScriptAccess" value="sameDomain" />
				<param name="movie" value="banner.swf" />
				<param name="quality" value="high" />
				<param name="bgcolor" value="#000000" />
				<embed src="banner.swf" quality="high" bgcolor="#000000" width="389" height="254" name="flashbanner" align="middle" 
					allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
			</object>
		</div>
	</div>';

	return $slideshow;
}


function cchmb_add_page_image( $content ) {
	global $post;
	if ( is_page() && has_post_thumbnail($post->ID) ) {
		$image_markup = '<div id="featured-image"><div>
			' . get_the_post_thumbnail( $post->ID, 'medium' ) . '
		</div></div>';

		$content = $image_markup . $content;
	}

	return $content;
}
add_filter('the_content', 'cchmb_add_page_image');


function cchmb_nav_menu_args( $args ) {
	$args['depth'] = 1;
	return $args;
}
add_filter('wp_nav_menu_args', 'cchmb_nav_menu_args');


//add_filter('default_feed', create_function('', 'return "atom";'));


function cchmb_cleanup() {
	global $wp_scripts;


	$comments = false;
	if ( is_single() || is_page() || is_comments_popup() ) {
		global $post;
		if ( 'open' == $post->comment_status ) {
			$comments = true;
		}
	}

	if ( $comments ) {
		$wp_scripts->add_data('comment-reply', 'group', 1);
	} else {
		wp_deregister_script('comment-reply');
	}
}
add_action('wp', 'cchmb_cleanup', 90);

function cchmb_admin_widget() {
	if ( is_front_page() || is_user_logged_in() ) {
?>
	<div id="admin-widget">
		<ul>
			<?php wp_register(); ?>
			<li><?php wp_loginout(); ?></li>
		</ul>
	</div>
<?php
	}
}
add_action('wp_footer', 'cchmb_admin_widget');

?>
