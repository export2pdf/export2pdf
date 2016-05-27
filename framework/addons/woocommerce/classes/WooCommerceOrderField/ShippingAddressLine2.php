<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_ShippingAddressLine2 extends WooCommerceOrderField
{

  public $name  = 'Shipping Address Line 2';
  public $group = 'Shipping';
  
  public function value( $entry )
  {
    return $entry->order()->shipping_address_2;
  }

}
