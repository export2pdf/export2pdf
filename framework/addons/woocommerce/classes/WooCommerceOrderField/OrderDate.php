<?php

/**
 * Order Date
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_OrderDate extends WooCommerceOrderField
{

  public $name  = 'Order Date';
  public $group = 'Order';
  
  public $default_formatting         = 'DateTime';
  
  public function __construct()
  {
    $this->default_formatting_options = array(
      'date_format' => get_option( 'date_format' ),
    );
  }
  
  public function value( $entry )
  {
    return $entry->order()->order_date;
  }

}
