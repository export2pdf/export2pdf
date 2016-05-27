<?php

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
  
$languages_folder = realpath( __DIR__ . '/../../languages' ) . '/';
define( 'EXPORT2PDF_LANGUAGES_PATH', $languages_folder );

$result = load_plugin_textdomain(   
  'export2pdf', 
  FALSE, 
  'export2pdf/languages' 
);
  
/**
 * Add missing translations to the database, and translate using database
 */
add_filter( 'gettext', function ( $translated_text = NULL, $untranslated_text = NULL, $domain = NULL) {
  
  // We manage only translations for our plugin
  if ( $domain != "export2pdf" )
    return $translated_text;
    
  return \Export2Pdf\Translation::translate( $untranslated_text, $translated_text );

}, 10, 3);

/*
  
  TODO: handle ngettext
  
  add_filter( 'ngettext', function ( $translated_text = NULL, $single = NULL, $plural = NULL, $number = 0, $domain = NULL) {
    
    // We manage only translations for our plugin
    if ( $domain != "export2pdf" )
      return $translated_text;
      
    \Export2Pdf\Translation::translate( $single );
    
    return $translated_text;

  }, 10, 3);
  
*/
