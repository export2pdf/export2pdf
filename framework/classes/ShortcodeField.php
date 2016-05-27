<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Shortcode that inserts a form field value into an HTML template
 */
 
class ShortcodeField extends Shortcode
{
  
  const NAME = 'export2pdf_field';
  
  public static $last_map; // Stores last generated field map
  
  /**
   * Generate shortcode
   *
   * @param $form_field FormField Form field that will be exported
   * @param $options array (optional) Shortcode options
   *
   * @return string Generated shortcode
   */
  public static function generate( $form_field, $options = array() )
  {

    // Format and validate options
    $options[ 'form_field' ]      = $form_field->id();
    
    return self::_generate( $options );
  
  }
  
  /**
   * Parse shortcode arguments, and output shortcode value
   * 
   * @param $attributes array Shortcode options
   *
   * @return string Shortcode output
   */
  public static function process( $attributes = array() )
  {
    
    extract( $attributes );
    
    try
    {
    
      self::$last_map = NULL;
    
      if ( ! isset( $_GET[ 'template' ] ) )
        throw new Exception( "Template is not set." );
    
      $template = Template::get( $_GET[ 'template' ] );
      
      $form = $template->form();
      
      if ( ! isset( $form_field ) )
        throw new Exception( "Shortcode attribute entry is not set." );
    
      $form_field = $form->field( $form_field );

      // Get template map information
      $map = new TemplateMap();
      $map->source    = $form_field;
      $map->source_id = $form_field->id();
      $map->template  = $template;
      
      // Set formatting options
      if ( isset( $formatting ) )
      {
      
        $map->formatting = $formatting;
        
        foreach ( $attributes as $option_name => $option_value )
          $map->set_option( $option_name, $option_value );

      }
      
      self::$last_map = $map;
      
      // Get entry information
      if ( ! isset( $_GET[ 'entry' ] ) )
        throw new Exception( "Entry is not set." );
    
      $entry = $form->entry( $_GET[ 'entry' ] );
      $entry_value = $entry->value( $form_field );
      $map->value    = $entry_value;
      
      // Format value
      $value = Format::map( $map );
      
      return $value . '';
    
    }
    catch ( Exception $e )
    {
      // Something went wrong
      // return $e->getMessage();
    }
    
    return '';
  
  }

}
