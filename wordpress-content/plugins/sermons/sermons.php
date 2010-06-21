<?php
/*
 Plugin Name: Sermons
 Plugin URI: http://wordpress.org/extend/plugins/sermons
 Description: Sermons
 Author: Will Norris
 Author URI: http://willnorris.com/
 Version: 1.0-trunk
 License: Apache 2 (http://www.apache.org/licenses/LICENSE-2.0.html)
 Text Domain: sermons
*/


function sermons_register_post_type() {

	// make sure and register the taxonomies BEFORE the post type because the rewrite slugs overlap

	$series_args = array( 
		'hierarchical' => true, 
		'labels' => array(
			'name' => __('Sermon Series', 'sermon'), 
			'add_new_item' => __('Add New Series', 'sermon'), 
		),
		'singular_label' => __('Sermon Series', 'sermon'), 
		'query_var' => 'sermon_series', 
		'rewrite' => get_sermon_permalink_base() ? array( 'slug' => get_sermon_permalink_base() . '/series' ) : false,
	);
	register_taxonomy( 'sermon_series', '', $series_args );

	$service_args = array( 
		'hierarchical' => true, 
		'labels' => array(
			'name' => __('Sermon Services', 'sermons'), 
			'add_new_item' => __('Add New Service', 'sermon'), 
		),
		'singular_label' => __('Sermon Service', 'sermons'), 
		'query_var' => 'sermon_service', 
		'rewrite' => get_sermon_permalink_base() ? array( 'slug' => get_sermon_permalink_base() . '/service' ) : false,
	);
	register_taxonomy( 'sermon_service', '', $service_args );

	// setup custom post type
	$post_type_args = array(
		'labels' => array(
			'name' => __('Sermons', 'sermons'),
			'singular_name' => __('Sermon', 'sermons'),
			'add_new_item' => __('Add New Sermon', 'sermons'),
			'edit_item' => __('Edit Sermon', 'sermons'),
			'new_item' => __('New Sermon', 'sermons'),
			'view_item' => __('View Sermon', 'sermons'),
			'search_items' => __('Search Sermons', 'sermons'),
			'not_found' => __('No sermons found', 'sermons'),
			'not_found_in_trash' => __('No sermons found in Trash', 'sermons'),
		),
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => get_sermon_permalink_base() ? array( 'slug' => get_sermon_permalink_base(), 'with_front' => false ) : false,
		'permalink_epmask' => EP_ALL,
		'query_var' => false,
		'supports' => array('title', 'editor', 'author', 'custom-fields'),
		'register_meta_box_cb' => 'sermons_register_meta_box',
	);
	register_post_type('sermon', $post_type_args);

	// register rewrite rules
	if ( isset($post_type_args['rewrite']['slug']) ) {
		// rewrite rule for feed
		add_rewrite_rule( $post_type_args['rewrite']['slug'] . '/feed', 'index.php?feed=sermons', 'top' );

		// rewrite rule for pagination
		add_rewrite_rule( $post_type_args['rewrite']['slug'] . '/page/?([0-9]{1,})/?$', 'index.php?post_type=sermon&paged=$matches[1]', 'top' );

		flush_rewrite_rules();
	}

	global $wp_rewrite;
	//wp_die('<pre>' . print_r($wp_rewrite, true) . '</pre>');

	register_taxonomy_for_object_type('sermon_series', 'sermon');
	register_taxonomy_for_object_type('sermon_service', 'sermon');
}
add_action('init', 'sermons_register_post_type');

function sermons_register_meta_box() {
	add_meta_box('sermon-audio', __('Sermon Audio', 'sermons'), 'sermons_audio_meta_box', 'sermon');
}

function sermons_audio_meta_box( $post ) {
	echo '
	<input type="text" name="sermon-audio" />';
}
 
function sermons_template_include($template) {
	global $wp;

	if ( $wp->query_vars['post_type'] == 'sermon' ) {
		$t = get_sermon_template();
		$template = $t ? $t : $template;
	}

	return $template;
}
//add_filter('template_include', 'sermons_template_include');
 
function get_sermon_template() {
	return get_query_template('sermon');
}


function do_feed_sermons() {
	query_posts('post_type=sermon');
	do_feed_atom();
}
add_action('do_feed_sermons', 'do_feed_sermons');


function get_sermon_permalink_base() {
	$base = get_option('sermon_base');
	if ( empty($base) ) {
		$base = 'sermons';
	}
	return $base;
}


function sermons_admin_init() {
	register_setting('permalink', 'sermon_base');
	add_settings_field('sermon_base', __('Sermon base', 'sermons'), 'sermons_permalink_form', 'permalink', 'optional');
}
add_action('admin_init', 'sermons_admin_init');

function sermons_permalink_form() {
	global $blog_prefix;

	if ( isset($_POST['sermon_base']) ) {
		check_admin_referer('update-permalink');
		update_option('sermon_base', trim($_POST['sermon_base']));
		flush_rewrite_rules();
	}

	$sermon_base = get_option('sermon_base');

	echo $blog_prefix
	  . '<input id="sermon_base" class="regular-text code" type="text" value="' . esc_attr($sermon_base) . '" name="sermon_base" />';
}


function sermons_wp( $wp ) {
	$sermons_base = get_sermon_permalink_base();
	if ( $wp->request == "$sermons_base/feed" ) {
		//$wp->query_vars['feed'] = 'sermons';
	}

	wp_die( '<pre>' . print_r($wp, true) . '</pre>' );
}
//add_filter('parse_request', 'sermons_wp');

function sermons_default_bible_translation() {
	return apply_filters('sermons_default_bible_translation', 'nkjv');
}

function sermons_passage_url( $passage, $translation = null ) {
	if ( !$translation ) $translation = sermons_default_bible_translation();
	$url = 'http://www.biblegateway.com/passage/?version=' . $translation . '&search=' . urlencode($passage);
	return apply_filters('sermons_passage_url', $url, $passage, $translation);
}

function sermons_passage_link( $passage, $translation = null ) {
	$url = sermons_passage_url($passage, $translation);
	$link = '<a href="' . $url . '" class="bible-link">' . $passage . '</a>';
	return apply_filters('sermons_passage_link', $link, $passage, $translation);
}

?>
