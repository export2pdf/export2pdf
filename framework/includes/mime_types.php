<?php

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Add a custom MIME type to handle PDF uploads
 */

add_filter( 'post_mime_types', function ( $post_mime_types ) {
 
  // Select the mime type, here: 'application/pdf'
  // Then we define an array with the label values

  $post_mime_types['application/pdf'] = array( __( 'PDFs' ), __( 'Manage PDFs' ), _n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>' ) );

  // Then we return the $post_mime_types variable
  return $post_mime_types;
 
});
