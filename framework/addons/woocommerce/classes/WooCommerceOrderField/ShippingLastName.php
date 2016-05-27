<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
  
class WooCommerceOrderField_ShippingLastName extends WooCommerceOrderField
{

  public $name  = 'Shipping Last Name';
  public $group = 'Shipping';
  
  public function value( $entry )
  {
    return $entry->order()->shipping_last_name;
  }

}
