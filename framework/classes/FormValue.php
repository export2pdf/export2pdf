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
  public $map;
  
  public $value;             // Field value as string
  public $values = NULL;     // Some fields may have multiple values

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
  
    $value = $this->value;
    
    if ( $this->map )
    {
    
      // If the corresponding PDF field is a checkbox or a radio,
      // then we need to select only valid values
      $pdf_field      = $this->map->field();
    
      if (
           ( $pdf_field instanceof PdfField_Radio )
        or ( $pdf_field instanceof PdfField_Checkbox )
      )
      {
      
        $valid_values = $pdf_field->values();
        
        // If this value is invalid, then let's go through all submitted values
        if ( ! in_array( $value, $valid_values ) )
        {
        
          // Let's see if this form value has a list of values
          if ( 
                $this->values
            and is_array( $this->values )
            and count( $this->values )
          )
          {
            
            foreach ( $this->values as $possible_value )
              if ( in_array( $possible_value, $valid_values ) )
                return $possible_value;
            
          }
          
        }
      
      }
    
    }
            
    return $value;
    
  }

}
