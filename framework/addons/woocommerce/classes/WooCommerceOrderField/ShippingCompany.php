<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_ShippingCompany extends WooCommerceOrderField
{

  public $name  = 'Shipping Company';
  public $group = 'Shipping';
  
  public function value( $entry )
  {
    return $entry->order()->shipping_company;
  }

}
