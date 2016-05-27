<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_ShippingFirstName extends WooCommerceOrderField
{

  public $name  = 'Shipping First Name';
  public $group = 'Shipping';
  
  public function value( $entry )
  {
    return $entry->order()->shipping_first_name;
  }

}
