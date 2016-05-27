<?php

/**
 * FirstName LastName
 * Address
 * Country
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_ShippingMethod extends WooCommerceOrderField
{

  public $name  = 'Shipping Method';
  public $group = 'Order';
  
  public function value( $entry )
  {
    return $entry->order()->get_shipping_method();
  }

}
