<?php

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Function to process logs (used in debug mode)
 */
function export2pdf_log( $type, $name = NULL, $additional = NULL )
{
  if ( class_exists( "\\Export2Pdf\\Log" ) )
  {
    \Export2Pdf\Log::log( $type, $name, $additional );
  }
}

/**
 * Include class that manages all startup functions
 */

function export2pdf_initialize_framework( $framework_path )
{
  
  try
  {
    // Perform start up checks
    // Basically to avoid PHP parse errors!
    export2pdf_startup_checks();
  }
  catch ( Exception $e )
  {
    // Do not load plugin is some start up checks failed
    return;
  }
  
  spl_autoload_register( function ( $class_name ) use ( $framework_path ) {
    
    // echo $class_name . "\n";
    
    $class_parts  = explode( "\\", $class_name );             // Because it's usually a part of a namespace
    
    if ( ! in_array( 'Export2Pdf', $class_parts ) )           // This class is not from Export2Pdf namespace
      return; 
    
    $class_name   = end( $class_parts );        
    $class_name   = preg_replace( '/^\_/', '', $class_name ); // If we have _Db or _Settings class
    
    $class_name   = str_replace( '_', '/', $class_name );     // If we need subfolders
    
    // If class name is Some_Error, then reverse the class name
    if ( preg_match( '/^(.*)\/Error$/', $class_name, $matches ) )
      $class_name = 'Error/' . $matches[ 1 ];
    
    $classes_path = $framework_path . '/classes/';
    $class_path   = $classes_path . $class_name . ".php";
    
    // echo $class_path . "\n";
    
    if ( file_exists( $class_path ) )
    {
      // echo $class_path . " --- yes \n";
      require_once $class_path;
    }
    
  }, TRUE, TRUE);
  
  foreach ( array( 'includes', 'controllers' ) as $folder )
  {
  
    $includes_folder = $framework_path . '/' . $folder . '/';
    if ( file_exists( $includes_folder ) )
    {
    
      // Include everything in {includes,controllers}/*.php
    
      $class_files = \Export2Pdf\Tools::files_in_folder( $includes_folder, TRUE );
      foreach ( $class_files as $class_file )
        if ( is_file( $class_file ) )
          if ( basename( $class_file ) != basename( __FILE__ ) )
            require_once $class_file;
    
    }
  
  }
  
}

export2pdf_initialize_framework( __DIR__ );


