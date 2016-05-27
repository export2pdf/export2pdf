<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Insert an <img /> tag into an HTML template
 */
 
class Format_ImageHtml extends Format
{
  
  public $name  = 'Image';
  public $group = 'Media';
  
  public function visible()
  {
    return ( $this->template and ( $this->template instanceof TemplateHtml ) );
  }
  
  public function process( $value, $options = array() )
  {
    
    $value = Tools::trim( $value );
    
    // This is an HTML template, so convert image src to <img /> tag
    return "<img src='$value' />";
    
  }
  
}


