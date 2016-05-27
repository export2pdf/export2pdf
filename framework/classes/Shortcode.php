<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Shortcode prototype
 */
 
class Shortcode
{
  
  const NAME = 'unknown_shortcode';
  
  public static $defaults = array(
  );
  
  /**
   * Parse shortcode arguments
   * 
   * @param $attributes array Shortcode options
   *
   * @return string URL to download the file
   */
  public static function parse_arguments( $attributes = array() )
  {
    
    // Merge default options with supplied arguments
    $attributes = array_merge( static::$defaults, $attributes );
    
    // Process arguments (validate, filter, ...)
    
    return $attributes;
  
  }
  
  /**
   * Parse shortcode arguments, and output shortcode value
   * 
   * @param $attributes array Shortcode options
   *
   * @return string Shortcode output
   */
  public static function process( $attributes = array() )
  {
    
    return 'shortcode output';
  
  }
  
  public static function _generate( $attributes = array() )
  {

    // Format and validate options
    // $attributes = self::parse_arguments( $attributes );
    
    // Reduce to WP format
    $keys   = array_keys( $attributes );
    $values = array_values( $attributes );
    $attributes = implode( '', array_map( function ( $key, $value ) { 
    
      // Convert object to string
      if ( $value instanceof TemplateMapOption )
        $value = $value->value;
      
      $attribute = " $key=\"$value\"";
      return $attribute;
      
    }, $keys, $values ));
    
  
    $shortcode = '[' . static::NAME . $attributes . ']';
    
    return $shortcode;
  
  }

}
