<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * A prototype for template actions (e.g. e-mails)
 */

class TemplateAction extends Model
{

  const TABLE = "template_actions";

  public $template;
  
  public $id;
  public $name;
  public $data;
  public $action_type;
  public $template_id;

  /**
   * Get form ID
   *
   * @return int|null Field ID
   */
  public function id()
  {
    return $this->id;
  }
  
  /**
   * Get form name
   *
   * @return string Field name
   */
  public function name()
  {
    return $this->name;
  }

}
