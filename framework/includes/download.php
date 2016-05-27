<?php

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

// Add AJAX action to download PDF file
function export2pdf_download_file()
{

  export2pdf_log( 'download', "Starting AJAX download action" );

  try
  {
  
    // Get entry and template
    $template = \Export2Pdf\Template::get( $_GET[ 'template' ] );
    $entry    = $template->entry( $_GET[ 'entry' ] );
    
    // Set up options
    $options = $_GET;
    
    $export  = \Export2Pdf\Export::create( $template, $entry, $options );
    
    $use_progress_indicator = \Export2Pdf\Settings::get( 'download_show_progress' );
    //if ( isset( $_GET[ 'download' ] ) )
    //  $use_progress_indicator = false;
    
    if ( $use_progress_indicator )
    {
    
      export2pdf_log( 'download', "Exporting with progress indicator" );
      $export->read_with_progress();
      
    }
    else
    {
    
      export2pdf_log( 'download', "Exporting directly to the browser" );
      $export->read();
      
    }
  
  }
  catch ( Exception $e )
  {
  
    $e->show();
    
  }
  
  export2pdf_log( 'download', "Finished AJAX download action" );

  // Clean up temporary files and folders
  \Export2Pdf\TempFile::clean_up();
  \Export2Pdf\TempGeneratedFile::clean_up();
  \Export2Pdf\TempFolder::clean_up();
  
  // Save logs to the database
  \Export2Pdf\Log::commit();
  
  wp_die();

}

add_action( 'wp_ajax_export2pdf-download', 'export2pdf_download_file' );
add_action( 'wp_ajax_nopriv_export2pdf-download', 'export2pdf_download_file' );
