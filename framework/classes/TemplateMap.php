<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * PDF Template Field Mapping
 */

class TemplateMap extends Model
{
  const TABLE = "template_maps";
  
  public $template_id;
  public $pdf_field_id;
  public $source_id;
  public $formatting;
  
  public $options;
  public $field;
  public $template;
  public $source;
  
  public $value; 
 
  /**
   * Get PDF Field object
   */
  public function field()
  {
  
    if ( $this->field )
      return $this->field;
      
    return $this->template()->file()->field( $this->pdf_field_id );
    
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
    $option = TemplateMapOption::all(
      array(
        'template_map_id' => $this->id(),
        'key'             => $key,
      )
    ); 
      
    if ( ! $option )
    {
    
      // If it doesn't, then create it 
      $option                  = new TemplateMapOption();
      $option->template_map_id = $this->id();
      $option->key             = $key;
      
    }
    else
    {
    
      // Option exists. Get it from the database
      $option = $option[ 0 ];
    
    }
    
    // Update option
    $option->value = $value;
    $option->save();
    
    // Re-initialize options
    $this->options = NULL;
    
  }
  
  /**
   * Get formatting option
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
        
    // Look for default options
    foreach ( $this->format()->default_options() as $option => $option_value )
      if ( $option_name == $key )
        return $option_value;
        
    // Well, it doesn't exist!
    return NULL;
  
  }

  /**
   * Get all formatting options
   */
  public function options()
  {
    
    if ( $this->options )
      return $this->options;
    
    // Get the list of options from the database if we don't have it yet
    $this->options = (array)TemplateMapOption::all(
      array( 
        'template_map_id' => $this->id()
      )
    );
    
    foreach ( $this->format()->default_options() as $default_option_key => $default_option_value )
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
      $option                  = new TemplateMapOption();
      $option->template_map_id = $this->id();
      $option->key             = $default_option_key;
      $option->value           = $default_option_value;
      
      $this->options[] = $option;
      
    }
    
    return $this->options;
  
  }

  /** 
   * Get formatting options as array
   */
  public function options_array()
  {

    $options = array();
    foreach ( $this->options() as $option )
      $options[ $option->key ] = $option->value;
      
    return $options;
    
  }
  
  /**
   * Get Format object
   */
  public function format()
  {
  
    $format = new Format();
    
    try
    {
      $klass = $this->formatting;
      $klass = "Export2Pdf\\Format_" . ucfirst( $klass );
      if ( class_exists( $klass ) )
        $format = new $klass();
    }
    catch ( Exception $e )
    {
    }
    
    $format->template = $this->template();
    
    return $format;
    
  }
  
  /**
   * Get PDF Template object
   */
  public function template()
  {
  
    if ( $this->template )
      return $this->template;
  
    $this->template = Template::get( $this->template_id );
    return $this->template;
  }
  
  /**
   * Get PDF Template object
   */
  public function source()
  {
  
    if ( $this->source )
      return $this->source;
  
    return $this->template()->form()->field( $this->source_id );
    
  }
  
  /**
   * Get field value
   *
   @return string Value of the field associated with this field map
   */
  public function value()
  {
    return $this->value;
  }
  
  /**
   * Save
   */
  public function save()
  {
    // Check if form field is set
    if ( ! $this->source_id )
      throw new Exception( "Field map should have a corresponding form field.");
      
    // Check if PDF field is set
    if ( ! $this->pdf_field_id )
      throw new Exception( "Field map should have a corresponding PDF field.");
    
    // Reset cached template maps
    $this->template->maps = NULL;
    
    // Call parent method
    // Saves to the DB
    parent::save();
    
  }
  
  /**
   * Get entry data
   *
   * @param $template Template Template that will be used
   * @param $entry FormEntry Entry that will be exported
   *
   * @return array Array of Field maps.
   */
  public static function export( $template, $entry )
  {
  
    $maps = $template->maps();
    
    foreach ( $maps as $map )
    {
    
      $pdf_field       = $map->field();
      $form_field      = $map->source();
      $form_value      = $entry->value( $form_field );
      
      $form_value->map = $map;
      $map->value      = $form_value;
      
    }
    
    return $maps;
  
  }
  
}
