<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * HTML Template
 */

class TemplateHtml extends Template
{

  public $type = 'TemplateHtml';
  
  public static $styles = array( 'normalize', 'shortcode_field', 'style', 'tinymce' );
  
  public $default_options = array(
    'font_size'              => '16',
    'font_family'            => 'Open Sans',
    'font_color'             => '#333333',
    'paper_size'             => 'A4',
    'paper_orientation'      => 'portrait',
    'paper_margins'          => '10mm,10mm,10mm,10mm',
  );
   
  // PDF resolution
  const DPI = 300;
  
  // How many mm in one inch
  const MM_PER_INCH = 25.4;
  
  // Scales up or down the editor
  const HTML_WIDTH = 800;
    
  /**
   * Get PDF file object
   */
  public function file()
  {
    return new PdfHtmlFile( intval( $this->pdf_file_id ) );
  }
    
  /**
   * Get default options
   */
  public function default_options()
  {
  
    $options = $this->default_options;
    
    // Our options are made for metric system
    // If the client uses imperial system, then change these options
    if ( Settings::get( 'measurement_unit' ) == 'in' )
    {
      $options[ 'paper_margins' ] = '1in,1in,1in,1in';
      $options[ 'paper_size' ]    = 'Letter';
    }
    
    return $options;
  }
                            
  public static $paper_sizes = array(
  
    /**
     * All available options:
     * 'A0', 'A1', 'A2', 'A3', 'A5', 'A6', 'A7', 'A8', 'A9', 'B0', 'B1', 'B10', 'B2', 
     * 'B3', 'B4', 'B5', 'B6', 'B7', 'B8', 'B9', 'C5E', 'Comm10E', 'DLE', 'Executive', 
     * 'Folio', 'Ledger', 'Legal', 'Tabloid',
     * 
     * List of paper sizes:
     * http://resources.printhandbook.com/pages/paper-size-chart.php
     *
     * Format:
     * paper size => array( width, height ) in portrait mode, in mm
     */
     
    'A4'      => array( 210, 297 ),
    'Letter'  => array( 216, 279 ),
    'Legal'   => array( 216, 356 ),
    'A5'      => array( 148, 210 ),
    'Ledger'  => array( 279, 432 ),
    
  );
  
  public static $paper_orientations = array( 'portrait', 'landscape' );

  /**
   * Get document format
   *
   * @return string 'portrait' or 'landscape'
   */
  public function format()
  {
    return $this->option( 'paper_orientation' );
  }

  /** 
   * Convert mm to pixels (in PDF)
   *
   * @param $mm int Millimeters
   *
   * @return int Pixels that correspond to $mm millimeters for given DPI
   */
  public static function mm_to_pixel( $mm, $round = FALSE )
  {
  
    // If $mm is in inches, transform to mm
    if ( preg_match( '/^([\d\.]+)in$/', $mm, $matches ) )
    {
      $mm  = $matches[ 1 ];
      $mm  = floatval( $mm );
      $mm *= self::MM_PER_INCH;
    }
  
    // TODO: how to calulate this?
    
    $koeff   = 3.18 * 1.17;
    $pixels  = ( $mm * $koeff );
    
    
    // Round to integer
    if ( $round )
      $pixels = round( $pixels );
    
    return $pixels;
    
  }
  
  /** 
   * Convert mm to pixels (in PDF)
   *
   * @param $mm int Millimeters
   *
   * @return int Pixels that correspond to $mm millimeters for given DPI
   */
  public static function mm_to_pixel_in_editor( $mm, $round = FALSE )
  {
  
    // If $mm is in inches, transform to mm
    if ( preg_match( '/^([\d\.]+)in$/', $mm, $matches ) )
    {
      $mm  = $matches[ 1 ];
      $mm  = floatval( $mm );
      $mm *= self::MM_PER_INCH;
    }
  
    // Convert mm to pixels
    $a4_page_width_in_mm = self::$paper_sizes[ 'A4' ][ 0 ];
    $koeff = $a4_page_width_in_mm / self::HTML_WIDTH;
    $pixels  = ( $mm / $koeff );
    
    // Round to integer
    if ( $round )
      $pixels = round( $pixels );
    
    return $pixels;
    
  }

  /**
   * Gets template font
   *
   * @return Font Template font
   */
  public function font()
  {
  
    $font_family = $this->option( 'font_family' );
    return Font::get( $font_family );
  
  }

  /**
   * Gets template styles
   */
  public function style( $for_editor = FALSE )
  {
    
    $styles = '';
    
    // Append font import
    
    $styles .= $this->font()->import_statement();
    
    // Get the list of options for this template
    
    $options = $this->options_array();
      
    // Generate page size options
    
    $paper_size      = $options[ 'paper_size' ];
    
    if ( ! isset( self::$paper_sizes[ $paper_size ] ) )  
      throw new Exception( "Unknown paper size " . $paper_size );
      
    $page_dimensions = self::$paper_sizes[ $paper_size ];
    
    if ( $options[ 'paper_orientation' ] == 'landscape' )
    {
      // For landscape mode, switch values
      $page_dimensions = array_reverse( $page_dimensions );
    }
    
    $options[ 'page_width' ]   = self::mm_to_pixel( $page_dimensions[ 0 ], TRUE ) . 'px';
    $options[ 'page_height' ]  = self::mm_to_pixel( $page_dimensions[ 1 ], TRUE ) . 'px';
    
    // Page margins
    
    $margins = explode( ',', $options[ 'paper_margins'] );
    
    if ( count( $margins ) == 4 )
    {
    
      // Convert margin mm to pixels 
      $padding  = "";
      $padding .= self::mm_to_pixel( $margins[ 0 ], TRUE ) . "px ";
      $padding .= self::mm_to_pixel( $margins[ 1 ], TRUE ) . "px ";
      $padding .= self::mm_to_pixel( $margins[ 2 ], TRUE ) . "px ";
      $padding .= self::mm_to_pixel( $margins[ 3 ], TRUE ) . "px ";
      
    }
    else
    {
    
      // Something went wrong
      // $padding = self::mm_to_pixel( 10 ) . "px";
      
      throw new Exception( "Bad paper margin value: " . $options[ 'paper_margins'] );
      
    }
    $options[ 'page_padding' ] = $padding;
    
    // Append styles
    
    foreach ( self::$styles as $style )
    {
    
      // Don't include TinyMCE-specific styles
      if ( !$for_editor and ( $style == 'tinymce' ) )
        continue;
    
      // Get CSS stylesheet content
      $style_path = \Export2Pdf\Framework::assets_path() . 'css/template_html/' . $style . '.css';
      $css = file_get_contents( $style_path );
      
      // Replace CSS content with template options
      foreach ( $options as $option_key => $option_value )
      {
        $option_tag   = strtoupper( $option_key );
        $css          = str_replace( $option_tag, $option_value, $css );
      }
    
      // Append the stylesheet to global styles
      $styles .= $css . "\n";
      
    } 
    
    return $styles;
    
  }

  /**
   * Creates a PDF file for this template
   */
  public function add_pdf_file( $pdf_path )
  {
    // We don't need this.
  }
  
  /**
   * Creates an HTML file for this template
   */
  public function add_html_file()
  {
    // Create the file
    $html = new PdfHtmlFile();
    $html->save();
    
    // Write to HTML file disk
    $html->set_content();
    
    // Update file ID for this template
    $this->pdf_file_id = $html->id();
    $this->type        = 'TemplateHtml';
    $this->save();
  }
  
  /**
   * Creates an empty field
   *
   * HTML form's don't have a defined set of fields,
   * so these fields are created from scratch,
   * just to make it all work.
   */
  public function field()
  {
  
    $field = new PdfField();
    $field->template = $this;
    return $field;
    
  }
  
  /**
   * MD5 Hash of the HTML template
   *
   * @return stirng MD5 checksum of the HTML code
   */
  public function hash()
  {
    return md5( file_get_contents( $this->file()->path() ) );
  }
 
  /**
   * Get path to preview image
   *
   * @return string Path to image of the first page of PDF file
   */
  public function preview_path()
  {
    try
    {
      return dirname( $this->file()->path() ) . '/preview.png';
    }
    catch ( Exception $e )
    {
      return '';
    }
  }
  
  /**
   * Get path to preview hash
   *
   * @return string Path to a text file that contains MD5 hash of the template
   */
  public function preview_hash_path()
  {
  
    $preview_path = $this->preview_path();
    
    if ( $preview_path )
      return $preview_path . '.hash';
    
    return '';
    
  }
  
  /**
   * Get URL to preview image
   *
   * @return string URL to image of the first page of PDF file
   */
  public function preview_url()
  {
  
    $preview_path = $this->preview_path();
    
    if ( $preview_path and file_exists( $preview_path ) )
    {
      $preview_url = dirname( $this->file()->url() ) . '/' . basename( $preview_path );
      return $preview_url;
    }
      
    return false;
  }
  
  /**
   * Checks if preview image corresponds to the template
   *
   * @return bool TRUE if corresponds, FALSE if it doesn't.
   */
  public function preview_changed()
  {
    
    $preview_path      = $this->preview_path();
    $preview_hash_path = $this->preview_hash_path(); // MD5 hash of the HTML template
    
    if ( ! $preview_path or ! $preview_hash_path ) 
      return false;
      
    // If preview or hash doesn't exist
    if ( ! file_exists( $preview_path ) or ! file_exists( $preview_hash_path ) )
      return true;
      
    // If preview timestamp if older than template timestamp
    if ( file_get_contents( $preview_hash_path ) != $this->hash() )
      return true;
    
    return false;
    
  }

}
