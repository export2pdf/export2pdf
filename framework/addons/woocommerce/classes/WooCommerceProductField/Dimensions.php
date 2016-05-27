<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Product Dimensions
 */

class WooCommerceProductField_Dimensions extends WooCommerceProductField
{

  public $name  = 'Dimensions';
  
  public function value( $entry )
  {
    return $entry->product()->get_dimensions();
  }

}
