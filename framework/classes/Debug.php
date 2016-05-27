<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Manages debugging information
 */

class Debug
{

  public static function enabled()
  {
    return intval( Settings::get( 'debug_mode' ) );
  }

}


