<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_BillingCity extends WooCommerceOrderField
{

  public $name  = 'Billing City';
  public $group = 'Billing';
  
  public function value( $entry )
  {
    return $entry->order()->billing_city;
  }

}
