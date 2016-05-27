<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Product title
 */

class WooCommerceProductField_Title extends WooCommerceProductField
{

  public $name  = 'Title';
  
  public function value( $entry )
  {
    return $entry->name();
  }

}
