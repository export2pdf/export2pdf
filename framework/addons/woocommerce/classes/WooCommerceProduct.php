<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * A WooCommerce Product
 */
 
class WooCommerceProduct extends FormEntry
{
  
  public $meta_data;
  public $post;
  public $product;
  
  /**
   * Get entry name
   * First, try parent method
   * Then, try Formidable entry name
   *
   * @return string Entry title
   */
  public function name()
  {
    return $this->post->post_title;
  }
  
  /**
   * Get WooCommerce order
   *
   * @return WC_Order WooCommerce order
   */
  public function product()
  {
    return $this->product;
  }
  
  /**
   * Constructor
   * 
   * @param $id string Formidable Form Entry ID
   */
  public function __construct( $id )
  {
  
    $this->post = get_post( $id );
    
    if ( ! $this->post ) 
      throw new Exception( "Couldn't find WooCommerce product #$id" );
    
    $product_factory = new \WC_Product_Factory();  
    $this->product   = $product_factory->get_product( $id );
    
    $this->meta_data  = get_post_meta( $id );
    $this->id         = $id;
    
  }
  
  /**
   * Get entry values
   *
   * @return array Array of values in format: $field_id => $value
   */
  public function values()
  {
    
    $values = array();
    
    foreach ( $this->form()->fields() as $field )
    {
    
      try
      {
        $field_value = $field->value( $this );
      }
      catch ( Exception $e )
      {
        $field_value = '';
      }
      
      // Store our value
      $value = new FormValue();
      $value->entry = $this;
      $value->field = $field;
      $value->value = $field_value;
      $values[] = $value;
    }
    
    return $values;
    
  }

}
