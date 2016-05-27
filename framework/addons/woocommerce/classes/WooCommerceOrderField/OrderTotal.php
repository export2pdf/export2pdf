<?php

/**
 * FirstName LastName
 * Address
 * Country
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_OrderTotal extends WooCommerceOrderField
{

  public $name  = 'Order Total';
  public $group = 'Order';
  
  public $default_formatting = 'WooPrice';
  
  public function value( $entry )
  {
    return $entry->order()->get_total();
  }

}
