<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Get information about a PDF file (pages and fields)
 *
 * Pages: width, height, number
 *
 * Fields: name, position, width, height, type
 */

class ApiRequest_PdfInfo extends ApiRequest
{
  
  public $arguments = array( 'pdf_file' );

}
