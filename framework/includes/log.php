<?php

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

add_action( 'admin_init', function () {
  export2pdf_log( "admin_init", $_SERVER[ 'REQUEST_URI' ] );
});

add_action( 'wp_loaded', function () {
  export2pdf_log( "wp_loaded" );
});

