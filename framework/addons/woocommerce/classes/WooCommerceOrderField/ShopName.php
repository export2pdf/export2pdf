<?php

/**
 * FirstName LastName
 * Address
 * Country
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_ShopName extends WooCommerceOrderField
{

  public $name  = 'Shop Name';
  public $group = 'Shop';
  
  public function value( $entry )
  {
    return get_option( 'blogname' );
  }

}
