<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * General framework functions
 */
 
class Framework
{
  
  /**
   * Path to a folder that contains PDF data
   */
  public static function pdf_data_path()
  {
    // Get path to wp-content/uploads
    $upload_dir = wp_upload_dir();
    $path = $upload_dir['basedir'] . '/export2pdf/templates/';
    
    // Create this folder if it doesn't exist
    Tools::mkdir( $path );
    
    return $path;
      
  }

  /**
   * URL to a folder that contains PDF data
   */
  public static function pdf_data_url()
  {
    // Get URL to wp-content/uploads
    $upload_dir = wp_upload_dir();
    $url = $upload_dir['baseurl'] . '/export2pdf/templates/';
    return $url;
      
  }
  
  /**
   * URLs in the dashboard
   */
  public static function url( $data = array() )
  {
    // If page argument is not set, 
    // then take it from current request
    if ( ! isset( $data[ 'page' ] ) )
    {
      if ( isset( $_GET['page'] ) )
        $data[ 'page' ] = $_GET[ 'page' ];
      else
        $data[ 'page' ] = 'export2pdf';
    }
    
    // Build query based on $data array
    return admin_url( 'admin.php' ) . '?' . http_build_query( $data );
  }

  /**
   * Check if current user is an administrator
   *
   * @return bool TRUE if admin, FALSE if not admin
   */
  public static function is_admin()
  {
    return current_user_can( 'manage_options' );
  }
  
  /**
   * Plugin base URL
   *
   * @return string Plugin base URL
   */
  public static function plugin_url()
  {
    return plugins_url( '/', self::plugin_path() . 'export2pdf.php' );
  }
  
  /**
   * Plugin base path
   *
   * @return string Plugin base path
   */
  public static function plugin_path()
  {
    return realpath( __DIR__ . '/../../' ) . '/';
  }
  
  /**
   * URL for /framework/assets/ folder
   */
  public static function assets_url()
  {
    return static::plugin_url() . 'framework/assets/';
  }
  
  
  /**
   * System path for /framework/assets/ folder
   */
  public static function assets_path()
  {
    return self::plugin_path() . 'framework/assets/';
  }
  
  /**
   * System path for /framework/ folder
   */
  public static function path()
  {
    return self::plugin_path() . 'framework/';
  }
  
}
