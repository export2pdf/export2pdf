<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Format as time
 */
 
class Format_Time extends Format_Date
{
  
  public $name = 'Time';
  
  public function examples()
  {
  
    return array(
      'G:i',
      'g:i a',
      'g:i A',
      'g:i:s a',
      'g:i:s A',
      'H:i',
    );
  
  }
  
}


