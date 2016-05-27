<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * A template option
 */

class TemplateOption extends Model
{

  const TABLE = "template_options";

  public $template;
  
  public $id;
  public $value;
  public $key;
  public $template_id;

  /**
   * Get template
   *
   * @return Template Template for this option
   */
  public function template()
  {
    
    if ( $this->template )
      return $this->template;
    
    $this->template = Template::get( $this->template_id );
      
    return $this->template;
    
  }

}
