<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Export to a PDF file (PDF Template)
 */

class ApiRequest_ExportPdf extends ApiRequest
{
  
  public $arguments = array( 'pdf_file', 'json_data' );
  public $decode_answer = false;

}
