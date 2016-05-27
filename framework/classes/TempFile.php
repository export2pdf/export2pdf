<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Temporary file management
 */
 
class TempFile
{
  
  public $path;
  
  const FOLDER = 'temporary';
  const EXPIRE_IN = 1800;     // 30 minutes
  
  public static $created_files = array();
  
  /**
   * Get path to temporary folder and create .htaccess file inside this folder
   *
   * @return string Path to temporary folder
   */
  public static function folder()
  {
  
    // Get path to wp-content/uploads
    $upload_dir = wp_upload_dir();
    $path = $upload_dir['basedir'] . '/export2pdf/' . static::FOLDER . '/';
    
    // Create this folder if it doesn't exist
    Tools::mkdir( $path );
    
    // Create an .htaccess file to block all requests to this folder
    $htaccess_path = $path . '.htaccess';
    if ( ! file_exists( $htaccess_path ) )
    {
      $htaccess_content = "Order deny,allow\nDeny from all\n";
      @file_put_contents( $htaccess_path, $htaccess_content );
    }
    
    return $path;
    
  }
  
  /**
   * Get path to temporary file 
   *
   * @return string Path to temporary folder
   */
  public function path()
  {
    return $this->path;
  }
  
  /**
   * Create a file
   *
   * @param $extension string File extension
   */
  public function __construct( $extension = 'txt' )
  {
  
    $this->path = tempnam( $this->folder(), 'export2pdfTempFile' );
    
    // We want to delete created file and add an extension instead
    @unlink( $this->path );
    $this->path .= '.' . $extension;
    
    // Store file, so that we can clean it up afterwards
    static::$created_files[] = $this;
    
  }
  
  /**
   * Temporary file name
   */
  public function name()
  {
    return basename( $this->path() );
  }
  
  /**
   * Write to file
   *
   * @param $data string Data to write
   */
  public function write( $data = '' )
  {
  
    if ( @file_put_contents( $this->path(), $data ) === FALSE )
      throw new Exception( "Could not write " . strlen( $data ) . " bytes to temporary file " . $this->path() ); 
    
  }
  
  /**
   * Delete file
   */
  public function delete()
  {
    @unlink( $this->path() );
  }
  
  /**
   * Clean up temporary files
   */
  public static function clean_up()
  {
  
    // Clean up all temporary files created by this process
    foreach ( static::$created_files as $file )
      $file->delete();
      
    // Clean up all temporary files created by other processes that have expired
    $temporary_files = Tools::files_in_folder( static::folder() );
    foreach ( $temporary_files as $temporary_file )
      if ( time() - filemtime( $temporary_file ) > self::EXPIRE_IN )
      {
      
        try
        {
          Tools::rm( $temporary_file );
        }
        catch ( Exception $e )
        {
        }
        
      }
    
  }
  
}
