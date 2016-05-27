<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Migration class
 *
 * Used for creating new folders, adding or changing MySQL tables, etc.
 */
 
class Migration
{
  public $file;
  public $id;
  
  /**
   * Create a migration
   *
   * @param $file string Path to migration file
   */
  public function __construct( $file )
  {
    $this->file = $file;
    $this->id   = str_replace( '.php', '', basename( $file ) );
  }
  
  /**
   * Checks if migration was previously performed
   *
   * @return bool TRUE if performed, FALSE if not.
   */
  public function migrated()
  {
    return Settings::get( 'migration_done_' . $this->id );
  }
  
  /**
   * Get the list of all migrations
   */
  public static function all()
  {
  
    $migrations = array();
    
    $migration_folder = Framework::path() . 'migrations';
    $migration_files = Tools::files_in_folder( $migration_folder );
    foreach ( $migration_files as $migration_file )
      if ( preg_match( '/\.php$/', $migration_file ) )
        $migrations[] = new Migration( $migration_file );
    
    return $migrations;
  }
  
  /**
   * Perform all missing migrations
   */
  public static function migrate()
  {
    $migrations = self::all();
    foreach ( $migrations as $migration )
      $migration->perform();
  }
  
  /**
   * Perform all migration actions
   */
  public function perform()
  {
  
    if ( $this->migrated() )
      return;
      
    require $this->file;
  
    Settings::set( 'migration_done_' . $this->id, '1' );
    
  }
  
  /**
   * Execute SQL queries
   */
  public function executeSql()
  {
    
    $sql_folder = dirname( $this->file ) . DIRECTORY_SEPARATOR . $this->id;
    
    try
    {
      // Get the list of SQL files
      $sql_files = Tools::files_in_folder( $sql_folder );
    }
    catch ( Exception $e )
    {
      // Folder with SQL files doesn't exist for this migration
      return;
    }
    
    foreach ( $sql_files as $sql_file )
    {
      $sql_command = file_get_contents( $sql_file );
      try
      {
      
        // Replace variables in SQL files
        $sql_command = str_replace( '{prefix}', Db::$prefix, $sql_command );
        $sql_command = str_replace( '{charset}', Db::CHARSET, $sql_command );
        
        // Perform SQL query
        Db::query( $sql_command );
        
      }
      catch (Exception $e)
      {
        throw new Exception( 
          "There was an error while processing MySQL file " . 
          basename( $sql_file ) .
          " : " .
          $e->getMessage()
        );
      }
    }
  
  }
}
