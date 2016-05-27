<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Convert a float to a WooCommerce price, using WooCommerce formatting function wc_price()
 */
 
class Format_WooPrice extends Format
{
  
  public $name  = 'Price (WooCommerce)';
  public $group = 'Numbers';
  
  public function process( $value, $options = array() )
  {
    
    $value = floatval( $value );
    $value = wc_price( $value );
    
    return $value;
    
  }
  
  public function available()
  {
    return function_exists( 'wc_price' );
  }
  
}


