<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Manages Google Fonts
 */

class Font
{

  // Font properties, just like in Google Fonts API
  
  //public $kind = "";
  public $family = "";
  //public $category = "";
  public $variants = array();
  //public $version = "";
  //public $lastModified = "";

  public static $api_key;

  /**
   * Get font name
   */
  public function name()
  {
    return $this->family;
  }
  
  /**
   * Get CSS @import statement
   */
  public function import_statement()
  {
  
    $family = urlencode( $this->family );
    $variants = implode( ',', $this->variants );
    
    return "\n/* FONT: '" . $this->family . "' */\n@import url('//fonts.googleapis.com/css?family=$family:$variants');\n/* END FONT */\n\n";
  }
  
  /**
   * Get font from ID
   */
  public static function get( $family = '' )
  {
    
    foreach ( static::all() as $font )
      if ( $font->family == $family )
        return $font;
    
    $class_name = get_called_class();
    return new $class_name();
    
  }

  /**
   * Get the list of all fonts
   *
   * @return array<Font> List of all fonts
   */
  public static function all()
  {
  
    // Try to get all fonts from serialized data
    $font_cache_file = static::cache_file();
    if ( file_exists( $font_cache_file ) and filesize( $font_cache_file ) )
    {
      $font_cache_file_data = @file_get_contents( $font_cache_file );
      $fonts_data = @unserialize( $font_cache_file_data );
      if ( $fonts_data and is_array( $fonts_data ) and count( $fonts_data ) )
        return $fonts_data;
    }
    
    return array();
    
  }
  
  /**
   * Path to a JSON file that contains font list
   */
  public static function cache_file()
  {
    return Framework::path() . '/tools/fonts/fonts.txt';
  }
  
  /**
   * Preview image path
   */
  public function preview_path()
  {
  
    $preview_folder = Framework::path() . '/tools/fonts/preview/';
    Tools::mkdir( $preview_folder );
    
    $preview_path   = $preview_folder . $this->family . '.png';
    
    return $preview_path;
    
  }
  
  /**
   * Preview image url
   */
  public function preview_url()
  {
    
    $preview_path   = $this->preview_path();
    $preview_url    = str_replace( ABSPATH, '/', $preview_path );
    
    return $preview_url;
    
  }
   
  /**
   * Gets the first available variant for this font
   */ 
  public function default_variant()
  {
  
    $variants = $this->variants;
    
    // If regular font exists, then we'll use it
    if ( in_array( 'regular', $variants ) )
      return 'regular';
    
    // If not, then return the first available variant
    return $variants[ 0 ];
    
  }
  
  /**
   * Update fonts list
   */
  public static function update()
  {
  
    // Get the list of fonts from API
  
    if ( ! static::$api_key )
      throw new Exception( 'API key must be set in order to get the list of fonts.');
      
    $fonts_url      = 'https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity&key=' . self::$api_key;
    $fonts_response = wp_remote_get( $fonts_url );
    
    if ( is_wp_error( $fonts_response ) or ! $fonts_response['body'] )
      throw new Exception( "Could not get the list of fonts from Google" );
    
    $fonts_data = @json_decode( $fonts_response['body'] );
    
    // Construct our list of fonts
    $fonts = array();
    foreach ( $fonts_data->items as $font_object )
    {
    
      $font = static::get();
      
      foreach ( $font_object as $font_property => $font_value )
      {
        // We don't need all returned font values here
        // Check if $font_property exists for a font
        if ( isset( $font->{ $font_property } ) )
          $font->{ $font_property } = $font_value;
      }
      
      $fonts[] = $font;
      
    }
    
    // Store font file
    $font_data = serialize( $fonts );
    Tools::rm( static::cache_file() ); 
    file_put_contents( static::cache_file(), $font_data );
    
  }

}


