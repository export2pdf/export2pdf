<?php

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Start-up checks for compatibility (PHP version, ...)
 */
 
function export2pdf_startup_checks()
{

  // Check PHP version
  if ( version_compare( PHP_VERSION, '5.4.0', '<' ) )
    throw new Exception( 
      "<h1>PHP update required</h1>" . 
      "<p>Export2PDF requires PHP verion 5.4.0 or later. Your PHP version is " . PHP_VERSION . "</p>" 
    );

}

function export2pdf_activate()
{

  try
  {
    export2pdf_startup_checks();
  }
  catch ( Exception $e )
  {
    wp_die( 
      $e->getMessage(),
      'Export2PDF activation error',
      array(
        'back_link' => true,
      )
    );
  }

}
