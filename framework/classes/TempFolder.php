<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Temporary folder management
 */
 
 
class TempFolder extends TempFile
{

  public function __construct( $extension = 'folder' )
  {
  
    parent::__construct( $extension ); 
    
    @unlink( $this->path() );
    @mkdir( $this->path() );
    
    if ( ! file_exists( $this->path() ) )
      throw new Exception( "It wasn't possible to create folder " . $this->path() );
      
    $this->path .= '/';
    
  }
  
  /**
   * Delete folder
   */
  public function delete()
  {
    Tools::rm( $this->path() );
  }
  
}
