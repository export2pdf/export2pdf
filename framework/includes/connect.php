<?php

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

add_action( 'init', function () {

  // Set up the database
  \Export2Pdf\Db::connect();
  // Perform all pending migrations
  \Export2Pdf\Migration::migrate();

});
