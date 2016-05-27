<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Export HTML to a PDF file (HTML Template)
 */

class ApiRequest_ExportHtml extends ApiRequest
{
  
  public $arguments = array( 'html', 'options' );
  public $decode_answer = false;

}
