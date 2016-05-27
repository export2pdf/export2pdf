<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Shortcode to export PDF files
 */
 
class ShortcodeExport extends Shortcode
{
  
  const NAME = 'export2pdf';
  
  /**
   * Generate shortcode
   *
   * @param $template Template Template that will be used
   * @param $entry FormEntry Form entry that will be exported
   * @param $options array (optional) Shortcode options
   *
   * @return string Generated shortcode
   */
  public static function generate( $template, $entry, $options = array() )
  {

    // Format and validate options
    $options[ 'entry' ]    = $entry->id();
    $options[ 'template' ] = $template->id();
    
    return self::_generate( $options );
  
  }
  
  /**
   * Generate link to download the file
   *
   * @param $options array Shortcode options
   *
   * @return string Link to download PDF file
   */
  public static function generate_link( $template, $entry, $options=array() )
  {
  
    // Format and validate options
    $options[ 'entry' ]    = $entry->id();
    $options[ 'template' ] = $template->id();
    $attributes = self::parse_arguments( $options );
    
    $attributes[ 'action' ] = 'export2pdf-download';
    
    $link = admin_url( 'admin-ajax.php' ) . '?' . http_build_query( $attributes );
    
    return $link;
  
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
    
      if ( ! isset( $template ) )
        throw new Exception( "Shortcode attribute template is not set." );
    
      $template = Template::get( $template );
      
      if ( ! isset( $entry ) )
        throw new Exception( "Shortcode attribute entry is not set." );
    
      $entry = $template->entry( $entry );
      
      return static::generate_link( $template, $entry, $attributes );
    
    }
    catch ( Exception $e )
    {
      // Something went wrong
      // TODO: remove error message
      return $e->getMessage();
    }
    
    return '';
  
  }

}
