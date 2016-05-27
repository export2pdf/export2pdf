<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_ShippingAddressLine1 extends WooCommerceOrderField
{

  public $name  = 'Shipping Address Line 1';
  public $group = 'Shipping';
  
  public function value( $entry )
  {
    return $entry->order()->shipping_address_1;
  }

}
