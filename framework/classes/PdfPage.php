<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * PDF page that belongs to a PdfFile
 */
 
class PdfPage extends Model
{
  
  const TABLE = "file_pages";
 
  public $file; 
  public $fields;
  
  public $pdf_file_id;
  public $number;
  public $width;
  public $height;
  
  /**
   * Get page format
   *
   * @return string 'portrait' or 'landscape'
   */
  public function format()
  {
    if ( $this->height < $this->width )
      return 'landscape';
    return 'portrait';
  }
  
  /** 
   * Get page number
   *
   * @return int Page number starting from 1
   */
  public function number()
  {
    return $this->number;
  }
  
  /**
   * Get PDF file 
   *
   * @return PdfFile PDF file object
   */
  public function file()
  {
  
    if ( $this->file )
      return $this->file;
      
    if ( $this->pdf_file_id )
      $this->file = new PdfFile( $this->pdf_file_id );
      
    return $this->file;
    
  }
  
  
  /**
   * Get PDF fields 
   *
   * @return array Array of PDF fields on this page
   */
  public function fields()
  {
  
    if ( $this->fields )
      return $this->fields;
      
    $this->fields = PdfField::all(
      array(
        'pdf_file_id' => $this->file()->id(),
        'pdf_page_id' => $this->id(),
      )
    );
      
    return $this->fields;
    
  }
  
  /**
   * Get PDF fields, including radio buttons (that are duplicate)
   *
   * @return array Array of PDF fields on this page, including radio buttons
   */
  public function fields_with_occurrences()
  {
  
    $fields = $this->fields();
    
    // For each real field,
    // take its options and see,
    // if it is duplicated somewhere else on the page
    // (e.g. radio button)
    foreach ( $this->file()->fields() as $field )
    {
      if ( isset( $field->options->occurrences ) )
      {
     
        $occurrences = (array) $field->options->occurrences;
        
        foreach ( $occurrences as $occurrence )
        {
          if ( is_object( $occurrence ) )
          {
          
            $occurrence_field         = clone $field;
            
            $occurrence_field->page   = (int)   $occurrence->page;
            $occurrence_field->width  = (float) $occurrence->width;
            $occurrence_field->height = (float) $occurrence->height;
            $occurrence_field->x      = (float) $occurrence->x;
            $occurrence_field->y      = (float) $occurrence->y;
            
            if ( $occurrence_field->page != $this->number() )
              continue;
            
            $fields[]                 = $occurrence_field;
          
          }
        }
        
      }
    }
    
    return $fields;
    
  }
  
  /**
   * When destroying a page, remove preview folders too
   */
  public function destroy()
  {
    
    // Delete templates/ID/pageN/ folder
    $folder = dirname( $this->file()->path() ) . "/page" . $this->number() . "/";
    Tools::rm( $folder, FALSE );
    
    parent::destroy();
  }
  
  /**
   * Get preview path
   *
   * @param string $size Size of the preview. Full
   *
   * @return string Path to the preview file
   */
  public function preview_path( $size = 'full' )
  {
    $folder = dirname( $this->file()->path() ) . "/page" . $this->number() . "/";
    Tools::mkdir( $folder );
    $file = $folder . "preview_" . $size . ".png";
    return $file;
  }
  
  /**
   * Checks if a preview page exists or not
   * 
   * @return bool TRUE if preview exists, FALSE if not
   */
  public function has_preview()
  {
    return file_exists( $this->preview_path() );
  }
  
  /**
   * Get preview URL
   *
   * @param string $size Size of the preview. Full
   *
   * @return string Path to the preview file
   */
  public function preview_url( $size = 'full' )
  {
  
    $preview_options = array(
      'action'   => 'export2pdf_pdf_page_preview',
      'page'     => $this->number(),
      'file'     => $this->file()->id(),
      'size'     => $size,
    );
    $preview_url = admin_url( 'admin-ajax.php' ) . '?' . http_build_query( $preview_options );
    return $preview_url;
    
  }
  
  /**
   * Generate page previews
   */
  public function generate_preview()
  {
    
    $path = $this->preview_path();

    if ( class_exists( "Export2Pdf\\Export2PdfOffline" ) )
    {
    
      // We're in offline mode. Generate preview directly
      $preview_binary_data = ApiRequest_PdfPreview::perform( $this->file()->path(), $this->number() );
    
    }
    else
    {

      try
      {
      
        // First, try without uploading the file
        $preview_binary_data = ApiRequest_PdfPreview::perform( 'hash:' . $this->file()->hash(), $this->number() );
        
      }
      catch ( PdfNotUploaded_Error $e )
      {
      
        // Then, upload file and retry
        ApiRequest_UploadPdf::perform( $this->file()->path(), $this->file()->hash() );
        $preview_binary_data = ApiRequest_PdfPreview::perform( 'hash:' . $this->file()->hash(), $this->number() );
      
      }
    
    }
    
    file_put_contents( $path, $preview_binary_data );
    
  }
  
}
