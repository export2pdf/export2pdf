<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Carriage return to comma
 *
 * E.g: "a\nb\nc" → "a, b, c"
 */
 
class Format_CarriageReturnToComma extends Format
{
  
  public $name  = 'Carriage return to comma';
  public $group = 'Text';
  
  public function process( $value, $options = array() )
  {
    
    $value = str_replace( "\r", "", $value );
    $value = str_replace( "\n", ", ", $value );
    $value = preg_replace( '/ +/', ' ', $value );
    $value = preg_replace( '/\, +$/', '', $value );
    
    return $value;
    
  }
  
}


