<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Export HTML to a PDF file (HTML Template) and return the preview as a PNG image
 *
 * PNG image is used in template thumbnails in the dashboard
 */
 
class ApiRequest_ExportHtmlPreview extends ApiRequest
{
  
  public $arguments = array( 'html', 'options' );
  public $decode_answer = false;

}
