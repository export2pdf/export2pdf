<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Product description
 */

class WooCommerceProductField_Description extends WooCommerceProductField
{

  public $name  = 'Description';
  
  public function value( $entry )
  {
    return apply_filters( 'the_content', $entry->post->post_content );
  }

}
