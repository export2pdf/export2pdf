<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * A PDF field prototype
 */
 
class PdfField extends Model
{
  
  const TABLE = "file_fields";
 
  public $file; 
  public $page;
  
  public $name;
  public $type;
  public $x;
  public $y;
  public $width;
  public $height;
  public $pdf_file_id;
  public $pdf_page_id;
  public $options;
  public $description = "an unknown field";
  
  /**
   * Create PDF field class automatically,
   * depending on field type.
   */
  public static function get( $id = null )
  {
  
    $field = new PdfField( $id );
    $field_type = $field->type;
    
    if ( ! $field_type )
      return $field;
      
    $field_type = ucfirst( $field_type );
    $klass = "Export2Pdf\\PdfField_" . $field_type;
    if ( class_exists( $klass ) )
      return new $klass( $id );
    
    return $field;
  }
  
  /**
   * Unserialize options if they are set
   */
  public function __construct( $id = null )
  {
  
    // Call parent constructor method
    // Gets model information
    parent::__construct( $id );
    
    // Unserialize options
    if ( $this->options )
      $this->options = @unserialize( $this->options );
    // If something went wrong, then just set an empty array
    if ( ! $this->options )
      $this->options = new \stdClass(); 
    
  }
  
  /**
   * Get page object
   *
   * @return PdfPage PDF Page object
   */
  public function page()
  {
  
    if ( $this->page )
      return $this->page;
      
    if ( $this->pdf_page_id )
      $this->page = new PdfPage( $this->pdf_page_id );
      
    return $this->page;
    
  }
  
  /**
   * Get name
   */
  public function name()
  {
    return $this->name;
  }
  
  /**
   * List of possible values of this field,
   * as defined in the PDF
   *
   * return array List of values as strings
   */
  public function values()
  {
    if ( isset( $this->options->values ) )
    {
      $values = (array) $this->options->values;
      sort( $values );
      return $values;
    }
    return array();
  }
  
  /**
   * Check if this field has some predefined values.
   *
   * return bool TRUE if a field has predefined values, FALSE otherwise
   */
  public function has_predefined_values()
  {
    return ( count( $this->values() ) > 0 );
  }
  
  /**
   * List of possible values of this field,
   * as defined in the PDF
   */
  public function description()
  {
    $text = 'This is ' . $this->description . '.';
    if ( $this->has_predefined_values() )
    {
      $values = $this->values();
      if ( count( $values ) > 1 )
      {
      
        // There are multiple values,
        // so we want to display them as a list
        $text .= '<br />It can have these values:</p><ul class="list">';
        $text .= implode( 
          '', 
          array_map( 
            function ( $value ) 
            { 
              return "<li><i>" . esc_html( $value ) . "</i></li>";
            }, 
            $values
          )
        );
        $text .= '</ul><p>';
        
      }
      else
      {
      
        // There's only one value,
        // so no need to make it a list
        $value = $values[ 0 ];
        $text .= '<br />Its export value is <i>' . esc_html( $value ) . '</i>';
        
      }
    }
    return $text;
  }
  
  /**
   * Save 
   */
  public function save()
  {
    // Store page ID
    $this->pdf_page_id = $this->page()->id();
  
    // Call parent save method
    parent::save();
    
  }
  
  /**
   * JSON representation
   * (Used in templates) 
   *
   * @param $template Template Template that this field is related to.
   *
   * @return stdClass Class with field properties
   */
  public function info( $template )
  {
  
    $json = json_decode( json_encode ( $this ) );
    
    // export2pdf_log( 'log', "Getting information for field " . $this->id() );
    
    // Get information about what form field this PDF field is mapped to
    $map = $template->map( $this );
    $json->mapped = $map->id();
    if ( $json->mapped )
    {
      $json->mapped_title    = $map->source()->name();
      $json->mapped_field_id = $map->source()->id();
    }
    
    return $json;
    
  }
  
}
