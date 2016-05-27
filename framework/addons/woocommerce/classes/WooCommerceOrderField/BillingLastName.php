<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_BillingLastName extends WooCommerceOrderField
{

  public $name  = 'Billing Last Name';
  public $group = 'Billing';
  
  public function value( $entry )
  {
    return $entry->order()->billing_last_name;
  }

}
