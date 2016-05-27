<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Addon template
 * 
 * Used to integrate support for other plugins with Export2PDF
 */
 
class Addon
{

  const TITLE = 'Sample Addon';
  const URL   = 'http://addon.com/';
  
  public static $addons = array();
  
  public function __construct()
  {
    static::$addons[] = $this;
  }
  
  /** 
   * Addon class reference
   */
  public function id()
  {
    $klass = get_called_class();
    $klass = str_replace( "Export2Pdf\\", '', $klass );
    return $klass;
  }
  
  /**
   * Addon name
   */
  public function name()
  {
    return static::TITLE;
  }
  
  /**
   * Addon URL
   */
  public function url()
  {
    return static::URL;
  }
  
  /**
   * Checks if the addon is available
   * E.g.: if the corresponding plugin is activated or not
   */
  public function available()
  {
    return false;
  }

  
  /**
   * Get list of available addons
   */
  public static function all()
  {
    return array_filter(
      
      static::$addons,
      
      function ( $addon )
      {
        return $addon->available();
      }
      
    );
  }

}
