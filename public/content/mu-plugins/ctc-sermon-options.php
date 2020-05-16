<?php
/*
Plugin Name: CTC Sermon Options
Description: Adds additional slides and passage fields for sermons provided by the Church Theme Content plugin.
Author: Will Norris
Author URI: https://willnorris.com/
*/

add_filter( 'ctmb_fields-ctc_sermon_options', function( $fields ) {
  $fields['_ctcx_sermon_slides'] = array(
    'name'              => __( 'Slides', '' ),
    'after_name'        => '', // (Optional), (Required), etc.
    'after_input'       => '', // text to show to right of input (fields: text, select, number, range, upload, url, date, time)
    'desc'              => __( 'Enter the URL to the sermon slides.', '' ),
    'type'              => 'url', // text, textarea, checkbox, radio, select, number, range, upload, upload_textarea, url, date, time
    'checkbox_label'    => '', //show text after checkbox
    'options'           => array(), // array of keys/values for radio or select
    'upload_button'     => '', // text for button that opens media frame
    'upload_title'      => '', // title appearing at top of media frame
    'upload_type'       => '', // optional type of media to filter by (image, audio, video, application/pdf)
    'default'           => '', // value to pre-populate option with (before first save or on reset)
    'no_empty'          => false, // if user empties value, force default to be saved instead
    'allow_html'        => false, // allow HTML to be used in the value (text, textarea)
    'attributes'        => array(), // attr => value array (e.g. set min/max for number or range type)
    'class'             => '', // class(es) to add to input (try ctmb-medium, ctmb-small, ctmb-tiny)
    'field_attributes'  => array(), // attr => value array for field container
    'field_class'       => '', // class(es) to add to field container
    'custom_sanitize'   => '', // function to do additional sanitization
    'custom_field'      => '', // function for custom display of field input
    'visibility'        => array(), // show/hide based on other fields' values: array( array( 'field1' => 'value' ), array( 'field2' => array( 'value', '!=' ) )
  );

  $fields['_ctcx_sermon_passage'] = array(
    'name'              => __( 'Passage', '' ),
    'after_name'        => '', // (Optional), (Required), etc.
    'after_input'       => '', // text to show to right of input (fields: text, select, number, range, upload, url, date, time)
    'desc'              => __( 'Enter the primary bible passage(s).', '' ),
    'type'              => 'text', // text, textarea, checkbox, radio, select, number, range, upload, upload_textarea, url, date, time
    'checkbox_label'    => '', //show text after checkbox
    'options'           => array(), // array of keys/values for radio or select
    'upload_button'     => '', // text for button that opens media frame
    'upload_title'      => '', // title appearing at top of media frame
    'upload_type'       => '', // optional type of media to filter by (image, audio, video, application/pdf)
    'default'           => '', // value to pre-populate option with (before first save or on reset)
    'no_empty'          => false, // if user empties value, force default to be saved instead
    'allow_html'        => false, // allow HTML to be used in the value (text, textarea)
    'attributes'        => array(), // attr => value array (e.g. set min/max for number or range type)
    'class'             => '', // class(es) to add to input (try ctmb-medium, ctmb-small, ctmb-tiny)
    'field_attributes'  => array(), // attr => value array for field container
    'field_class'       => '', // class(es) to add to field container
    'custom_sanitize'   => '', // function to do additional sanitization
    'custom_field'      => '', // function for custom display of field input
    'visibility'        => array(), // show/hide based on other fields' values: array( array( 'field1' => 'value' ), array( 'field2' => array( 'value', '!=' ) )
  );

  return $fields;
}, 20);

add_filter( 'ctc_get_theme_support_by_post_type', function( $data, $post_type, $argument ) {
  if ( 'ctc_sermon' == $post_type ) {
    //wp_die(print_r($data, true));
  }
  return $data;
}, 10, 3);

add_filter( 'ctc_field_supported', function( $supported, $feature, $field ) {
  //wp_die("$feature - $field");
  if ( '_ctc_sermon_pdf' == $field ) {
  }
  return $supported;
}, 10, 3);

add_filter( 'wp_term_image_get_taxonomies', function( $args ) {
  $args["name"] = ("ctc_sermon_speaker" && "ctc_sermon_series");
  return $args;
}, 11);