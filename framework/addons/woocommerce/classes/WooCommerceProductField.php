<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * A field from a WooCommerce product
 */
 
class WooCommerceProductField extends FormField_Dynamic
{

  /**
   * Get a value for a field
   *
   * @param $entry WooCommerceOrder
   */
  public function value( $entry )
  {
    return '';
  }

}
