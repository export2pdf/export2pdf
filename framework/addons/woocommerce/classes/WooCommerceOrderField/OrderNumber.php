<?php

/**
 * Order number
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_OrderNumber extends WooCommerceOrderField
{

  public $name  = 'Order Number';
  public $group = 'Order';
  
  public function value( $entry )
  {
    return $entry->order()->get_order_number();
  }

}
