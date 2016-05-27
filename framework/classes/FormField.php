<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * A prototype for addons form fields
 */

class FormField
{

  public $form;
  
  public $id;
  public $name;
  
  public $group = NULL;
  
  public $default_formatting         = NULL;
  public $default_formatting_options = array();
  
  public $template;

  /**
   * Get field ID
   *
   * @return int|null Field ID
   */
  public function id()
  {
    return $this->id;
  }
  
  /**
   * Get field name
   *
   * @return string Field name
   */
  public function name()
  {
    return $this->name;
  }

  /**
   * Get field group
   *
   * @return string Field group
   */
  public function group()
  {
    return $this->group;
  }
  
  /**
   * Get a shortcode to insert a field into an HTML designer
   */
  public function shortcode()
  {
    
    $options = $this->default_formatting_options;
    
    // Add default formatting
    if ( $this->default_formatting )
      $options[ 'formatting' ] = $this->default_formatting;
    
    return ShortcodeField::generate( $this, $options );
    
  }

}
