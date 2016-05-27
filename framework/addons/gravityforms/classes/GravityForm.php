<?php

/**
 * A Formidable form
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class GravityForm extends Form
{
  
  public $form_data;
  
  /**
   * Get form name
   *
   * @return string Form name
   */
  public function name()
  {
    return $this->form_data->title;
  }
  
  /**
   * Get form ID
   *
   * @return string Form name
   */
  public function internal_id()
  {
    return $this->form_data->id;
  }
  
  /**
   * Get fields
   *
   * @return array Array of FormidableField's
   */
  public function fields()
  {
  
    $fields = array();
    
    $formidable_fields = \FrmField::get_all_for_form( $this->internal_id(), '', 'include' );
    foreach ( $formidable_fields as $formidable_field )
    {
      $fields[] = new FormidableFormField( $formidable_field );
    }
    
    return $fields;
    
  }
  
  /**
   * Constructor
   * 
   * @param $id string Formidable Form key
   */
  public function __construct( $id )
  {
    
    $this->form_data = \RGFormsModel::get_form( $id );
    $this->id        = $id;
    
  }
  
  /**
   * Get the list of forms from the database.
   *
   * @return array Array of forms
   */
  public static function get_forms()
  {
  
    $rows = \RGFormsModel::get_forms();
    
    $forms = array();
    foreach ( $rows as $row )
      $forms[] = new GravityForm( $row->id );
    
    return $forms;
    
  }

  /**
   * Get the list of e-mail actions that this form can send
   */
  public function emails()
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
    return admin_url( 'admin.php' ) . '?page=gf_edit_forms&id=' . $this->id();
  }
  
  /** 
   * Get link to view entries of this form
   *
   * @return string URL to view entries of this form
   */
  public function entries_url()
  {
    return admin_url( 'admin.php' ) . '?page=gf_entries&id=' . $this->id();
  }
  
  /**
   * Get the list of entries for this form
   */
  public function entries()
  {
    
    global $wpdb;
    
    $query = "
      SELECT 
        `id` 
      FROM 
        `".$wpdb->prefix."frm_items` 
      WHERE 
        `form_id` = " . Db::escape( $this->internal_id() ) . "
      ORDER BY 
        UNIX_TIMESTAMP(`created_at`) DESC
    ";
    
    $rows = Db::_select( $query );
    
    $entries = array();
    foreach ( $rows as $row )
    {
      $entry = new FormidableFormEntry( $row->id );
      $entry->form = $this;
      $entries[] = $entry;
    }
    
    return $entries;
    
  }
  
  /** 
   * Get link to add an entry to this form
   *
   * @return string URL to add an entry to this form
   */
  public function add_entry_url()
  {
    return get_site_url() . '/?gf_page=preview&id=' . $this->id();
  }

}
