<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Product Availability
 */

class WooCommerceProductField_Availability extends WooCommerceProductField
{

  public $name  = 'Availability';
  
  public function value( $entry )
  {
  
    $availability_information = $entry->product()->get_availability();
    
    if ( isset( $availability_information[ 'availability' ] ) )
      return $availability_information[ 'availability' ];
      
    return '';
    
  }
  
  public function visible()
  {
    return ( get_option( 'woocommerce_manage_stock' ) == 'yes' );
  }

}
