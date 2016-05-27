<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
  
/**
 * A prototype to format a field map
 */
 
class Format
{
  
  public static $all_formats;
  
  public $template;
  
  public $name  = '(unknown format)';
  public $group = 'Other';
  
  public $default_format = 'None';
  
  public static $groups = array(
    'Time',
    'Numbers',
    'Text',
    'Other',
  );
  
  /**
   * Constructor that will store a formatting option,
   * so that we can use it in field map designer
   */
  public function __construct() 
  {
    static::$all_formats[] = $this;
  }
  
  /**
   * Check if this format is available
   * For example, if a corresponding plugin is activated 
   */
  public function available()
  {
    return true;
  }
  
  /**
   * Check if this format should be visible
   * For example, PDF and HTML templates may need different formatting options
   */
  public function visible()
  {
    return true;
  }
  
  /**
   * Get all available formats
   *
   * @return array Array of Format classes
   */
  public static function all()
  {
  
    if ( static::$all_formats )
      return static::$all_formats;
        
    $formats = array();
    
    // Get the list of files in "Format" folder and create corresponding classes
    $class_files = Tools::files_in_folder( __DIR__ . '/Format/' );
    foreach ( $class_files as $class_file )
    {
    
      if ( ! preg_match( '/\.php$/', $class_file ) )
        continue;
    
      $klass = basename( $class_file );
      $klass = str_replace( ".php", "", $klass );
      $klass = "\\Export2Pdf\\Format_" . $klass;
      $formats[] = new $klass();
      
    }
    
    // Remove unavailable formats
    $formats = array_filter( 
      $formats,
      function ( $format )
      {
        return $format->available();
      }
    );
    
    // Sort them by their group
    usort( $formats, function ( $a, $b ) {
      
      if ( $a->group != $b->group )
      {
      
        // If the group is not the same,
        // then sort by group order
        
        // $result = strcmp( $a->name(), $b->name() );
        
        $group_order = self::$groups;
        
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
    
    static::$all_formats = $formats;
    
    return $formats;
    
  }
  
  /**
   * Get format name
   *
   * @return string Format name
   */
  public function name() 
  {
    return $this->name;
  }
  
  /**
   * Get format ID
   *
   * @return string Class name of this object, that will be used as formatting ID
   */
  public function id()
  {
    $klass = get_class( $this );
    $klass = str_replace( "Export2Pdf\\Format_", '', $klass );
    return $klass;
  }
  
  /**
   * Format a field map
   *
   * @param $map TemplateMap Mapping that needs to be formatted
   *
   * @return mixed Formatted value of this field map
   */
  public static function map( $map )
  {
  
    // Get mapped field value
    $value = $map->value()->get_value();
    
    try
    {
      $value = $map->format()->process( $value, $map->options_array() );
    }
    catch ( Exception $e )
    {
    }
    
    return $value;
    
  }

  /**
   * Formats a value
   *
   * @param $value mixed Value to format (text, date, ...)
   * @param $options array Template map options from the template map
   *
   * @return mixed Formatted value
   */
  public function process( $value, $options = array() )
  {
    return $value;
  }
  
  /**
   * Get default options
   *
   * @return array List of default options
   */
  public function default_options()
  {
    return array();
  }
  
  /**
   * Shows additional options for this format
   *
   * @param $map TemplateMap Selected field mapping
   *
   * @return string HTML that contains additional formatting options
   */
  public function show_options( $map = NULL )
  {
    return '';
  }
  
}
