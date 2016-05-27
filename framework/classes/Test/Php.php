<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Check what is PHP version
 */

class Test_Php extends Test
{

  const NAME = "PHP";

  public function execute()
  {
  
    $this->result = PHP_VERSION;
    
  }

}
