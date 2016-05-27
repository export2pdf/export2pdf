<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Remove empty lines
 */
 
class Format_RemoveLines extends Format
{
  
  public $name  = 'Remove empty lines';
  public $group = 'Text';
  
  public function process( $value, $options = array() )
  {
    
    $value = str_replace( "\r", "", $value ); 
    
    $lines = explode( "\n", $value );
    foreach ( $lines as $line_number => $line )
    {
    
      $line = preg_replace( '/(^\s+)|(\s+$)/us', '', $line );
      
      if ( strlen( $line ) )
        $lines[ $line_number ] = $line;
      else
        unset( $lines[ $line_number ] );
        
    }
    
    $lines = array_values( $lines );
    $value = implode( "\n", $lines );
    
    return $value;
    
  }
  
}


