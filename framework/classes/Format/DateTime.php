<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Format as number or time
 */
 
class Format_DateTime extends Format_Date
{
  
  public $name = 'Date and time';
  
  public function examples()
  {
  
    // Get all date examples
    $date_format   = new Format_Date();
    $date_examples = $date_format->examples();
    
    // Get all time examples
    $time_format   = new Format_Time();
    $time_examples = $time_format->examples();
    
    // Combine date and time examples
    $examples = array();
    foreach ( $date_examples as $date_example )
      foreach ( $time_examples as $time_example )
        $examples[] = $date_example . ' ' . $time_example;
  
    return $examples;
  }
  
}


