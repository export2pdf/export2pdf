<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Product SKU
 */

class WooCommerceProductField_Sku extends WooCommerceProductField
{

  public $name  = 'SKU';
  
  public function value( $entry )
  {
    return $entry->product()->get_sku();
  }

}
