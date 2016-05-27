<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Configures Export2PDF API
 */

class Api
{

  // For production:
  const ENDPOINT = 'https://api.export2pdf.com/api/';
  
  // For testing:
  // const ENDPOINT = 'http://localhost/api/';
  
  public static $key = NULL;
  
  /**
   * Checks if client's website is activated
   */
  public static function activated()
  {
    return self::$key;
  }
  
  /**
   * Checks if API should be used
   */
  public static function enabled()
  {
    return ! class_exists( "Export2Pdf\\Export2PdfOffline" );
  }
  
  /** 
   * Get API key
   */
  public static function key()
  {
  
    // Return static $key if it is set
    if ( self::$key ) 
      return self::$key;
      
    return strtoupper( md5( AUTH_KEY ) );
  
  }

}
