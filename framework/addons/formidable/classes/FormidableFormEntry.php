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
    
      // Prepare the value
      $value        = new FormValue();
      $value->entry = $this;
      $value->field = $field;
    
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
        $value->value  = \FrmEntriesHelper::display_value( $formidable_value, $formidable_field, $options );
        $value->values = array( $value->value );
        
        // Well, many validations below, because I don't know how reliable Formidable plugin is
        
        // Set raw value of this field
        // (might be used in formatting options)
        if ( 
              isset( $formidable_entry->metas )
          and is_array( $formidable_entry->metas )
          and isset( $formidable_entry->metas[ $formidable_field->id ] )
        )
        {
          
          $raw_value = $formidable_entry->metas[ $formidable_field->id ];
          
          // Formidable sets date to YYYY-MM-DDD
          
          $value->raw_value = $raw_value;
        
        }
        
        // If this is a checkbox, then we don't want the formatted value
        // We will assign an array of values instead
        if ( 
              isset( $formidable_field->type )
          and isset( $formidable_entry->metas )
          and ( 
                   ( $formidable_field->type == "checkbox" )
                or ( $formidable_field->type == "radio" )
                or ( $formidable_field->type == "lookup" )
                or ( $formidable_field->type == "scale" )
                or ( $formidable_field->type == "select" )
          )
          and isset( $formidable_entry->metas[ $formidable_field->id ] )
          and is_array( $formidable_entry->metas[ $formidable_field->id ] )
          and count( $formidable_entry->metas[ $formidable_field->id ] )
        )
        {
        
          $value->values = $formidable_entry->metas[ $formidable_field->id ];
        
        }
        
        // To handle checkbox and radio labels, we will also append them to the list of possible options
        if ( 
        
              isset( $formidable_field->type )
          and ( 
                   ( $formidable_field->type == "checkbox" )
                or ( $formidable_field->type == "radio" )
                or ( $formidable_field->type == "lookup" )
                or ( $formidable_field->type == "scale" )
                or ( $formidable_field->type == "select" )
          )
          and isset( $formidable_field->options )
          and is_array( $formidable_field->options )
          and count( $formidable_field->options )
        )
        {
        
          foreach ( $formidable_field->options as $option ) 
          {
          
            if ( 
                  is_array( $option )
              and isset( $option[ 'label' ] )
              and !empty( $option[ 'label' ] )
              and in_array( $option[ 'label' ], $value->values )
            )
            {
            
              $value->values[] = $option[ 'value' ];
              
            }
            
          }
        
        }
        
        /*
        // For debugging:
        if ( $formidable_field->type == "time" )
        {
          var_dump( $formidable_value, $formidable_entry, $formidable_field, $value->value, $value->values, $value->raw_value );
          exit;
        }
        */
        
      }
      catch ( Exception $e )
      {
        
        $value->value = '';
        
      }
      
      // Store the value
      $values[] = $value;
      
    }
    
    // For debugging:
    // print_r( $values ); exit;
    
    return $values;
    
  }

}
