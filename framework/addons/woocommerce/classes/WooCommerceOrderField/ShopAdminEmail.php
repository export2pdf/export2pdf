<?php

/**
 * FirstName LastName
 * Address
 * Country
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrderField_ShopAdminEmail extends WooCommerceOrderField
{

  public $name  = 'Shop Administrator Email';
  public $group = 'Shop';
  
  public function value( $entry )
  {
    return get_option( 'admin_email' );
  }

}
