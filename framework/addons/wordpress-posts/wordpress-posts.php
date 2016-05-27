<?php

/**
 * Export2Pdf addon for WordPress
 */

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

// TODO: Add wordpress post export support
return;

class WordPressPosts extends Addon
{

  const TITLE = 'WordPress';
  const URL   = 'https://wordpress.org/';
  
  public $forms;
  
  /**
   * Get list of forms
   *
   * @return array Array of FormidableForm's
   */
  public function forms()
  {
  
    if ( $this->forms )
      return $this->forms;
  
    $forms = array();
    
    $forms[] = new WordPressPostType( 'post' );
    
    $this->forms = $forms;
    return $forms;
    
  }

}

export2pdf_initialize_framework( __DIR__ );
new WordPress();

