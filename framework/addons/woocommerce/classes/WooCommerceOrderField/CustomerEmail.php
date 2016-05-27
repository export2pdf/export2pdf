<?php

/**
 * Email of the customer
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_CustomerEmail extends WooCommerceOrderField
{

  public $name  = 'Customer Email';
  public $group = 'Customer';
  
  public function value( $entry )
  {
    return $entry->order()->billing_email;
  }

}
