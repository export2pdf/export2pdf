<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Framework settings
 */
 
class Settings
{

  public static $options = NULL;
  
  public static $default_options = array(
    'measurement_unit'       => 'in',
    'download_show_progress' => 1,
    'debug_mode'             => 0,
  );
  
  /**
   * Get a value
   *
   * @param $setting_name string Name of a setting
   */
  public static function get( $setting_name )
  {
  
    // Load options automatically
    if ( ! self::$options )
    {
      if ( Db::table_exists( Option::TABLE ) )
        self::$options = Option::all();
      else
        self::$options = array();
    }
    
    // Try to find the option based on $setting_name
    foreach ( self::$options as $option )
      if ( $option->key == $setting_name )
        return $option->value;
        
    // Get standard value
    if ( isset( self::$default_options[ $setting_name ] ) )
      return self::$default_options[ $setting_name ];
        
    // Option hasn't been found
    return '';
    
  }
  
  
  /**
   * Set a value
   */
  public static function set( $setting_name, $setting_value )
  {   

    // Set up self::$options
    self::get( $setting_name );
    
    // Try to find the option based on $setting_name
    foreach ( self::$options as $option )
      if ( $option->key == $setting_name )
      {
      
        $option->value = $setting_value;
        $option->save();
        
        // Reset the list of options
        self::$options = null;
        
        return;
        
      }
     
    // This option doesn't exist. Create it.
    $option        = new Option();
    $option->key   = $setting_name;
    $option->value = $setting_value;
    $option->save();
    
  }

}
