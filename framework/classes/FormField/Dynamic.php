<?php

/**
 * A prototype for addons form entries
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class FormField_Dynamic extends FormField
{

  public function id()
  {
  
    // Split class name using _, and return the last part
    $klass       = get_class( $this );
    $klass_parts = explode( '_', $klass );
    return array_pop( $klass_parts );
    
  }

}
