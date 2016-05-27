<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Prototype for HTML files
 */
 
class PdfHtmlFile extends PdfFile
{

  const PDF_TEMPLATE_FILENAME = 'template.html';
  public $type = 'PdfHtmlFile';
  
  public function pages()
  {
    return array();
  }
  
  public function fields()
  {
    return array();
  }

}
