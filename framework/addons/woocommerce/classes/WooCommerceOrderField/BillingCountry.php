<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_BillingCountry extends WooCommerceOrderField
{

  public $name  = 'Billing Country';
  public $group = 'Billing';
  
  public function value( $entry )
  {
  
    $country_code = $entry->order()->billing_country;
    
    // Transform country code into country name
    if ( 
          WC() 
      and WC()->countries
      and WC()->countries->countries 
      and isset( WC()->countries->countries[ $country_code ] )
    )
    {
      return WC()->countries->countries[ $country_code ];
    }
    
    return $country_code;
    
  }

}
