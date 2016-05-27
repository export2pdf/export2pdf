<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_BillingAddressLine1 extends WooCommerceOrderField
{

  public $name  = 'Billing Address Line 1';
  public $group = 'Billing';
  
  public function value( $entry )
  {
    return $entry->order()->billing_address_1;
  }

}
