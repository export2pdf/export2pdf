<?php

/**
 * Change plugin settings
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class SettingsController extends Controller
{

  const PAGE = "export2pdf-settings";
  
  public function index()
  {
  
    if ( Tools::is_post() )
    {
    
      $debug_mode = Debug::enabled();
    
      // Form is submitted
      $settings = stripslashes_deep( $_POST[ 'settings' ] );
      
      foreach ( $settings as $option_name => $option_value )
        Settings::set( $option_name, $option_value );
        
      // Reload page if debug mode changed
      if ( Debug::enabled() != $debug_mode )
      {
      
        $refresh_url = $this->action_url();
        Tools::redirect( $refresh_url ); 
        
      }
      
    }
  
  }
  
}
