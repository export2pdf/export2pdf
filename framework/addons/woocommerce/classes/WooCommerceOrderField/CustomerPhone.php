<?php

/**
 * Phone number of the customer
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_CustomerPhone extends WooCommerceOrderField
{

  public $name  = 'Customer Phone';
  public $group = 'Customer';
  
  public function value( $entry )
  {
    return $entry->order()->billing_phone;
  }

}
