<?php

/**
 * Text field in PDFs
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
class PdfField_Text extends PdfField
{

  public $description = "a text field";
  
}
