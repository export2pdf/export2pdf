<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Capitalize All Words
 */
 
class Format_CapitalizeWords extends Format
{
  
  public $name  = 'Capitalize All Words';
  public $group = 'Text';
  
  public function process( $value, $options = array() )
  {
    
    $upcase    = ( function_exists( 'mb_strtoupper' ) ? 'mb_strtoupper' : 'strtoupper' );
    $downcase  = ( function_exists( 'mb_strtolower' ) ? 'mb_strtolower' : 'strtolower' );
    
    $value = preg_replace_callback( 
      '/(^[a-z]| [a-z])/u', 
      function ( $matches ) use ( $upcase, $downcase ) 
      {
        return $upcase( $matches[ 1 ] );
      }, 
      $downcase( $value ) 
    );
    
    return $value;
    
  }
  
}


