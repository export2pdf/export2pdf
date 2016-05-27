<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Common functions
 */

class Tools
{
  
  /**
   * Trim a string
   */
  public static function trim( $string )
  {
    $regex  = '[ \t\r\n]+';
    $string = preg_replace( '/^' . $regex . '/', '', $string );
    $string = preg_replace( '/' . $regex . '$/', '', $string );
    return $string;
  }

  /**
   * Helper function that enables error reporting
   * for easier debugging
   */
  public static function show_errors()
  {
    ini_set( 'display_errors', 'on' );
    error_reporting( E_ALL );
  }

  /**
   * Redirect to an URL
   *
   * @param $url string URL where we need to make redirection to.
   */
  public static function redirect( $url )
  {
    if ( ! headers_sent() )
    {
      header( "Location: $url" );
    }
    else
    {
      echo '
        <script type="text/javascript">
          location.assign(\'' . addslashes( $url ) . '\');
        </script>
      ';
    }
    exit;
  }

  /**
   * Create a folder on the server
   */
  public static function mkdir( $path )
  {
 
    // If folder exists, then don't create it
    if ( file_exists( $path ) )
      return;
  
    if ( ! file_exists( $path ) )
      @mkdir( $path, 0777, TRUE );
      
    if ( ! file_exists( $path ) )
    {
      $error = error_get_last();
      throw new Exception( 
        "The plugin wasn't able to create this folder on the server: <br />" .
        "<code>" . $path . "</code><br />" .
        "The reason was: <strong>" . $error['message'] . "</strong><br />" .
        "Please make sure that folder <code>" . dirname( $path ) . "</code> is writable for the server.<br />" .
        "You can also try to <strong>change its permissions</strong> to 777 using an FTP client.<br />" .
        "For more information, see <a target='_blank' href='https://codex.wordpress.org/Changing_File_Permissions#Using_an_FTP_Client'>https://codex.wordpress.org/Changing_File_Permissions#Using_an_FTP_Client</a>."
      );
    }
    
  }
  
  /**
   * Helper function to flush output
   */
  public static function flush()
  {
    ob_flush();
    flush();
  }
  
  /**
   * Gets lists of files in a folder
   *
   * @param string $folderPath string Path to folder where we need to get files from
   * @return array Array of files in $folderPath
   */
  public static function files_in_folder( $folder_path, $recursive = FALSE )
  {
    if ( ! file_exists( $folder_path ) )
      throw new Exception( "Folder $folder_path doesn't exist.");
   
    $files = array();
    
    $folder_path = realpath( $folder_path );
    
    // Get list of files in $folderPath
    foreach ( glob( $folder_path . '/{,.}[!.,!..]*', GLOB_MARK | GLOB_BRACE ) as $file_name )
      if ( ! is_link( $file_name ) )
        $files[] = $file_name;

    sort( $files );
    
    // Get list of all files in subfolders
    if ( $recursive )
    {
      foreach ( $files as $file )
      {
        if ( is_dir( $file ) )
        {
          $files_in_subfolder = self::files_in_folder( $file, $recursive );
          $files = array_merge( $files, $files_in_subfolder );
        }
      }
    }
    
    return $files;
  }
  
  /**
   * Delete files in a folder recursively
   */
  public static function rm( $path, $throw_exception = TRUE )
  {

    if ( ! $path or empty( $path ) )
      throw new Exception( "File to delete cannot be empty!" );

    if ( is_dir( $path ) )
    {

      // This is a folder

      $files = Tools::files_in_folder( $path, TRUE );
     
      $files = array_reverse( $files );
      
      // Delete all files first
      foreach ( $files as $file )
        if ( ! is_dir( $file ) )
          @unlink( $file );
          
      // Delete all folders first
      foreach ( $files as $file )
        if ( is_dir( $file ) )
          @rmdir( $file );
          
      @rmdir( $path );
    
    }
    else
    {
    
      // This is a normal file
      @unlink( $path );
      
    }
    
    if ( $throw_exception )
      if ( file_exists( $path ) )
        throw new Exception( "It wasn't possible to delete file or folder: " . $path );

  }
  
  /**
   * Checks if HTTP Method is POST
   *
   * @return bool TRUE is method is POST, FALSE if method is not POST
   */
  public static function is_post()
  {
    return ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' );
  }
  
  /**
   * Copy a file on the server
   */
  public static function copy( $old_path, $new_path )
  {
 
    // Check if it exists
    if ( ! file_exists( $old_path ) )
      throw new Exception( "File <code>$old_path</code> doesn't exist, so we can't copy it to <code>$new_path</code>");
  
    if ( file_exists( $new_path ) )
      @unlink( $new_path );
  
    if ( file_exists( $new_path ) )
    {
      $error = error_get_last();
      throw new Exception( 
        "The plugin wasn't able to delete this file on the server: <br />" .
        "<code>" . $new_path . "</code><br />" .
        "The reason was: <strong>" . $error['message'] . "</strong><br />" .
        "Probably it's not writable.<br />" .
        "You can try to <strong>change its permissions</strong> to 777 using an FTP client.<br />" .
        "For more information, see <a target='_blank' href='https://codex.wordpress.org/Changing_File_Permissions#Using_an_FTP_Client'>https://codex.wordpress.org/Changing_File_Permissions#Using_an_FTP_Client</a>."
      );
    }
  
    @copy( $old_path, $new_path );
      
    if ( ! file_exists( $new_path ) )
    {
      $error = error_get_last();
      throw new Exception( 
        "The plugin wasn't able to delete this file on the server: <br />" .
        "<code>" . $new_path . "</code><br />" .
        "The reason was: <strong>" . $error['message'] . "</strong><br />" .
        "Probably it's not writable.<br />" .
        "You can try to <strong>change its permissions</strong> to 777 using an FTP client.<br />" .
        "For more information, see <a target='_blank' href='https://codex.wordpress.org/Changing_File_Permissions#Using_an_FTP_Client'>https://codex.wordpress.org/Changing_File_Permissions#Using_an_FTP_Client</a>."
      );
    }
    
  }

}
