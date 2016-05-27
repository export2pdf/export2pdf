<?php

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

add_action( 'plugins_loaded', function () {

  // Translate field groups
  foreach ( \Export2Pdf\WooCommerceOrderField::$groups as $index => $group_name )
  {
    \Export2Pdf\WooCommerceOrderField::$groups[ $index ] = __( $group_name, 'woocommerce' );
  }

});
