<?php

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * E-mail actions for WooCommerce
 */
 
add_filter( 'woocommerce_email_attachments', function ( $attachments, $status, $order ) {

  // Loop through all available templates
  $templates = \Export2Pdf\Template::all();
  foreach ( $templates as $template )
  {
  
    try
    {
    
      // Select only templates with addon = Formidable
      if ( ! ( $template->addon() instanceof \Export2Pdf\WooCommerce ) )
        continue;
        
      // Select only templates with form = current form ($form variable)
      if ( $template->form()->id() != 'orders' )
        continue;
        
      // Filter by email type
      if ( ! $template->has_action( $status ) )
        return;
      
      $entry = $template->entry( $order->id );
        
      $attachments[] = export2pdf_export_template( $template, $entry );
    
    }
    catch ( Exception $e )
    {
    
      // An error occured
      // die( $e->getMessage() );
      
      export2pdf_log( 'error_attachment', $e );
    
    }
  
  }
  
  return $attachments;

}, 10, 3);
