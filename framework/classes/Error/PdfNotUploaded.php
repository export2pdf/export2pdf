<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Indicates that we need to upload a PDF before exporting it
 */

class PdfNotUploaded_Error extends Exception
{

}
