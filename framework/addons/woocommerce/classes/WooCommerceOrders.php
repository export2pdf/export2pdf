<?php

/**
 * A WooCommerce Order
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class WooCommerceOrders extends Form
{
  
  public $id = 'orders';
  
  public $orders; // List of all WooCommerce Orders
  public $fields; // List of available fields
  
  /**
   * Get form name
   *
   * @return string Form name
   */
  public function name()
  {
    return __( 'Orders', 'woocommerce' );
  }
  
  /**
   * Get fields
   *
   * @return array Array of FormidableField's
   */
  public function fields()
  {
  
    if ( $this->fields )
      return $this->fields;
  
    $fields = array();
    
    // Get the list of files in "WooCommerceOrderField" folder and create corresponding classes
    $class_files = Tools::files_in_folder( __DIR__ . '/WooCommerceOrderField/' );
    foreach ( $class_files as $class_file )
    {
      $klass = basename( $class_file );
      $klass = str_replace( ".php", "", $klass );
      $klass = "\\Export2Pdf\\WooCommerceOrderField_" . $klass;
      $fields[] = new $klass();
    }
    
    // Sort fields by their group
    usort( $fields, function ( $a, $b ) {
      
      if ( $a->group != $b->group )
      {
      
        // If the group is not the same,
        // then sort by group order
        
        // $result = strcmp( $a->name(), $b->name() );
        
        $group_order = WooCommerceOrderField::$groups;
        
        $pos_a = array_search( $a->group, $group_order );
        $pos_b = array_search( $b->group, $group_order );
        $result = $pos_a - $pos_b;
        
      }
      else
      {
      
        // If the group is the same,
        // then sort in alphabetical order
      
        $result = strcmp( $a->name(), $b->name() );
        
      }
        
      return $result;
    
    });
    
    $this->fields = $fields;
    
    return $fields;
    
  }

  /**
   * Get the list of e-mail actions that this form can send
   */
  public function emails()
  {
  
    $emails = array();
    
    $mailer          = WC()->mailer();
    $email_templates = $mailer->get_emails();
    
    foreach ( $email_templates as $email_template )
    {
      
      $action       = new FormAction();
      
      $action->id   = $email_template->id; 
      $action->name = $email_template->title; 
      
      $emails[] = $action;
      
    }
        
    return $emails;
    
  }
  
  /** 
   * Get link to view form
   *
   * @return string URL to view form
   */
  public function url()
  {
    return admin_url( 'post.php' ) . '?post=' . $this->id() . '&action=edit';
  }
  
  /** 
   * Get link to view entries of this form
   *
   * @return string URL to view entries of this form
   */
  public function entries_url()
  {
    return admin_url( 'edit.php' ) . '?post_type=shop_order';
  }
  
  /**
   * Get the list of entries for this form
   */
  public function entries()
  {
    
    if ( $this->orders )
      return $this->orders;
    
    $orders = array();
    
    $order_posts = get_posts( array(
      'post_type'   => 'shop_order',
      // 'post_status' => array( 'pending', 'processing', 'on-hold', 'completed', 'cancelled', 'refunded', 'failed' ),
      'post_status' => 'any',
    ));
    
    foreach ( $order_posts as $order_post )
    {
      $order       = new WooCommerceOrder( $order_post->ID );
      $order->form = $this;
      $orders[]    = $order;
    }
    
    $this->orders = $orders;
    
    return $orders;
    
  }
  
  /** 
   * Get link to add an entry to this form
   *
   * @return string URL to add an entry to this form
   */
  public function add_entry_url()
  {
    return false;
  }

}
