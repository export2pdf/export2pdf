<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_ShippingCountry extends WooCommerceOrderField
{

  public $name  = 'Shipping Country';
  public $group = 'Shipping';
  
  public function value( $entry )
  {
  
    $country_code = $entry->order()->shipping_country;
    
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
