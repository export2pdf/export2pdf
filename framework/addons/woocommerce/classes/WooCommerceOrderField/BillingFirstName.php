<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_BillingFirstName extends WooCommerceOrderField
{

  public $name  = 'Billing First Name';
  public $group = 'Billing';
  
  public function value( $entry )
  {
    return $entry->order()->billing_first_name;
  }

}
