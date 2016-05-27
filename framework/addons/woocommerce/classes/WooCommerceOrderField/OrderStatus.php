<?php

/**
 * Order Status
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_OrderStatus extends WooCommerceOrderField
{

  public $name  = 'Order Status';
  public $group = 'Order';
  
  public function value( $entry )
  {
  
    // Get status ID
    $status = $entry->order()->get_status();
    
    // Transform status ID into human text
    foreach ( wc_get_order_statuses() as $status_id => $status_title )
      if ( $status_id == "wc-{$status}" )
        return $status_title;
        
    return $status;
  }

}
