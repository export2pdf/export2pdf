<?php

/**
 * Radio field in PDFs
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
class PdfField_Checkbox extends PdfField
{

  public $description = "a checkbox";
  
  /** 
   * Remove 'Off' from field values
   */
  public function values()
  {
    $values = parent::values();
    foreach ( $values as $index => $value )
      if ( $value == 'Off' )
        unset( $values[ $index ] );
    return array_values( $values );
  }
  
}
