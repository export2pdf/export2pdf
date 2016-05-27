<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Manages translations
 */
 
class Translation extends Model
{
  
  const TABLE = "translations";
  
  public $language;
  public $original;
  public $translated;
  
  /**
   * Get corrent language
   *
   * @return string Current language of this WordPress installation
   */
  public static function current_language()
  {
    return get_locale();
  }
  
  /**
   * Checks if we manage translations
   */
  public static function enabled()
  {
    return Debug::enabled();
  }
  
  /**
   * Store a translation
   *
   * @param $string Text that can be translated
   * @param $translated_string (optional) Translated text
   */
  public static function add( $string, $translated_string = "" )
  {
  
    // Manage translations only in debug mode
    if ( ! self::enabled() )
      return;
  
    // First, check if it already exists in the database
    $existing_translations = self::all(array(
      'language' => self::current_language(),
      'original' => $string,
    ));
    
    // If it exists already, then don't store it again
    if ( count( $existing_translations ) )
    {
    
      if ( ! $translated_string )
        return;
        
      // Update existing translation
          
      $translation = $existing_translations[ 0 ];
      $translation->translated = $translated_string;
      $translation->save();
    
    }
    else
    {
  
      // Store this translation
    
      $translation             = new Translation();
      
      $translation->language   = self::current_language();
      $translation->original   = $string;
      $translation->translated = $translated_string;
      
      $translation->save();
    
    }
  
  }
  
  /**
   * Transalate a string
   * 
   * @param $string string Text to be translated
   * @param $translated_string (optional) Translated text
   *
   * @return string Translated text
   */
  public static function translate( $string, $translated_string = "" )
  {
  
    // Manage translations only in debug mode
    if ( ! self::enabled() )
      return $translated_string;
  
    // First, check if it already exists in the database
    $existing_translations = self::all(array(
      'language' => self::current_language(),
      'original' => $string,
    ));
    
    // If no translations are found, then return the original
    if ( ! count( $existing_translations ) )
    {
      self::add( $string );
      return $translated_string;
    }
      
    // Return translation from the database
    $translation = $existing_translations[ 0 ];
    if ( $translation->translated )
      return $translation->translated;
      
    // By default, if not translations are found, return the original
    return $translated_string;
  
  }

}
