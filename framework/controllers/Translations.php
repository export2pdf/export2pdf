<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Translation management
 */

class TranslationsController extends Controller
{

  const PAGE = "export2pdf-translations";
  
  public function index()
  {
  
    if ( Tools::is_post() )
    {
    
      // Delete a translation
      if ( isset( $_POST[ 'remove_translation' ] ) )
      {
      
        $translation_id = $_POST[ 'remove_translation' ];
        $translation    = Translation::get( $translation_id );
        
        $translation->destroy();
        wp_die();
        
      }
    
      // Save translations
      $translations = (array) stripslashes_deep( $_POST[ 'translations' ] );
      foreach ( $translations as $id => $translated_text )
      {
      
        $translation             = Translation::get( $id );
        $translation->translated = $translated_text;
        $translation->is_new     = 0;
        $translation->save();
      
      }
    
    }
    
    // Get all translations for current language
    $translations = Translation::all(array(
      'language' => Translation::current_language(),
    ));
    $this->variables[ 'translations' ] = $translations;
  
  }
  
}
