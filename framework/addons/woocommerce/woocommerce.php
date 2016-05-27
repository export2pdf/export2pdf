<?php

/**
 * Export2Pdf addon for WooCommerce Orders
 */

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerce extends Addon
{

  const TITLE = 'WooCommerce';
  const URL   = 'https://wordpress.org/plugins/woocommerce/';
  
  public $forms;
  
  /**
   * Checks if the addon is available
   * E.g.: if the corresponding plugin is activated or not
   */
  public function available()
  {
    return class_exists( '\WC_Product_Factory' );
  }
  
  /**
   * Get list of forms
   *
   * @return array Array of FormidableForm's
   */
  public function forms()
  {
  
    if ( $this->forms )
      return $this->forms;
  
    $forms = array();
    
    $forms[] = new WooCommerceOrders();
    $forms[] = new WooCommerceProducts();
    
    $this->forms = $forms;
    return $forms;
    
  }

}

export2pdf_initialize_framework( __DIR__ );
new WooCommerce();

