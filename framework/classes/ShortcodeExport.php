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
  
  public static $defaults = array(
    'as_link' => 0,
    'title'   => 'Download',
  );
  
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
    
    $options[ 'action' ] = 'export2pdf-download';
    
    $link = admin_url( 'admin-ajax.php' ) . '?' . http_build_query( $options );
    
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
    
    $options = self::parse_arguments( $attributes );
    extract( $options );
    
    try
    {
    
      if ( ! isset( $template ) )
        throw new Exception( "Shortcode attribute template is not set." );
    
      $template = Template::get( $template );
      
      if ( ! isset( $entry ) )
        throw new Exception( "Shortcode attribute entry is not set." );
    
      $entry = $template->entry( $entry );
      
      $link = static::generate_link( $template, $entry, $attributes );
      
      // Generate as link unless 
      if ( ! $is_link )
      {
      
        $title = __( $title, 'export2pdf' );
      
        $link = sprintf(
          '<a href="%s" target="_blank" title="%s" target="download_window">%s</a>',
          $link,
          esc_attr( $title ),
          $title
        );
        
      }
      
      return $link;
    
    }
    catch ( Exception $e )
    {
    
      // Something went wrong
      
      // For debugging:
      // return $e->getMessage();
      
    }
    
    return '';
  
  }

}
