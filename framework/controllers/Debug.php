<?php

/**
 * User interface to check website configuration
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class DebugController extends Controller
{

  const PAGE = "export2pdf-debug";
  
  /**
   * Get all tests and perform them
   */
  public function index()
  {
  
    $tests = Test::all();
    foreach ( $tests as $test )
      $test->perform();

    $this->variables[ 'tests' ] = $tests;
  
  }
  
}
