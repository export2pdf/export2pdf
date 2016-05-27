<?php

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
  
function export2pdf_is_field_map_designer_page()
{
  // Check if we're exporting a PDF
  if ( isset( $_GET[ 'action' ] ) and ( $_GET[ 'action' ] == 'export2pdf-download' ) )
    return true;
    
  // Check if we're in field map designer
  if ( ! isset( $_GET['page'] ) or ! isset( $_GET['action'] ) )
    return false;
  if ( ( $_GET['page'] != 'export2pdf-templates' ) or ( $_GET['action'] != 'edit_step3' ) )
    return false;
  return true;
}

// Shortcode builder

add_action( 'wp_loaded', function () {  

  // We want to see the shortcode on all pages except for the field map designer page
  if ( export2pdf_is_field_map_designer_page() )
    return;
    
  // Attach shortcode processor
  add_shortcode( \Export2Pdf\ShortcodeExport::NAME, array( "\\Export2Pdf\\ShortcodeExport", "process" ) );
  
  // Add buttons
  if ( current_user_can('edit_posts') and current_user_can('edit_pages') )  
  {  
  
    // Supply list of external buttons to TinyMCE
    add_filter('mce_buttons', function ( $buttons ) {  
    
      array_push( $buttons, "export2pdf" );  
      return $buttons;  
      
    });  
  
    // Tell TinyMCE where to find corresponding JavaScript files 
    // that will render the shortcode
    add_filter( 'mce_external_plugins', function ( $plugin_array ) {  
    
      $timestamp = @filemtime( \Export2Pdf\Framework::assets_path() . 'js' . DIRECTORY_SEPARATOR . 'tinymce' . DIRECTORY_SEPARATOR . 'shortcode.js' );
      $plugin_array['export2pdf'] = \Export2Pdf\Framework::assets_url() . 'js/tinymce/shortcode.js?' . $timestamp;

      return $plugin_array;  
      
    });  
    
  }  
  
}, 99999 );

// Shortcode builder for fields
add_action( 'wp_loaded', function () {  

  // We want to see the shortcode only on field map designer page
  if ( ! export2pdf_is_field_map_designer_page() )
    return;
    
  // Don't use default theme styles
  global $editor_styles;
  $editor_styles = array();

  // Add general editor styles
  $editor_style_params = array(
    'action'    => 'export2pdf_template_styles',
    'template'  => $_GET['template'],
    'timestamp' => time(),
  );
  $editor_style_url = admin_url( 'admin-ajax.php' ) . '?' . http_build_query( $editor_style_params );
  add_editor_style( $editor_style_url );

  // Attach shortcode processor
  add_shortcode( \Export2Pdf\ShortcodeField::NAME, array( "\\Export2Pdf\\ShortcodeField", "process" ) );
  
  // Add buttons
  if ( current_user_can('edit_posts') and current_user_can('edit_pages') )  
  {  
  
    // Get the list of plugins for TinyMCE when creating an HTML template
    $tinymce_plugins_path = \Export2Pdf\Framework::assets_path() . 'js/tinymce/html/';
    $tinymce_plugins_url  = \Export2Pdf\Framework::assets_url()  . 'js/tinymce/html/';
    
    $tinymce_plugins      = \Export2Pdf\Tools::files_in_folder( $tinymce_plugins_path );
    $tinymce_plugins      = array_filter( 
                              $tinymce_plugins,
                              function ( $tinymce_plugin ) {
                                // Filter only JavaScript files
                                return preg_match( '/\.js$/', $tinymce_plugin );
                              } 
                            );
    $tinymce_plugins      = array_values( $tinymce_plugins );
    
    $tinymce_plugin_names = array_map(
                              // Get basenames of JavaScript files
                              function ( $tinymce_plugin ) {
                                // Filter only JavaScript files
                                return 'export2pdf_' . str_replace( '.js', '', basename( $tinymce_plugin ) );
                              },
                              $tinymce_plugins
                            );
                            
    // Supply list of external buttons to TinyMCE
    add_filter('mce_buttons_3', function ( $buttons ) use ( $tinymce_plugin_names ) {  
    
      foreach ( $tinymce_plugin_names as $tinymce_plugin_name )    
        array_push( $buttons, $tinymce_plugin_name );  
        
      return $buttons;  
      
    });  
  
    // Tell TinyMCE where to find corresponding JavaScript files 
    // that will render the shortcode
    add_filter( 'mce_external_plugins', 
    
      function ( $plugin_array ) 
        use ( 
          $tinymce_plugins, 
          $tinymce_plugin_names, 
          $tinymce_plugins_url, 
          $tinymce_plugins_path 
        ) 
      {  
    
        $plugins_count = count( $tinymce_plugins );
        
        for ( $plugins_counter = 0; $plugins_counter < $plugins_count; $plugins_counter++ )
        {
        
          $plugin_name = $tinymce_plugin_names[ $plugins_counter ];
          $plugin_path = $tinymce_plugins     [ $plugins_counter ];
          $plugin_url  = str_replace( $tinymce_plugins_path, $tinymce_plugins_url, $plugin_path );
        
          $timestamp = @filemtime( $plugin_path );
          $plugin_array[ $plugin_name ] = $plugin_url . '?' . $timestamp;
          
        }

        return $plugin_array;  
      
      }
      
    );  
    
    // Change default TinyMCE buttons
    
    add_filter( 'tiny_mce_before_init', function ( $buttons ) {
    
      $buttons[ 'wordpress_adv_hidden' ] = FALSE; // Hide "Toolbar Toggle" button
      
      $buttons['theme_advanced_buttons1'] = 'bold,italic,underline,bullist,numlist,hr,blockquote,link,unlink,justifyleft,justifycenter,justifyright,justifyfull,outdent,indent';         
      $buttons['theme_advanced_buttons2'] = 'formatselect,pastetext,pasteword,charmap,undo,redo';
      
      return $buttons;
    
    });
    
  }  
  
}, 99999 );

 

