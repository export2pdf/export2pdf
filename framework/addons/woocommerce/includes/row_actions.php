<?php

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Links in the list of orders to export to PDF
 *
 * @see https://www.skyverge.com/blog/add-actions-to-woocommerce-my-orders-table/
 */
 
add_filter( 'woocommerce_admin_order_actions_end', function ( $order ) {

  // Check if there are any associated templates
  $export_template = NULL;
  
  $templates = \Export2Pdf\Template::all();
  foreach ( $templates as $template )
  {
  
    // Loop through all available templates to see if one can be used for WooCommerce
    
    if ( ! $template->available() )
      continue;
    
    if ( 
          ( $template->form == 'orders' )
      and ( $template->addon == 'WooCommerce' )
    )
    {

      // Create an export2pdf action button
      $entry = new \Export2Pdf\WooCommerceOrder( $order->id );
      $download_url = export2pdf_download_link( $template, $entry );
      
      echo sprintf(
        '<a class="button export-to-pdf" href="%s" target="_blank" title="%s (%s)">%s</a>',
        $download_url,
        $template->name(),
        __( 'Export to PDF', 'export2pdf' ),
        $template->name()
      );
    
    }
    
  }

}, 10, 1);
