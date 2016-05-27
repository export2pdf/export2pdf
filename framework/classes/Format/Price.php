<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Format as price
 */
 
class Format_Price extends Format_Number
{
  
  public $name = 'Price';
  
  public function examples()
  {
  
    $examples = array();
    
    $currencies           = array( '$', '€', '¥' );
    $thousands_separators = array( '', ' ', '.' );
    $decimal_separators   = array( ',', '.' );
    
    // TODO:
    // If they have WooCommerce installed,
    // then suggest their currency by default
    // if ( function_exists( 'get_woocommerce_currency_symbol' ) )
    //   $currencies = array( get_woocommerce_currency_symbol() );
      
    foreach ( $currencies as $currency )
      foreach ( array( '', $currency, $currency . ' ' ) as $prefix )
        foreach ( array( '', $currency, ' ' . $currency ) as $suffix )
          foreach ( array( 0, 2 ) as $decimals )
            foreach ( $thousands_separators as $thousands_separator )
              foreach ( $decimal_separators as $decimal_separator )
              {
                
                // Don't include formats with the same thousands and decimal separator
                if ( $decimal_separator == $thousands_separator )
                  continue;
                
                // Doesn't make sense to loop through separators if we don't have any decimals
                if ( $decimals == 0 && $decimal_separator )
                  continue;
                
                // Don't include formats with prefix and suffix
                if ( ( ! $prefix && ! $suffix ) || ( $prefix && $suffix ) )
                  continue;
                
                $example = array(
                  'number_format_decimals'            => $decimals,
                  'number_format_decimal_separator'   => $decimal_separator,
                  'number_format_thousands_separator' => $thousands_separator,
                  'number_format_prefix'              => $prefix,
                  'number_format_suffix'              => $suffix,
                );
                
                $examples[] = $example;
                
              }
    
    /*
    
      // $1,000.00
      $examples[] = array(
        'number_format_decimals'            => '2',
        'number_format_decimal_separator'   => '.',
        'number_format_thousands_separator' => ',',
        'number_format_prefix'              => '$',
        'number_format_suffix'              => '',
      );
      
      // $ 1,000.00
      $examples[] = array(
        'number_format_decimals'            => '2',
        'number_format_decimal_separator'   => '.',
        'number_format_thousands_separator' => ',',
        'number_format_prefix'              => '$ ',
        'number_format_suffix'              => '',
      );
      
      // 1 000,00€
      $examples[] = array(
        'number_format_decimals'            => '2',
        'number_format_decimal_separator'   => ',',
        'number_format_thousands_separator' => ' ',
        'number_format_prefix'              => '',
        'number_format_suffix'              => '€',
      );
      
      // 1 000,00 €
      $examples[] = array(
        'number_format_decimals'            => '2',
        'number_format_decimal_separator'   => ',',
        'number_format_thousands_separator' => ' ',
        'number_format_prefix'              => '',
        'number_format_suffix'              => ' €',
      );
      
      // 1.000,00 €
      $examples[] = array(
        'number_format_decimals'            => '2',
        'number_format_decimal_separator'   => ',',
        'number_format_thousands_separator' => '.',
        'number_format_prefix'              => '',
        'number_format_suffix'              => ' €',
      );
      
      // ¥1,234.56
      $examples[] = array(
        'number_format_decimals'            => '2',
        'number_format_decimal_separator'   => '.',
        'number_format_thousands_separator' => ',',
        'number_format_prefix'              => '¥',
        'number_format_suffix'              => '',
      );
      
    */
    
    return $examples;
  
  }
  
}


