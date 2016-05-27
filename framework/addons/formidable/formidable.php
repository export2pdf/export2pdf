<?php

/**
 * Export2Pdf addon for Formidable Forms
 */

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class Formidable extends Addon
{

  const TITLE = 'Formidable Forms';
  const URL   = 'https://wordpress.org/plugins/formidable/';
  
  /**
   * Checks if the addon is available
   * E.g.: if the corresponding plugin is activated or not
   */
  public function available()
  {
    return class_exists( '\FrmAppHelper' );
  }
  
  /**
   * Get list of forms
   *
   * @return array Array of FormidableForm's
   */
  public function forms()
  {
    return FormidableForm::all();
  }

}

export2pdf_initialize_framework( __DIR__ );
new Formidable();

