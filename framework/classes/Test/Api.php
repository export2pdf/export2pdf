<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Check if Export2PDF API works fine on this website
 */

class Test_Api extends Test
{

  const NAME = "Export2PDF API";

  public function execute()
  {
  
    $result = ApiRequest_Test::perform();
    
    if ( Tools::trim( $result ) != 'works' )
      throw new Exception( "Export2PDF API is not available" );
    
  }

}
