<?php

/**
 * A WooCommerce Order
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrder extends FormEntry
{
  
  public $meta_data;
  public $post;
  public $order;
  
  /**
   * Get entry name
   * First, try parent method
   * Then, try Formidable entry name
   *
   * @return string Entry title
   */
  public function name()
  {
  
    // E.g.: Order #999
    $order_title = sprintf( 
      __( 'Order #%s', 'woocommerce' ), 
      $this->order()->get_order_number()
    );
    
    // E.g.: John Smith
    $order_customer = $this->order()->get_formatted_billing_full_name();
    
    // E.g.: 123€
    $order_price = $this->order()->get_formatted_order_total();
    
    return "$order_title ($order_price, $order_customer)";
  }
  
  /**
   * Get WooCommerce order
   *
   * @return WC_Order WooCommerce order
   */
  public function order()
  {
    return $this->order;
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
      throw new Exception( "Couldn't find WooCommerce order #$id" );
    
    $this->order = new \WC_Order( $id );
    
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
