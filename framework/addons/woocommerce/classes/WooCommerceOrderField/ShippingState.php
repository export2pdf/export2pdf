<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_ShippingState extends WooCommerceOrderField
{

  public $name  = 'Shipping State';
  public $group = 'Shipping';
  
  public function value( $entry )
  {
    return $entry->order()->shipping_state;
  }

}
