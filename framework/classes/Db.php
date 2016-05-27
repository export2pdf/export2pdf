<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

// TODO: write function descriptions

/**
 * Database function
 */

class Db
{

  const CHARSET = DB_CHARSET;

  public static $prefix = 'export2pdf_';
  
  public static $columns = array();
  
  /**
   * Execute a raw MySQL query
   *
   * @param $query string MySQL query
   * @return array Array of stdClass objects representing MySQL rows
   */
  public static function query( $query )
  {
    
    export2pdf_log( 'db', $query );
  
    global $wpdb;
    if ( FALSE === $wpdb->query( $query ) )
    {
      ob_start();
      $wpdb->print_error();
      $error = ob_get_clean();
      throw new Exception( "Error in query $query : " . strip_tags( $error ) );
    }
    
  }
  
  /**
   * Get the list of columns for this model
   *
   * @return array List of column name
   */
  public static function columns( $table )
  {
  
    if ( isset( self::$columns[ $table ] ) )
      return self::$columns[ $table ];
  
    $columns = array();
    $table = static::$prefix . $table;
    $rows = static::_select( "SHOW COLUMNS FROM $table" );
    foreach ( $rows as $row )
      $columns[] = $row->Field;
      
    self::$columns[ $table ] = $columns;
      
    return $columns;
    
  }
  
  /**
   * Escapes a string (basically, adds slashes)
   *
   * @param $value string Some text
   * @return mixed Escaped value used in MySQL
   */
  public static function escape( $value )
  {
    global $wpdb;
    return $wpdb->prepare( '%s', $value );
  }
  
  /**
   * Formats a hash into MySQL WHERE statement
   *
   * @param $where mixed Hash or empty
   */
  public static function format_where( $where = false )
  {
    if (
        ! $where 
        or ! is_array( $where )
        or ! count( $where )
      )
      return "1";
    
    $conditions = array();
    foreach ( $where as $column => $value )
    {
      $value = static::escape( $value );
      $conditions[] = "`$column` = $value";
    }
    
    return implode( ' AND ', $conditions );
  }
  
  /**
   * Count rows
   *
   * @param $where array|null WHERE MySQL condition
   */
  public static function count( $table, $where = null )
  {
  
    $where = self::format_where( $where );
    $table = static::$prefix . $table;
    $query = "SELECT COUNT(*) FROM $table WHERE $where";
    
    export2pdf_log( 'db', $query );
    
    global $wpdb;
    return $wpdb->get_var( $query );
    
  }
  
  /**
   * Check if a table exists
   *
   * @param $table string Table name (without export2pdf_ prefix)
   *
   * @return bool TRUE if exists, FALSE if not.
   */
  public static function table_exists( $table )
  {
    
    $table = static::$prefix . $table;
    $result = static::_select( "SHOW TABLES LIKE '$table'" );
    if ( $result and is_array( $result ) )  
      return ( count( $result ) > 0 );
    return false;
    
  }
  
  /**
   * Select ony row from the database
   *
   * @param $table string Table name
   * @param $where array (optional) Condition on which data should be selected
   */
  public static function selectOne( $table, $where=false )
  {
  
    $rows = static::select( $table, $where );
    if ( count( $rows ) )
      return $rows[ 0 ];
      
    return false;
    
  }
  
  /**
   * Executed on MySQL connection
   */
  public static function connect()
  { 
    // Update prefix to reference tables quicker
    global $wpdb;
    self::$prefix = $wpdb->prefix . self::$prefix;
  }
  
  /**
   * Select rows from the database
   *
   * @param $table string Table name
   * @param $where array (optional) Condition on which data should be selected
   * @param $order array (optional) Sorting order of the rows
   */
  public static function select( $table, $where=false, $order=false )
  {
  
    global $wpdb;
    
    $table = self::$prefix . $table;
    $where = self::format_where( $where );
    if ( $order )
      $order = "ORDER BY $order";
      
    $query = "SELECT * FROM $table WHERE $where $order";
      
    export2pdf_log( 'db', $query );
      
    return $wpdb->get_results( $query );
    
  }
  
  /**
   * Select rows from the database.
   *
   * @param $query string Raw MySQL query
   */
  public static function _select( $query )
  {
  
    export2pdf_log( 'db', $query );
    
    global $wpdb;
    return $wpdb->get_results( $query );
    
  }
  
  /**
   * Update a row in the database
   *
   * @param $table string Table name
   * @param $what array Data that needs to be inserted
   * @param $where array (optional) Condition on which data should be updated
   */
  public static function update( $table, $what, $where=false )
  {
  
    global $wpdb;
    $table = self::$prefix . $table;

    export2pdf_log( 'db', "UPDATE $table", array( $what, $where ) );
    
    return $wpdb->update( $table, $what, $where );
    
  }
  
  /**
   * Insert a row in the database
   *
   * @param $table string Table name
   * @param $what array Data that needs to be inserted
   */
  public static function insert( $table, $what )
  {
  
    global $wpdb;
    $table = self::$prefix . $table;
    
    export2pdf_log( 'db', "INSERT $table", $what );
    
    $wpdb->insert( $table, $what );

    // Warn that the query wasn't successful
    if ( ! $wpdb->insert_id )
      throw new Exception( 'Could not save ' . print_r( $what, true ) . ' to table ' . $table );
      
    return $wpdb->insert_id;
    
  }
  
  /**
   * Delete a row from the database
   *
   * @param $table string Table name
   * @param $what array Data that needs to be deleted
   */
  public static function delete( $table, $where )
  {
  
    global $wpdb;
    $table = self::$prefix . $table;
    
    export2pdf_log( 'db', "DELETE $table", $where );
    
    return $wpdb->delete( $table, $where );
    
  }
  
}
