<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * A prototype for addons forms
 */

class Form
{

  public static $forms;
  
  public $id;
  public $name;

  /**
   * Get form ID
   *
   * @return int|null Form ID
   */
  public function id()
  {
    return $this->id;
  }
  
  /**
   * Get form name
   *
   * @return string Form name
   */
  public function name()
  {
    return $this->name;
  }
  
  /**
   * Get a form field
   *
   * @param id int Field ID
   *
   * @return FormField 
   */
  public function field( $id )
  {
  
    foreach ( $this->fields() as $field )
      if ( $field->id() == $id )
        return $field;
     
    throw new Exception( 'Field ' . $id . ' doesn\'t exist for Form #' . $this->id() . ' (' . $this->name() . ')' );
  }

  /**
   * Helper function to get list of all forms
   *
   * @return array Array of forms
   */
  public static function all()
  {
    if ( static::$forms )
      return static::$forms;
    
    static::$forms = static::get_forms();
    
    return static::$forms;
  }
  
  /**
   * Get the list of e-mail actions that this form can send
   */
  public function emails()
  {
    return array();
  }
  
  /**
   * Get the list of entries for this form
   */
  public function entries()
  {
    return array();
  }
  
  /** 
   * Get link to view form
   *
   * @return string URL to view form
   */
  public function url()
  {
    return false;
  }
  
  /** 
   * Get link to view entries of this form
   *
   * @return string URL to view entries of this form
   */
  public function entries_url()
  {
    return false;
  }
  
  /** 
   * Get link to add an entry to this form
   *
   * @return string URL to add an entry to this form
   */
  public function add_entry_url()
  {
    return false;
  }
  
  /**
   * Get a form entry for this form
   *
   * @param $id int Entry ID
   *
   * @return FormEntry Corresponding form entry
   */
  public function entry( $id )
  {
    
    foreach ( $this->entries() as $entry )
      if ( $entry->id() == $id )
        return $entry;
    
    throw new Exception( 'This template doesn\'t have entry with ID #' . $id );
    
  }
  
  /**
   * Get the list of forms from the database.
   * This funciton should be implemented in the parent class.
   */
  public static function get_forms()
  {
    throw new Exception( 'get_forms() should be implemented in the parent class.' );
  }

}
