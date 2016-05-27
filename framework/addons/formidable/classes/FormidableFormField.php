<?php

/**
 * A Formidable form field
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class FormidableFormField extends FormField
{
  
  public $field_data;
  
  /**
   * Get form name
   *
   * @return string Form name
   */
  public function name()
  {
    return strip_tags( $this->field_data->name );
  }
  
  /**
   * Get form ID
   *
   * @return string Form name
   */
  public function internal_id()
  {
    return $this->field_data->id;
  }
  
  /**
   * Constructor
   * 
   * @param $field_data object Row from the database that has field properties
   */
  public function __construct( $field_data )
  {
    
    $this->field_data = $field_data;
    $this->id         = $field_data->id;
    
  }

}
