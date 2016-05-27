<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Exports an entry into a PDF file
 *
 * Used as a template class for exporting PDF or HTML file
 */

class Export
{
  
  public $type      = 'application/pdf';    // Exported file type
  public $filename  = 'Unknown.pdf';        // Exported file name
  public $generated = false;                // Indicated if generated process has been done already
  
  public $template_path;
  public $path;
  public $options;
  public $maps;
  public $file;
  public $template;
  
  public $hash; // A temporary hash for this export process
  
  /**
   * Get temporary hash
   *
   * @return string Temporary hash
   */
  public function hash()
  {
  
    // Return hash if it was created already
    if ( $this->hash ) 
      return $this->hash;
      
    // Generate a random string
    $this->hash = $this->template->file()->hash();
    return $this->hash;
  
  }
  
  /**
   * Get file type
   *
   * @return string MIME type
   */
  public function type()
  {
    return $this->type;
  }
  
  /**
   * Get generated file path
   *
   * @return string Path to generated PDF
   */
  public function path()
  {
    
    if ( $this->path )
      return $this->path;
    
    // Generate the file if this hasn't been done already
    if ( ! $this->generated )
    {
    
      $this->generated = true;
      $this->generate();
      
    }
      
    return $this->path;
  }
  
  /**
   * Read the generated file and send it to the browser
   */
  public function read()
  {
  
    $this->path(); // Generated the file
  
    header( 'Content-Type: ' . $this->type() );
    header( 'Content-Disposition: inline; filename="' . $this->filename() . '"' );
    header( 'Content-Length: ' . @filesize( $this->path() ) );
    
    readfile( $this->path() );
  
  }
  
  /**
   * Create the file and show a progress bar while creating it
   */
  public function read_with_progress()
  {
  
    // If generated_file variable is set, then probably the file was already generated
    if ( 
            isset( $this->options[ 'download_file' ] ) 
        and isset( $this->options[ 'download_hash' ] ) 
        and isset( $this->options[ 'attempt' ] ) 
    )
    {
    
      $temporary_file_hash = $this->options[ 'download_hash' ];
      $download_attempt    = $this->options[ 'attempt' ];
    
      // Calculate path to a temporary PDF file
      $temporary_file_name = $this->options[ 'download_file' ];
      $temporary_file_name = TempGeneratedFile::clean_filename( $temporary_file_name );
      $temporary_file_path = TempGeneratedFile::folder() . $temporary_file_name;
      
      // If it exists, don't generate anthing, just download it
      if ( file_exists( $temporary_file_path ) )
      {
      
        if ( md5_file( $temporary_file_path ) == $temporary_file_hash )
        {
        
          $this->generated = true;
          $this->path = $temporary_file_path;
          $this->read();
          return;
        
        }
        
      }
      elseif ( $download_attempt > 10 )
      {
        throw new Exception( "Temporary PDF file wasn't found." ); 
      }
      
    }
  
    // Show a window with a progress bar
  
    require Framework::path() . 'views/export/with_progress.php';
  
    try
    {

      Progress::step( 
        __( 'Exporting...', 'export2pdf' ), 
        5, 
        100 
      );
      
      Progress::pulsate();
    
      $this->generate();
      
      // Store in a temporary folder
      $binary_pdf_data = file_get_contents( $this->path );
      $temporary_file = new TempGeneratedFile();
      $temporary_file->write( $binary_pdf_data );
    
      // Get an url to download the file
      $current_request = $_GET;
      
      if ( ! isset( $current_request[ 'attempt' ] ) )
        $current_request[ 'attempt' ] = 0;
      
      $current_request[ 'download_file' ] = $temporary_file->name();
      $current_request[ 'download_hash' ] = md5_file( $temporary_file->path() );
      $current_request[ 'attempt' ]       += 1;
      
      $download_link = '?' . http_build_query( $current_request );
      
      Progress::step( 
        sprintf(
          __( 'Downloading <a href="%s">%s</a>...', 'export2pdf' ), 
          $download_link,
          $this->filename()
        ),
        100
      );
      
      // Progress::pulsate( 2 );
    
      echo '<script type="text/javascript">location.assign("' . $download_link . '");</script>';
    
    }
    catch ( Exception $e )
    {
    
      // An error occurred while generating a PDF
    
      // TODO: show a user friendly error message when $error is too long
      $message = $e->getMessage();
      $error = json_encode( array( 'error' => $message ) );
      echo '<script type="text/javascript">export2pdf_fatal_progress_error(' . $error . ');</script>';
      
      // $e->show();
      
    }
  
  }
  
  /**
   * Get file name
   *
   * @return string Name of file (basename)
   */
  public function filename()
  {
    $generated_filename = $this->filename;
    $generated_filename = preg_replace('/[^a-zA-Z0-9\_\.\- ]+/', ' ', $generated_filename);
    $generated_filename = preg_replace('/ +/', ' ', $generated_filename);
    $generated_filename = trim($generated_filename);
    $generated_filename = trim($generated_filename, '_');
    if ( ! $generated_filename )
      $generated_filename = 'Form';
    return $generated_filename . '.pdf';
  }
  
  /**
   * Get template name
   *
   * @return string MIME type
   */
  public function template_path()
  {
    return $this->template_path;
  }
  
  /**
   * Export to PDF
   */
  public function generate()
  {
    throw new Exception( 'Generate PDF function not implemented.' );
  }
  
  /** 
   * Constructor
   * TODO: Handle PDF or Word export
   *
   * @param $template Template Template that will be used for the PDF
   * @param $entry FormEntry Entry that will be exported
   * @param $options array (optional) Additional options
   */
  public static function create( $template, $entry, $options = array() )
  {
  
    // Initialize the process
    if ( $template instanceof TemplateHtml )
      $class = 'Export_Html';
    else
      $class = 'Export_Pdf';    
      
    $class = "\\Export2Pdf\\" . $class; // Add namespace
    $process = new $class();
    
    // Set up PDF data
    $process->template      = $template;
    $process->options       = $options;
    $process->template_path = $template->file()->path();
    $process->filename      = $template->name();
    $process->maps          = TemplateMap::export( $template, $entry );
    
    return $process;
  }
  
}
