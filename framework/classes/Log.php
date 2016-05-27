<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Stores log information, used only in debug mode
 */
 
class Log extends Model
{

  const TABLE = "logs";

  const MAXIMUM_UNCOMMITTED_LOGS = 100;

  public $name;
  public $timestamp;
  public $request_id;
  public $group;
  public $data;
  
  public static $first_known_timestamp = 0;
  public static $uncommitted_logs = array();
  public static $committing = false;
  
  /**
   * Create a log entry
   *
   * @param $name string Log entry name
   * @param $object mixed Additional information
   */
  public static function log( $type, $name = NULL, $additional = NULL )
  {
    
    // Do not allow to run this method in loop
    if ( self::$committing ) return;
    self::$committing = true;
    
    // Do not log anything if debug mode is not enabled
    if ( ! Debug::enabled() )
      return;
  
    if ( ( $name instanceof Exception ) or ( $name instanceof \Exception ) )
    { 
      // Format exception
      $exception  = $name;
      
      $additional = array(
        'trace' => $exception->getTraceAsString(),
      );
      
      $name       = $exception->getMessage();
    }
    
    // Create a long entry
    $entry = new Log();
    $entry->name = $name;
    $entry->group = $type;
    
    // Store all additional data
    if ( $additional )
      $entry->data = $additional;
    
    // Calculate how much time elapsed since script started
    if ( ! self::$first_known_timestamp )
    {
    
      // REQUEST_TIME_FLOAT should normally be available on PHP >= 5.4
      if ( isset( $_SERVER[ "REQUEST_TIME_FLOAT" ] ) )
        self::$first_known_timestamp = $_SERVER[ "REQUEST_TIME_FLOAT" ];
      else
        self::$first_known_timestamp = microtime( true );

    }        
      
    $entry->timestamp = round( ( microtime( true ) - self::$first_known_timestamp ) * 1000 ); // Milliseconds
    $entry->request_id = self::$first_known_timestamp;
 
    
    self::$uncommitted_logs[] = $entry;
    self::$committing = false;
  
    /*
    echo( count( self::$uncommitted_logs ) . "\n" ); Tools::flush();
  
    if ( count( self::$uncommitted_logs ) > 100 )
    {
      $e = new Exception("boo");
      $e->show();
    }
    */
  
    // If there are too many logs, let's commit them
    if ( count( self::$uncommitted_logs ) > self::MAXIMUM_UNCOMMITTED_LOGS )
      self::commit();
  
  }
  
  /**
   * Save all un-saved log entries to the database
   * (to avoid delays of MySQL requests
   */
  public static function commit()
  {
  
    // Do not log anything if debug mode is not enabled
    if ( ! Debug::enabled() )
      return;
  
    self::$committing = true;
  
    try
    {
      foreach ( self::$uncommitted_logs as $entry )
        $entry->save();
    }
    catch ( Exception $e )
    {
      // Somewhy log can't be saved
      // wp_die( $e->getMessage() );
    }
    
    // Delete old log entries
    $query = sprintf(
      "DELETE FROM %s%s WHERE created_at < '%s'",
      Db::$prefix,
      self::TABLE,
      date( 'Y-m-d H:i:s', time() - 24 * 60 * 60 * 31 ) // 1 month
    );
    Db::query( $query );
    
    self::$committing = false;
    
    // Clear cache
    self::$uncommitted_logs = array();
  
  }
  
}
