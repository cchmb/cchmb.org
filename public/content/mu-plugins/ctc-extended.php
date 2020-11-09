<?php
/*
Plugin Name: Extended Church Content
Description: Extends Church Content plugin with additional functionality such as sermon fields for slides and Bible passage.
Author: Will Norris
Author URI: https://willnorris.com/
*/

add_filter( 'ctmb_fields-ctc_sermon_options', function( $fields ) {
  $fields['_ctcx_sermon_slides'] = array(
    'name'              => __( 'Slides', '' ),
    'desc'              => __( 'Enter the URL to the sermon slides.', '' ),
    'type'              => 'url',
  );

  $fields['_ctcx_sermon_passage'] = array(
    'name'              => __( 'Passage', '' ),
    'desc'              => __( 'Enter the primary bible passage(s).', '' ),
    'type'              => 'text',
  );

  return $fields;
}, 20);

add_filter( 'ctfw_sermon_data', function( $data ) {
  $data = array_merge($data,
    ctfw_get_meta_data(array('slides', 'passage'), null, '_ctcx_sermon_')
  );

  return $data;
}, 20);
