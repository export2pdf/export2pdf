<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_BillingState extends WooCommerceOrderField
{

  public $name  = 'Billing State';
  public $group = 'Billing';
  
  public function value( $entry )
  {
    return $entry->order()->billing_state;
  }

}
