<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Transform a page of a PDF file into an image
 */

class ApiRequest_PdfPreview extends ApiRequest
{
  
  public $arguments = array( 'pdf_file', 'page' );
  public $decode_answer = false;

}
