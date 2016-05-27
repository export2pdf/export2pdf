<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();


/**
 * WordPress Posts
 */

class WordPressPosts extends Form
{
  
  public $id = 'posts';
  
  public $posts; // List of all WooCommerce Orders
  public $fields; // List of available fields
  
  /**
   * Get form name
   *
   * @return string Form name
   */
  public function name()
  {
    return __( 'Posts', 'wordpress' );
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
    $class_files = Tools::files_in_folder( __DIR__ . '/WordPressField/' );
    foreach ( $class_files as $class_file )
    {
      $klass = basename( $class_file );
      $klass = str_replace( ".php", "", $klass );
      $klass = "\\Export2Pdf\\WordPressField_" . $klass;
      $fields[] = new $klass();
    }
    
    // Sort fields by their group
    usort( $fields, function ( $a, $b ) {
      
      if ( $a->group != $b->group )
      {
      
        // If the group is not the same,
        // then sort by group order
        
        // $result = strcmp( $a->name(), $b->name() );
        
        $group_order = WordPressField::$groups;
        
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
    return admin_url( 'edit.php' ) . '?post_type=post';
  }
  
  /**
   * Get the list of entries for this form
   */
  public function entries()
  {
    
    if ( $this->products )
      return $this->products;
    
    $products = array();
    
    $product_posts = get_posts( array(
      'post_type'   => 'product',
      'post_status' => 'any',
    ));
    
    foreach ( $product_posts as $product_post )
    {
      $product       = new WordPressPost( $product_post->ID );
      $product->form = $this;
      $products[]    = $product;
    }
    
    $this->products = $products;
    
    return $products;
    
  }
  
  /** 
   * Get link to add an entry to this form
   *
   * @return string URL to add an entry to this form
   */
  public function add_entry_url()
  {
    return admin_url( 'post-new.php' ) . '?post_type=product';
  }

}
