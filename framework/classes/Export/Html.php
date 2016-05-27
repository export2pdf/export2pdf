<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Exports HTML code into a PDF file
 */

class Export_Html extends Export
{
  
  /**
   * Get HTML code and corresponding options
   */
  public function get_variables()
  {
    
    // Get template HTML code
    $html = @file_get_contents( $this->template_path );
    
    // Process shortcodes
    $html = wpautop( $html );
    $html = do_shortcode( $html );
    
    // Add styles
    $styles = $this->template->style();
    
    $html =
'<!DOCTYPE html>
<html>
  
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    
    <title>' . esc_html( $this->template->name() ) . '</title>
    
    <style type="text/css"> ' . $styles . ' </style>
  
  </head>
  
  <body>
  
    ' . $html . '
  
  </body>  
  
</html>';

    $options = $this->template->options_array();
    $options[ 'post_process' ] = $this->template->post_process_options();
    
    return array( $html, $options );
    
  }
  
  /**
   * Export to PDF
   */
  public function generate()
  {
  
    list( $html, $options ) = $this->get_variables();
    
    // Make API request
    $binary_pdf_output = ApiRequest_ExportHtml::perform( $html, $options );
        
    // Store response in a file
    $output_file = new TempFile( 'pdf' );
    $output_file->write( $binary_pdf_output );
    $this->path = $output_file->path();
  
  }
  
  /**
   * Export to PNG (generate previews
   */
  public function generate_preview()
  {
  
    list( $html, $options ) = $this->get_variables();

    // If preview doesn't correspond to the template,
    // then we will request it   
    if ( false )
    if ( ! $this->template->preview_changed() )
      throw new Exception( "Preview hasn't changed." );
      
    // Generate preview only when logged in as admin
    if ( ! Framework::is_admin() )
      throw new Exception( "User is not an admin. Not generating preview for him." );
  
    $binary_preview_output = ApiRequest_ExportHtmlPreview::perform( $html, $options );
    
    file_put_contents( $this->template->preview_path(), $binary_preview_output );
    file_put_contents( $this->template->preview_hash_path(), $this->template->hash() );
  
  }
  
}
