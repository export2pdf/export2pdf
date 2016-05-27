<?php

/**
 * A prototype for addons form entries
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class FormidableFormEntry extends FormEntry
{
  
  public $entry_data;
  
  /**
   * Get entry name
   * First, try parent method
   * Then, try Formidable entry name
   *
   * @return string Entry title
   */
  public function name()
  {
    $name = parent::name();
    if ( ! $name )
      $name = $this->entry_data->name;
    return $name;
  }
  
  /**
   * Constructor
   * 
   * @param $id string Formidable Form Entry ID
   */
  public function __construct( $id )
  {
  
    global $wpdb;
    
    $query = "
      SELECT 
        * 
      FROM 
        `" . $wpdb->prefix . "frm_items` 
      WHERE 
        `id` = " . Db::escape( $id )
    ;
    
    $rows = Db::_select( $query );
    
    if ( count( $rows ) != 1 )
      throw new Exception( 'Form entry #' . $id . ' not found.' );
      
    $row = array_shift( $rows );
    
    $this->entry_data = $row;
    $this->id         = $id;
    
  }
  
  /**
   * Get entry values
   *
   * @return array Array of values in format: $field_id => $value
   */
  public function values()
  {
  
    $formidable_entry = \FrmEntry::getOne( $this->id(), true);
    
    $values = array();
    
    foreach ( $this->form()->fields() as $field )
    {
    
      // Get value from Formidable

      try
      {
      
        // Prepare display value
        $formidable_field = \FrmField::getOne( $field->id() );
        $embedded_field_id = ( $formidable_entry->form_id != $formidable_field->form_id ) ? 'form' . $formidable_field->form_id : 0;
        $options = array(
          'type'              => $formidable_field->type, 
          'post_id'           => $formidable_entry->post_id,
          'show_filename'     => true, 
          'show_icon'         => true, 
          'entry_id'          => $formidable_entry->id,
          'embedded_field_id' => $embedded_field_id,
        );
        $formidable_value = \FrmEntriesHelper::prepare_display_value( $formidable_entry, $formidable_field, array() );
        
        // And format it
        $options = array(
          'type'     => $formidable_field->type, 
          'entry_id' => $formidable_entry->id,
        );
        $field_value = \FrmEntriesHelper::display_value( $formidable_value, $formidable_field, $options );
        
      }
      catch ( Exception $e )
      {
        $field_value = '';
      }
      
      // Store our value
      $value = new FormValue();
      $value->entry = $this;
      $value->field = $field;
      $value->value = $field_value;
      $values[] = $value;
    }
    
    return $values;
    
  }

}
