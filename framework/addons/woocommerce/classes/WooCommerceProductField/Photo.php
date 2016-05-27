<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Product title
 */

class WooCommerceProductField_Photo extends WooCommerceProductField
{

  public $name               = 'Photo';
  public $default_formatting = 'Image';
  
  public function value( $entry )
  {
    
    $thumbnail_id = get_post_thumbnail_id( $entry->id() );
    
    // Product doesn't have a photo
    if ( ! $thumbnail_id )
      return wc_placeholder_img_src();
      
    $thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'shop_thumbnail' );
    
    return $thumbnail[ 0 ];
    
  }

}
