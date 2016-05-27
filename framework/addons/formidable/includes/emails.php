<?php

/**
 * E-mail actions for Formidable Forms
 */
 
add_filter( 'frm_notification_attachment', function ( $attachments, $form, $arguments ) {

  // Loop through all available templates
  $templates = \Export2Pdf\Template::all();
  foreach ( $templates as $template )
  {
  
    try
    {
    
      // Select only templates with addon = Formidable
      if ( ! ( $template->addon() instanceof \Export2Pdf\Formidable ) )
        continue;
        
      // Select only templates with form = current form ($form variable)
      if ( $template->form()->internal_id() != $form->id )
        continue;
        
      // Select only templates with email action = current action
      $email_action_key = $arguments['email_key'];
      if ( ! $template->has_action( $email_action_key ) )
        continue;
        
      // Get corresponding entry ID
      if ( 
           ! is_array( $arguments )
        or ! isset( $arguments['entry'] )
        or ! isset( $arguments['entry']->id )
      )
      {
        throw new Exception( "Entry ID is not set" );
      }
      
      $entry_id = $arguments['entry']->id;
      $entry = $template->entry( $entry_id );
        
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
