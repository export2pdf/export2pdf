<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Upload a PDF file to API Server
 */

class ApiRequest_UploadPdf extends ApiRequest
{
  
  public $arguments = array( 'pdf_file', 'pdf_key' );

}
