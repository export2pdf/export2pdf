<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * A prototype for addons form actions
 */

class FormAction
{

  public $form;
  
  public $id;
  public $name;

  /**
   * Get form ID
   *
   * @return int|null Field ID
   */
  public function id()
  {
    return $this->id;
  }
  
  /**
   * Get form name
   *
   * @return string Field name
   */
  public function name()
  {
    return $this->name;
  }

}
