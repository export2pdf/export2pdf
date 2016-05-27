<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_BillingCompany extends WooCommerceOrderField
{

  public $name  = 'Billing Company';
  public $group = 'Billing';
  
  public function value( $entry )
  {
    return $entry->order()->billing_company;
  }

}
