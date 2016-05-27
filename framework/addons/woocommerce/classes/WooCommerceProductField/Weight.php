<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Product Weight
 */

class WooCommerceProductField_Weight extends WooCommerceProductField
{

  public $name  = 'Weight';
  
  public function value( $entry )
  {
    return $entry->product()->get_weight();
  }

}
