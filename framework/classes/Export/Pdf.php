<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Fills in a PDF file with fields
 */

class Export_Pdf extends Export
{
  
  /**
   * Prepare values for Java program
   * (special formatting required to simplify things)
   */
  public function prepare_values()
  {
  
    // Prepare fields values
    $fields = new \stdClass();
    
    $all_maps = count( $this->maps );
    
    foreach ( $this->maps as $counter => $map )
    {
    
      // Extract mapping options
      $row = $map->options_array();
    
      // Get formatted value
      $key = $map->field()->name();
      $value = Format::map( $map );
      
      // Add a row in JSON file
      $row[ 'value' ]  = $value . '';
      
      if ( ! empty( $map->formatting ) )
        $row[ 'format' ] = $map->formatting;
      
      $fields->{ $key } = $row;
      
      Progress::set( $counter / $all_maps * 100.0 );
      
    }
    
    // Prepare template options
    $options = $this->template->post_process_options();
    
    // Set output format
    $data = new \stdClass();
    $data->fields  = $fields;
    $data->options = $options;
    
    return $data;
    
  }
  
  /**
   * Export to PDF
   */
  public function generate()
  {

    
    Progress::step( "Preparing file...", 5, 30 );
    // Prepare a JSON file for Java program
    $json_data = json_encode( $this->prepare_values() );
    
    // For debugging:
    // print_r( $this->prepare_values() ); exit;
    
    if ( class_exists( "Export2Pdf\\Export2PdfOffline" ) )
    {
    
      // We're in offline mode. Export PDF directly
      Progress::step( 
        __( 'Preparing file...', 'export2pdf' ),
        30, 100 
      );
      Progress::pulsate();
      $binary_pdf_output = ApiRequest_ExportPdf::perform( $this->template_path(), $json_data );
    
    }
    else
    {
    
      try
      {
      
        Progress::step( 
          __( 'Exporting...', 'export2pdf' ),
          10, 70 
        );
      
        // Try to export a PDF
        // If the PDF hasn't been uploaded yet, then PdfNotUploaded_Error exception will be thrown
        $binary_pdf_output = ApiRequest_ExportPdf::perform( 'hash:' . $this->hash(), $json_data );
      
      }
      catch ( PdfNotUploaded_Error $e )
      {

        if ( Debug::enabled() )
        {
          Progress::step( "Uploading file to server...", 10, 70 );
        }
        else
        {
          Progress::step( 
            __( 'Exporting...', 'export2pdf' ), 
            10, 70 
          );
        }

        // Upload PDF
        ApiRequest_UploadPdf::perform( $this->template_path(), $this->hash() );
        
        if ( Debug::enabled() )
        {
          Progress::step( "Downloading file from server...", 70, 100 );
        }
        else
        {
          Progress::step( 
            __( 'Exporting...', 'export2pdf' ), 
            70, 100 
          );
        }
        
        // And export data
        $binary_pdf_output = ApiRequest_ExportPdf::perform( 'hash:' . $this->hash(), $json_data );
      
      }
      
    }
        
    // Store response in a file
    $output_file = new TempFile( 'pdf' );
    $output_file->write( $binary_pdf_output );
    $this->path = $output_file->path();
  
  }
  
}
