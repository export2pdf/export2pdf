<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * A prototype for form field value
 */

class FormValue
{

  public $entry;
  public $field;
  
  public $value;

  /**
   * Get form
   *
   * @return Form Form that this value belongs to
   */
  public function form()
  {
    return $this->entry()->form();
  }

  /**
   * Get entry
   *
   * @return FormEntry Form entry that this value belongs to
   */
  public function entry()
  {
    return $this->entry;
  }
  
  /**
   * Get field
   *
   * @return FormField Form field that this value belongs to
   */
  public function field()
  {
    return $this->field;
  }
  
  /**
   * Get field value
   *
   * @return mixed Entry field value (formatted)
   */
  public function get_value()
  {
    return $this->value;
  }

}
