<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_ShippingCity extends WooCommerceOrderField
{

  public $name  = 'Shipping City';
  public $group = 'Shipping';
  
  public function value( $entry )
  {
    return $entry->order()->shipping_city;
  }

}
