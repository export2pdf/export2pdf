<?php

/**
 * FirstName LastName
 * Address
 * Country
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_PaymentMethod extends WooCommerceOrderField
{

  public $name  = 'Payment method';
  public $group = 'Order';
  
  public function value( $entry )
  {
    return $entry->order()->payment_method_title;
  }

}
