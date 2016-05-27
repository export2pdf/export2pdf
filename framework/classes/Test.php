<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Perform some basic tests
 *
 * Useful for clients to debug what's wrong with their installation
 */

class Test
{

  const NAME = "Unknown test";
  
  public $warnings = array();
  public $errors   = array();
  public $result   = 'OK';

  public $priority = 0;         // Priority of this test (to move up in test list)
  
  public static $all = array(); // Array of all available tests

  
  /**
   * Get test name
   * 
   * @return string Test name
   */
  public function name()
  {
    return static::NAME;
  }
  
  /**
   * Gets the list of all available tests
   */
  public static function all()
  {
    if ( static::$all )
      return static::$all;
        
    $tests = array();
    
    // Get the list of files in "Test" folder and create corresponding classes
    $folders_with_tests = array( __DIR__ . '/Test/' );
    $folders_with_tests = apply_filters( "export2pdf_test_folders", $folders_with_tests );
    
    foreach( $folders_with_tests as $folder_with_tests )
    {
    
      $class_files = Tools::files_in_folder( $folder_with_tests );
      foreach ( $class_files as $class_file )
      {
      
        if ( ! preg_match( '/\.php$/', $class_file ) )
          continue;
      
        $klass = basename( $class_file );
        $klass = str_replace( ".php", "", $klass );
        $klass = "\\Export2Pdf\\Test_" . $klass;
        $tests[] = new $klass();
        
      }
    
    }
    
    // Sort them by their group
    usort( $tests, function ( $a, $b ) {
      
      if ( $a->priority != $b->priority )
      {
      
        // Order by priority first
        
        $group_order = self::$groups;
        
        $pos_a = $b->priority;
        $pos_b = $a->priority;
        $result = $pos_a - $pos_b;
        
      }
      else
      {
      
        // If priority is the same,
        // then sort in alphabetical order
      
        $result = strcmp( $a->name(), $b->name() );
        
      }
        
      return $result;
    
    });
    
    static::$all = $tests;
    
    return $tests;
  }
  
  /**
   * Executes the test
   */
  public function execute()
  {
    throw new Exception( "This method should be implemented in a child class." );
  }
  
  /**
   * Add an error message
   */
  public function error( $message )
  {
    $this->errors[] = $message;
  }
  
  /**
   * Add an warning message
   */
  public function warning( $message )
  {
    $this->warnings[] = $message;
  }
  
  /**
   * Print test result
   */
  public function perform()
  {
  
    // Execute the test first
    try
    {
      $this->execute();
    }
    catch ( Exception $e )
    {
      $this->error( $e->getMessage() );
    }
    
  }

}
