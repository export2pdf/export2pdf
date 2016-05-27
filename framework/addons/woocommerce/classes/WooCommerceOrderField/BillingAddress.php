<?php

/**
 * FirstName LastName
 * Address
 * Country
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_BillingAddress extends WooCommerceOrderField
{

  public $name  = 'Billing Address';
  public $group = 'Customer';
  
  public function value( $entry )
  {
    return $entry->order()->get_formatted_billing_address();
  }

}
