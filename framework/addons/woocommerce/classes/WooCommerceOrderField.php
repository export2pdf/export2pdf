<?php

/**
 * A field from a WooCommerce order
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField extends FormField_Dynamic
{

  public static $groups = array(
    'Order', 
    'Customer', 
    'Billing',
    'Shipping',
    'Shop',
  );

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
