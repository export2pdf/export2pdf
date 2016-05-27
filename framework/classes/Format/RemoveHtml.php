<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Remove HTML tags in a field's value
 */
 
class Format_RemoveHtml extends Format
{
  
  public $name  = 'Remove HTML tags';
  public $group = 'Text';
  
  public function process( $value, $options = array() )
  {
    
    $value = strip_tags( $value );
    
    // Remove empty lines too
    $format_remove_lines = new Format_RemoveLines();
    $value = $format_remove_lines->process( $value, $options ); 
    
    // And decode any special characters
    if ( function_exists( "html_entity_decode" ) )
      $value = html_entity_decode( $value );
    
    return $value;
    
  }
  
}


