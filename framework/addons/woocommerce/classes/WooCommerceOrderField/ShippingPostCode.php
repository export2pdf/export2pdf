<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_ShippingPostCode extends WooCommerceOrderField
{

  public $name  = 'Shipping Post Code';
  public $group = 'Shipping';
  
  public function value( $entry )
  {
    return $entry->order()->shipping_post_code;
  }

}
