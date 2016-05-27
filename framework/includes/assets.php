<?php

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Add CSS styles and JavaScripts for admin interface
 */

add_action( 'admin_enqueue_scripts', function () {
  
  /*
  
    TODO: try if it works?
    
    // Add media uploader for designer, step 2
    if (
        isset( $_GET[ 'page' ] ) 
        and preg_match( '/^export2pdf/', $_GET[ 'page' ] )
        and isset( $_GET[ 'action' ] ) 
        and ( $_GET[ 'action' ] == 'edit_step2' ) 
      )
    {
      wp_enqueue_media();
    }
    
  */
  
  
  // Common WP scripts and styles
  // that the plugin will use
  
  wp_enqueue_media();
  
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'wp-color-picker');
  
  wp_enqueue_style( 'wp-color-picker' );
  

  // List of CSS styles to include
  $css_files = array(
    'Glyphter',                       // Font for the menu icon
    'menu',                           // Menu styles
    'admin',                          // Other admin styles
  );
  
  // List of JS scripts to include
  $js_files = array(
    'admin',                          // Other admin scripts
  );
  
  // Enqueue CSS files
  foreach ( $css_files as $css_file )
  {
    
    $relative_url = 'css' . '/' . $css_file . '.css';
    $relative_path = 'css' . DIRECTORY_SEPARATOR . $css_file . '.css';
    
    // Enqueue style
    wp_enqueue_style(
      
      'export2pdf-' . $css_file,
      \Export2pdf\Framework::assets_url() . $relative_url,
      false,
      @filemtime( \Export2pdf\Framework::assets_path() . $relative_path )
      
    );
  }
  
  // Enqueue JS files
  foreach ( $js_files as $js_file )
  {
    
    $relative_url = 'js' . '/' . $js_file . '.js';
    $relative_path = 'js' . DIRECTORY_SEPARATOR . $js_file . '.js';
    
    // Enqueue style
    wp_enqueue_script(
      
      'export2pdf-' . $js_file,
      \Export2pdf\Framework::assets_url() . $relative_url,
      false,
      @filemtime( \Export2pdf\Framework::assets_path() . $relative_path )
      
    );
  }

});
