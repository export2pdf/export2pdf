<?php

/**
 * A Formidable form
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class FormidableForm extends Form
{
  
  public $form_data;
  
  /**
   * Get form name
   *
   * @return string Form name
   */
  public function name()
  {
    return $this->form_data->name;
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
    
    /*  
    global $wpdb;
    
    $query = "
      SELECT 
        * 
      FROM 
        `" . $wpdb->prefix . "frm_fields` 
      WHERE 
        `form_id` = " . Db::escape( $this->internal_id() )
    ;
    
    $rows = Db::_select( $query );
    
    foreach ( $rows as $row )
      $fields[] = new FormidableFormField( $row );
    */
    
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
  
    global $wpdb;
    
    $query = "
      SELECT 
        * 
      FROM 
        `" . $wpdb->prefix . "frm_forms` 
      WHERE 
        `form_key` = " . Db::escape( $id )
    ;
    
    $rows = Db::_select( $query );
    
    if ( count( $rows ) != 1 )
      throw new Exception( 
        'Somewhy there are ' . count( $rows ) .
        ' Formidable Form(s) in the database with form key ' .
        $id
      );
      
    $row = array_shift( $rows );
    
    $this->form_data = $row;
    $this->id        = $id;
    
  }
  
  /**
   * Get the list of forms from the database.
   *
   * @return array Array of forms
   */
  public static function get_forms()
  {
  
    global $wpdb;
    
    $query = "
      SELECT 
        `form_key` 
      FROM 
        `".$wpdb->prefix."frm_forms` 
      WHERE 
        `status` = 'published' 
        AND ( `parent_form_id` = 0 OR `parent_form_id` IS NULL )
        AND ( `is_template` = 0 OR `is_template` IS NULL )
      ORDER BY 
        `name` ASC
    ";
    
    $rows = Db::_select( $query );
    
    $forms = array();
    foreach ( $rows as $row )
      $forms[] = new FormidableForm( $row->form_key );
    
    return $forms;
    
  }

  /**
   * Get the list of e-mail actions that this form can send
   */
  public function emails()
  {
  
    $emails = array();
    
    $form_actions = \FrmFormAction::get_action_for_form( $this->internal_id() );
    foreach ( $form_actions as $action )
      if ( $action->post_excerpt == 'email' )
      {
        $email = new FormidableFormAction();
        $email->id = $action->ID;
        $email->name = $action->post_title;
        $emails[] = $email;
      }
        
    return $emails;
    
  }
  
  /** 
   * Get link to view form
   *
   * @return string URL to view form
   */
  public function url()
  {
    return admin_url( 'admin.php' ) . '?page=formidable&frm_action=edit&id=' . $this->internal_id();
  }
  
  /** 
   * Get link to view entries of this form
   *
   * @return string URL to view entries of this form
   */
  public function entries_url()
  {
    return admin_url( 'admin.php' ) . '?page=formidable-entries&frm_action=list&form=' . $this->internal_id();
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
    return admin_url( 'admin-ajax.php' ) . '?action=frm_forms_preview&form=' . $this->form_data->form_key;
  }

}
