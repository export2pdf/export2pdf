<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Model class for database interaction, more or less like Ruby on Rails
 */
 
class Model
{
  public $id;
  
  const TABLE = "";
  
  public static $instances = array(); // Simple caching for models
  public static $cache = array();     // Simple caching for model collections
  
  /**
   * Get a model by it's ID
   * Used to be overwritten in child classes
   */
  public static function get( $row )
  {
  
    $class_name = get_called_class();
    $table = static::TABLE;
    
    // If cache for this $table is empty, then make it an array
    if ( ! isset( self::$instances[ $table ] ) )
      self::$instances[ $table ] = array();
  
    // Get model ID and try to return the model from cache
    if ( is_object( $row ) )
    {
      $model_id = $row->id;
    }
    else
    {
      $model_id = $row;
    }
  
    if ( isset( self::$instances[ $table ][ $model_id ] ) )
      return self::$instances[ $table ][ $model_id ];
  
    // Create model, and assign proper class
    
    $columns = static::columns();
    if ( in_array( 'type', $columns ) )
    {
    
      if ( ! is_object( $row ) )
      {
    
        // Get data from the table
        $row = Db::selectOne( 
          static::TABLE, 
          array( 
            'id' => $model_id,
          )
        );
      
      }
      
      $type = $row->type;
      if ( $type and class_exists( "Export2Pdf\\$type" ) )
        $class_name = "Export2Pdf\\$type";
      
    }
    
    $model = new $class_name( $row );
    
    // Save to cache
    self::$instances[ $table ][ $model_id ] = $model;
    
    return $model;
    
  }
  
  /**
   * Get model ID
   *
   * @return int|null Model ID
   */
  public function id()
  {
    return $this->id;
  }
  
  /**
   * Check if it exists in the database
   *
   * @return TRUE if exists, FALSE if it doesn't exist.
   */
  public function exists()
  {
    return $this->id();
  }
  
  /**
   * Count all models in the database
   *
   * @param $where array|null WHERE coundition
   *
   * @return int Count of models
   */
  public static function count( $where = null )
  {
    return Db::count( static::TABLE, $where );
  }
  
  /**
   * Get all models
   *
   * @param $where array|null WHERE coundition
   *
   * @return array All models matching $where condition
   */
  public static function all( $where = null )
  {
  
  
    $table     = static::TABLE;
    $cache_key = ( $where ? json_encode( $where ) : 'all' );
  
    // If cache for this $table is empty, then make it an array
    if ( ! isset( self::$cache[ $table ] ) )
      self::$cache[ $table ] = array();
    
    if ( isset( self::$cache[ $table ][ $cache_key ] ) )
      return self::$cache[ $table ][ $cache_key ];
  
    // Get the list of columns
    $columns = self::columns();
  
    $order = NULL;
    if ( in_array( 'created_at', $columns ) )
      $order = "created_at DESC";
  
    $rows = Db::select( $table, $where, $order );
    
    $models = array();
    foreach ( $rows as $row )
      $models[] = static::get( $row );
      
    // Save this query into cache
    self::$cache[ $table ][ $cache_key ] = $models;
      
    return $models;
    
  }
 
  /**
   * Get the list of columns for this model
   */
  public static function columns()
  {
    return Db::columns( static::TABLE );
  }
 
  /**
   * Save data to MySQL table
   */
  public function save()
  {
    // Get the list of columns
    $columns = self::columns();
    $table = static::TABLE;
    
    // Create an array with data that will be stored
    $model_data = array();
    foreach ( $columns as $column )
      if ( isset( $this->{ $column } ) )
        $model_data[ $column ] = $this->{ $column };
    
    if ( in_array( 'updated_at', $columns ) )
      $model_data[ 'updated_at' ] = date( 'Y-m-d H:i:s' );
    
    // If one of the arguments is an array, then serialize it
    foreach ( $model_data as $column => $value )
    {
      if ( is_array( $value ) or is_object( $value ) )
        $model_data[ $column ] = serialize( $value );
    }
    
    // Update or create an entry
    if ( ! $this->id() )
    {
    
      if ( in_array( 'created_at', $columns ) )
        $model_data[ 'created_at' ] = date( 'Y-m-d H:i:s' );
    
      // ID is not set. Create a new entry
      $this->id = Db::insert( $table, $model_data );
      
    }
    else
    {
    
      // ID is set. Update existing data
      Db::update(
        $table,
        $model_data,
        array(
          'id' => $this->id(),
        )
      );
      
    }
    
    // Reset cache
    self::$cache[ $table ] = array();
    self::$instances[ $table ][ $this->id() ] = $this;
    
  }
  
  /**
   * Delete from the database
   */
  public function destroy()
  {
    Db::delete( 
      static::TABLE,
      array(
        'id' => $this->id(),
      )
    );
  }
  
  /**
   * Get model data based on its $id
   *
   * @param $id int|null Model ID.
   */
  public function __construct( $id = null )
  {
    if ( ! $id )
      return;
    
    if ( is_object( $id ) )
    {
    
      // $id is a class that already contains all fields and values
      $row = $id;
      
    }
    else
    {
    
      // Get data from the table
      $row = Db::selectOne( 
        static::TABLE, 
        array( 
          'id' => $id,
        )
      );
      
    }
    
    if ( $row )
      foreach ( $row as $column => $value )
        $this->{ $column } = $value;
      
  }
}
