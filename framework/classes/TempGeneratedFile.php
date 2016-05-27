<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Temporary exported PDF files
 */
 
class TempGeneratedFile extends TempFile
{

  const FOLDER    = 'generated';
  
  /**
   * Create a file
   *
   * @param $extension string File extension
   */
  public function __construct( $extension = 'pdf' )
  {
    $this->path = tempnam( $this->folder(), '' ); 
  }
  
  /**
   * Clean up file name
   */
  public static function clean_filename( $temporary_file_name )
  {
    $temporary_file_name = preg_replace( '/[^0-9a-zA-Z\.]+/', '', $temporary_file_name ); // Allow only numbers, letters and dots
    return $temporary_file_name;
  }
  
}
