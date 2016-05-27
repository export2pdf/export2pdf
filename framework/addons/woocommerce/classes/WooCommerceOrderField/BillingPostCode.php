<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_BillingPostCode extends WooCommerceOrderField
{

  public $name  = 'Billing Post Code';
  public $group = 'Billing';
  
  public function value( $entry )
  {
    return $entry->order()->billing_post_code;
  }

}
