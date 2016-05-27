<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * PDF Template
 * Contains template properties and field maps
 */

class Template extends Model
{
  const TABLE = "templates";
  
  public $name;
  public $addon;
  public $form;
  public $actions;
  public $options;
  public $pdf_file_id;
  public $form_primary_field;
  public $type = 'Template';
  public $flatten;
  public $password;
  public $optimize;
  
  public $maps;
  
  public $default_options = array(); // Default options of the template
  
  /**
   * Get default options
   * Using this method just to generate them dynamically if needed
   *
   * @return array Default options
   */
  public function default_options()
  {
    return $this->default_options;
  }
  
  /**
   * Get type of this template (PDF or HTML)
   *
   * @return string Either "html" or "pdf"
   */
  public function type()
  {
    if ( $this instanceof TemplateHtml )
      return 'html';
    return 'pdf';
  }
  
  /**
   * Creates a PDF file for this template
   */
  public function add_pdf_file( $pdf_path )
  {
  
    $this->file()->path = $pdf_path;
  
    // Get information about existing field maps
    $old_field_maps = array();
    foreach ( $this->maps() as $map )
      $old_field_maps[ $map->field()->name ] = $map->field()->id;
  
    // Create the file
    $pdf = new PdfFile();
    $pdf->path = $pdf_path;
    $pdf->save_to_database();
    
    // Update file ID for this template
    $this->pdf_file_id = $pdf->id();
    $this->save();
    
    // Re-create field maps from old data,
    // and remove unused field maps
    foreach ( $old_field_maps as $old_field_name => $old_field_id )
    {
    
      $map_recreated = FALSE;
    
      foreach ( $pdf->fields() as $field )
      {
        if ( $field->name == $old_field_name )
        {
        
          $map_recreated = TRUE;
          $new_field_id  = $field->id;
        
          // Update old field ID to new field ID
          Db::update(
            TemplateMap::TABLE,
            array(
              'pdf_field_id' => $new_field_id,
            ),
            array(
              'pdf_field_id' => $old_field_id,
            )
          );
        
        }
      }
    
      // If map wasn't recreated, then delete it
      if ( ! $map_recreated )
      {
      
        Db::delete(
          TemplateMap::TABLE,
          array(
            'pdf_field_id' => $old_field_id,
          )
        );
      
      }
      
    }
    
  }
  
  /**
   * Get PDF file object
   */
  public function file()
  {
    return PdfFile::get( intval( $this->pdf_file_id ) );
  }
  
  /**
   * Destroy
   */
  public function destroy()
  {
    // Delete the file
    try
    {
      $this->file()->destroy();
    }
    catch ( Exception $e )
    {
    }
    parent::destroy();
  }
  
  /**
   * Save
   */
  public function save()
  {
    
    // Call parent methid
    // Saves to the DB
    parent::save();
  
    // Check if name wasn't set, then set it as form name
    if ( ! $this->name() )
    {
      try
      {
        $this->name = $this->form()->name();
        parent::save();
      }
      catch ( Exception $e )
      {
        // Well, probably the form isn't available anymore
      }
    }
    
  }
  
  /**
   * Get template name
   *
   * @return string Template name
   */
  public function name()
  {
    return $this->name;
  }
 
  /**
   * Check if this template has a PDF file that was uploaded
   *
   * @return bool TRUE if file exists, FALSE if it doesn't
   */
  public function available() 
  {
    try
    {
      $this->addon();
      $this->form();
      if ( $this->file()->id() ) 
        return true;
    }
    catch ( Exception $e )
    {
      // Probably it doesn't exist
    }
    return false;
  }
 
  /**
   * Get addon object
   *
   * @return Addon Addon assigned for this template.
   */
  public function addon()
  {
  
    if ( ! $this->addon )
      throw new Exception( 'Template #' . $this->id() . ' (' . $this->name . ') does not have an addon.' );
      
    $addons = Addon::all();
    foreach ( $addons as $addon )
      if ( $addon->id() == $this->addon )
        return $addon;
        
    throw new Exception( 'Addon ' . $this->addon . ' is not available for template #' . $this->id() . ' (' . $this->name . ') anymore.' );
  }
  
  /**
   * Get form object
   *
   * @return Form Form assigned for this template.
   */
  public function form()
  {
  
    if ( ! $this->form )
      throw new Exception( 'Template #' . $this->id() . ' (' . $this->name . ') does not have a form.' );
      
    $forms = $this->addon()->forms();
    foreach ( $forms as $form )
      if ( $form->id() == $this->form )
        return $form;
        
    throw new Exception( 'Form ' . $this->form . ' is not available for template #' . $this->id() . ' (' . $this->name . ') anymore.' );
  }
  
  /**
   * Get mapping for a field
   *
   * @param $field FormField A field of a form
   *
   * @return TemplateMap An object that contains information about where $field is mapped to
   */
  public function map( $field )
  {
    
    /*
    $row = Db::selectOne( 
      TemplateMap::TABLE, 
      array( 
        'pdf_field_id' => $field->id(),
        'template_id'  => $this->id(),
      )
    );
    
    if ( $row )
    {
      $map = new TemplateMap( $row->id );
    }
    else
    {
      $map = new TemplateMap();
      $map->pdf_field_id = $field->id();
      $map->template_id  = $this->id();
    }
    */
    
    foreach ( $this->maps() as $map )
      if ( $map->field()->id() == $field->id() )
        return $map;
    
    $map = new TemplateMap();
    $map->pdf_field_id = $field->id();
    $map->template_id  = $this->id();
    $map->template     = $this;
    
    // $this->maps[] = $map;
    
    return $map;
    
  }
  
  /**
   * Check if template has action enabled (e.g. email should be sent)
   *
   * @return bool TRUE if action exists, FALSE otherwise
   */
  public function has_action( $action_id )
  {
    
    // Loop through all available actions of this template
    foreach ( $this->actions() as $action )
      if ( $action->data == $action_id )
        return true;
        
    // Action not found
    return false; 
    
  }
  
  /**
   * Get all field maps
   *
   * @return array Array of TemplateMap
   */
  public function maps()
  {
  
    if ( $this->maps )
      return $this->maps;
  
    $rows = TemplateMap::all( array( 
      'template_id'  => $this->id(),
    ));
    
    $maps = array();
    foreach ( $rows as $map )
    {
    
      try
      {
      
        $map->template = $this;
        $map->field();
        $map->source();
        
        $maps[] = $map;
        
      }
      catch ( Exception $e )
      {
      
        // Probably field doesn't exist anymore
        // if ( $map ) $map->destroy();
        
      }
      
    }
    
    return $maps;
    
  }
  
  /**
   * Get list of template actions
   */
  public function actions()
  {
    if ( $this->actions )
      return $this->actions;
    
    $this->actions = TemplateAction::all( array ( 
      'template_id' => $this->id(),
    ));
    
    return $this->actions;
  }
  
  /**
   * Get a form entry for this template
   *
   * @param $id int Entry ID
   *
   * @return FormEntry Corresponding form entry
   */
  public function entry( $id )
  {
    return $this->form()->entry( $id );
  }
  
  /**
   * Get URL to preview image
   *
   * @return string URL to image of the first page of PDF file
   */
  public function preview_url()
  {
    try
    {
      $pages = $this->file()->pages();
      if ( count( $pages ) )
      {
        $page = $pages[ 0 ];
        return $page->preview_url();
      }
    }
    catch ( Exception $e )
    {
    }
    return false;
  }
  
  /**
   * Get document format
   *
   * @return string 'portrait' or 'landscape'
   */
  public function format()
  {
    try
    {
      $pages = $this->file()->pages();
      if ( count( $pages ) )
      {
        $page = $pages[ 0 ];
        return $page->format();
      }
    }
    catch ( Exception $e )
    {
    }
    return 'portrait';
  }
  
  
  /**
   * Get edit link
   *
   * @return string URL to edit page
   */
  public function edit_link()
  {
    $step = 'edit_step1';
    if ( $this->available() )
      $step = 'edit_step3';
    return
      TemplatesController::action_url( 
        $step, 
        array(
          'template' => $this->id(),
        )
      )
    ;
  }
  
  /**
   * Get edit link
   *
   * @return string URL to edit page
   */
  public function url()
  {
    return $this->edit_link();
  }
  
  /**
   * Set template option
   *
   * @param $key string Option name
   * @param $value string Option value
   */
  public function set_option( $key, $value )
  {
    
    // Let's see if this option exists already
    $option = Db::selectOne(
      TemplateOption::TABLE,
      array(
        'template_id' => $this->id(),
        'key'         => $key,
      )
    ); 
      
    if ( ! $option )
    {
    
      // If it doesn't, then create it 
      $option              = new TemplateOption();
      $option->template_id = $this->id();
      $option->key         = $key;
      
    }
    else
    {
    
      // Option exists. Get it from the database
      $option = new TemplateOption( $option->id );
    
    }
    
    // Update option
    $option->value = $value;
    $option->save();
    
    // Re-initialize options
    $this->options = NULL;
    
  }
  
  /**
   * Get template option
   *
   * @param $key string Option name
   *
   * @return string Option value
   */
  public function option( $key )
  {
    
    // Let's see if this option exists already
    foreach ( $this->options() as $option )
      if ( $option->key == $key )
        return $option->value;
        
    // Well, it doesn't exist!
    return NULL;
  
  }
  
  /** 
   * Get all template options as array
   */
  public function options_array()
  {

    $options = array();
    foreach ( $this->options() as $option )
      $options[ $option->key ] = $option->value;
      
    return $options;
    
  }
  
  /**
   * Get post processing options for a PDF 
   * e.g. set password, optimisations, ...
   */
  public function post_process_options()
  {
  
    $options = new \stdClass();
    
    $options->flatten  = $this->flatten;
    $options->name     = $this->name();
    $options->optimize = $this->optimize;
    $options->password = $this->password;
    
    return $options;
    
  }
  
  /**
   * Get all template options
   */
  public function options()
  {
    
    if ( $this->options )
      return $this->options;
    
    // Get the list of options from the database if we don't have it yet
    $this->options = TemplateOption::all(
      array( 
        'template_id' => $this->id()
      )
    );
    
    foreach ( $this->default_options() as $default_option_key => $default_option_value )
    {
      
      // Check if default option exists in the database
      $exists = false;
      
      foreach ( $this->options as $option )
        if ( $option->key == $default_option_key )
          $exists = true;
          
      if ( $exists )
        continue;
      
      // Default option hasn't been set
      // Append it to the list
      $option              = new TemplateOption();
      $option->template_id = $this->id();
      $option->key         = $default_option_key;
      $option->value       = $default_option_value;
      
      $this->options[] = $option;
      
    }
    
    return $this->options;
  
  }

}
