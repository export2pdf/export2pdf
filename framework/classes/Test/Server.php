<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Get server information
 */
 
class Test_Server extends Test
{

  const NAME = "Server";

  public function execute()
  {
  
    $this->result = $_SERVER[ 'SERVER_SOFTWARE' ];
    
    if ( Shell::program_exists( "uname" ) )
      $this->result .= "\n" . Shell::exec( "uname -a" );
    
  }

}
