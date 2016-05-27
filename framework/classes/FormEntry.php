<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * A prototype for addons form entries
 */

class FormEntry
{

  public $form;
  
  public $id;
  public $name;

  /**
   * Get entry ID
   *
   * @return int|null Field ID
   */
  public function id()
  {
    return $this->id;
  }
  
  /**
   * Get entry name
   *
   * @return string Field name
   */
  public function name()
  {
    return $this->name;
  }
  
  /**
   * Get parent form
   *
   * @return Form Form that this entry belongs to
   */
  public function form()
  {
    return $this->form;
  }

  /**
   * Get entry field value
   *
   * @param $field FormField Field for which we need to get value
   * 
   * @return FormValue Value of $field for this $entry
   */
  public function value( $field )
  {
    foreach ( $this->values() as $value )
      if ( $value->field()->id() == $field->id() )
        return $value;
    return '';
  }

  
  /**
   * Get entry values
   *
   * @return array Array of values in format: $field_id => $value
   */
  public function values()
  {
    return array();
  }

}
