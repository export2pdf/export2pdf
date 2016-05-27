<?php

/**
 * Export2Pdf addon for Formidable Forms
 * TODO: add gravity forms support
 */

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class GravityForms extends Addon
{

  const TITLE = 'Gravity Forms';
  const URL   = 'http://www.gravityforms.com/';
  
  /**
   * Checks if the addon is available
   * E.g.: if the corresponding plugin is activated or not
   */
  public function available()
  {
    return class_exists( "\\RGFormsModel" );
  }

  /**
   * Get list of forms
   *
   * @return array Array of FormidableForm's
   */
  public function forms()
  {
    return GravityForm::all();
  }

}

// export2pdf_initialize_framework( __DIR__ );
// new GravityForms();
