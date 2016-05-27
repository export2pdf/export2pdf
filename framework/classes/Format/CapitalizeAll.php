<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * CAPITALIZE ALL
 */
 
class Format_CapitalizeAll extends Format
{
  
  public $name  = 'CAPITALIZE ALL TEXT';
  public $group = 'Text';
  
  public function process( $value, $options = array() )
  {

    $upcase    = ( function_exists( 'mb_strtoupper' ) ? 'mb_strtoupper' : 'strtoupper' );
    $downcase  = ( function_exists( 'mb_strtolower' ) ? 'mb_strtolower' : 'strtolower' );
    
    $value = $upcase( $value );
    
    return $value;
    
  }
  
}


