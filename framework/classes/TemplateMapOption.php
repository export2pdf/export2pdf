<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Template map options
 */

class TemplateMapOption extends Model
{

  const TABLE = "template_map_options";

  public $map;
  
  public $id;
  public $value;
  public $key;
  public $template_map_id;

  /**
   * Get template map
   *
   * @return TemplateMap Template map for this option
   */
  public function map()
  {
    
    if ( $this->map )
      return $this->map;
    
    $this->map = Template::get( $this->template_map_id );
      
    return $this->map;
    
  }

}
