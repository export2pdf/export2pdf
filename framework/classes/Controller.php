<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Controller class for the framework
 */
 
class Controller
{

  const PAGE = "";

  public $variables = array();                // Array of variables that will be used in views
  public $action;
  public $is_modal = false;                   // Indicates if this is a modal window, e.g. Thickbox
  
  public static $additional_assets = array(); // Additional assets that this template may use
  public $controller_file = __FILE__;         // Indicates where class file is located

  public static $assets_enqueued = FALSE;     // Indicates if assets where enqued already

  public function __construct()
  { 
  }
  
  /**
   * Sets variables that define which mode
   * a user prefers (grid or table mode)
   */
  public function set_view_mode()
  {
    
    // Get default view mode from settings
    $mode = Settings::get( 'preferred_mode' );
    
    // Let's see if it is present in $_GET variables
    if ( isset( $_GET['mode'] ) ) 
      $mode = $_GET['mode'];
    
    // Filter it
    if ( ! in_array( $mode, array( 'grid', 'list' ) ) )
      $mode = 'grid';
      
    // Update settings if the view has changed
    if ( $mode != Settings::get( 'preferred_mode' ) )
      Settings::set( 'preferred_mode', $mode );
      
    $this->variables[ 'mode' ] = $mode;
    
    return $mode;
    
  }

  /**
   * Get URL for a controller
   * 
   * @param $controller_name string Controller name
   * @param $action_name string|null Action name
   */
  public static function url_for( $controller_name, $action_name = 'index', $additional_parameters = array() )
  {
    $controller_class = "\\Export2Pdf\\" . ucfirst( $controller_name ) . 'Controller';
    return $controller_class::action_url( $action_name, $additional_parameters );
  }

  /**
   * Enqueue additional asset for this controller
   *
   * @param $asset_name Basename of asset without extension
   */
  public static function add_asset( $asset_name )
  {
  
    if ( static::$assets_enqueued )
    {
    
      // If assets were enqueued already, then let's print them directly to the browser
    
      $asset_types = array( 
        'js',
        'css',
      );
      
      foreach ( $asset_types as $asset_type )
      {
          
        // Check if asset exists and print it
        $asset_relative_path            = $asset_type . '/' . $asset_name . '.' . $asset_type;
        $asset_relative_filesystem_path = $asset_relative_path;
        $asset_filesystem_path          = Framework::assets_path() . $asset_relative_filesystem_path;
        
        if ( ! file_exists( $asset_filesystem_path ) )
          continue;
          
        $asset_data = file_get_contents( $asset_filesystem_path );
          
        if ( $asset_type == 'js' )
        {
          echo '<script type="text/javascript">' . $asset_data . '</script>';
        }
        
        if ( $asset_type == 'css' )
        {
          echo '<style type="text/css">' . $asset_data . '</style>';
        }
        
      }
    
      return;
    
    }
  
    self::$additional_assets[] = $asset_name ;
    
  }

  /**
   * Enqueue assets for this controller
   */
  public static function add_assets( $files = NULL )
  {

    static::$assets_enqueued = TRUE;

    $klass      = get_called_class();
    $controller = new $klass();
    
    // Set file names that might be enqueued
    // This function will check if they exist,
    // and if they do, they will be enqueued
    
    $files = array();
    
    $files[] = 'style';
    $files   = array_merge( $files, self::$additional_assets );
    
    // Controller action dependent assets
    $files[] = $controller->controller_name();
    $files[] = $controller->controller_name() . '/' . $controller->action_name();
    
    // Controller action dependent assets in subfolders
    foreach ( array( 'js', 'css' ) as $asset_type )
    {
    
      $asset_folder = 
        Framework::assets_path() . 
        $asset_type . '/' . 
        $controller->controller_name() . '/' . 
        $controller->action_name() . '/'
      ;
      
      try
      {
        $files_in_asset_subfolder = Tools::files_in_folder( $asset_folder );
      }
      catch ( Exception $e )
      {
        // Skip if folder doesn't exist
        continue;
      }
      
      foreach ( $files_in_asset_subfolder as $file_in_asset_subfolder )
      {
      
        $file_in_asset_subfolder_name = basename( $file_in_asset_subfolder );
        $file_in_asset_subfolder_name = str_replace( "." . $asset_type, "", $file_in_asset_subfolder_name ); // Remove file extension
      
        $files[] = 
          $controller->controller_name() . '/' . 
          $controller->action_name() . '/' . 
          $file_in_asset_subfolder_name
        ;
        
      }
      
    }
    
    // Enqueue assets
    $asset_types = array( 
      // type => corresponding function
      'js'  => 'wp_enqueue_script',
      'css' => 'wp_enqueue_style',
    );
    
    // Dependencies by asset type
    $dependencies = array(
      'js'  => array( 'jquery' ),
      'css' => array(),
    );
    
    foreach ( $asset_types as $asset_type => $enqueue_function )
    {
      foreach ( $files as $file )
      {
        
        // Check if asset exists and enqueue it
        $asset_relative_path            = $asset_type . '/' . $file . '.' . $asset_type;
        $asset_relative_filesystem_path = $asset_relative_path;
        $asset_filesystem_path          = Framework::assets_path() . $asset_relative_filesystem_path;
        $asset_url                      = Framework::assets_url()  . $asset_relative_path;
        
        $asset_name = 'export2pdf-' . str_replace( '/', '-', $file );
        
        if ( ! file_exists( $asset_filesystem_path ) )
          continue;
          
        $enqueue_function(
          $asset_name,
          $asset_url . '?' . @filemtime( $asset_filesystem_path ),
          $dependencies[ $asset_type ]
        );
        
      }
    }
    
  }

  /**
   * Folder with views
   */
  public function views_root()
  {
    return dirname( $this->controller_file ) . '/../views/';
  }
  
  /**
   * Get action name from the request variables,
   * and use it in render() method to execute a corresponding action
   */
  public function action_name()
  {
  
    if ( $this->action )
      return $this->action;
  
    $action = 'index';
    if ( isset( $_GET[ 'action' ] ) )
    {
      $action = $_GET[ 'action' ];
      // Remove unique prefix ID
      $action = str_replace( 'export2pdf_', '', $action );
    }
    
    $this->action = $action;
    
    return $this->action;
  }
  
  /**
   * Get controller "slug" from its PHP class name
   */
  public function controller_name()
  {
    $controller_class = get_class( $this );
    $controller_name = str_replace( 'Export2Pdf\\', '', $controller_class );
    $controller_name = str_replace( 'Controller', '', $controller_name );
    $controller_name = lcfirst( $controller_name );
    return $controller_name;
  }
  
  /**
   * Get URL for an action
   *
   * @param $action_name string Action name
   * @paral $url_params array Additional parameters for GET request
   * 
   * @retrun string URL for this action
   */
  public static function action_url( $action_name = 'index', $url_params = array() )
  {
    $url_params[ 'page' ]   = static::PAGE;
    $url_params[ 'action' ] = $action_name;
    return Framework::url( $url_params );
  }
  
  /**
   * Executes the controller
   */
  public function render()
  {
    $action_name = $this->action_name();
    $controller_name = $this->controller_name();
    
    try
    {
    
      // Call corresponding action
      if ( method_exists( $this, $action_name ) )
        call_user_func( array( $this, $action_name ) );
      
    }
    catch ( Exception $e )
    {
      $e->show();
      return;
    }
    
    // Make variables available
    $this->variables[ 'is_modal' ] = $this->is_modal;
    @extract( $this->variables );
    $controller = $this;
    
    // TODO: assign helper functions
    
    if ( $this->is_modal )
    {
      $this->add_asset( 'global/modal' );
      $this->add_assets();
      require $this->views_root() . 'ajax' . DIRECTORY_SEPARATOR . 'header.php';
    }
    
    // Render the template
    require $this->views_root() . $controller_name . DIRECTORY_SEPARATOR . $action_name . '.php';
    
    if ( $this->is_modal )
    {
      require $this->views_root() . 'ajax' . DIRECTORY_SEPARATOR . 'footer.php';
    }
    
  }
  
}
