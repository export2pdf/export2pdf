<?php

/**
 * FirstName LastName
 * Address
 * Country
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceProductField_Price extends WooCommerceProductField
{

  public $name  = 'Price';
  
  // public $default_formatting = 'WooPrice';
  
  public function value( $entry )
  {
    return $entry->product()->get_price_html();
  }

}
