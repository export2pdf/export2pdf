<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Format as date (dd.mm.yyyy, yyyy/dd/mm, etc.)
 */
 
class Format_Date extends Format
{
  
  public $name  = 'Date';
  public $group = 'Time';
  
  public function default_options()
  {
    return array(
      'date_format' => get_option( 'date_format' ),
    );
  }
  
  public function process( $value, $options = array() )
  {
    extract( $options );
    $timestamp   = strtotime( $value );
    if ( $timestamp )
      $value = self::_format( $date_format, $timestamp );
    return $value;
  }
  
  public static function _format( $date_format, $timestamp )
  {
    return date_i18n( $date_format, $timestamp );
  }
  
  public function examples()
  {
    return array(
      'F j, Y',
      'F, Y',
      'l, F jS, Y',
      'Y/m/d',
      'd.m.Y',
      'm/d/y',
      'm/d/Y',
      'd-m-Y',
      'd-m-y',
    );
  }
  
  public function show_options( $map = NULL )
  {
  
    ob_start();
    
    ?>
    
      <tr class="export2pdf-additional-options">
        
        <th scope="row">
          <label for="map_formating">
            Date Format
          </label>
        </th>
        
        <td>
          
          <p class="description">
            Click on one of the formats below, <br />
            or type date format in the text field below.
          </p>
             
          <ul class="export2pdf-formatting-options-list list">
             
            <?php
            
              $current_timestamp = strtotime( date('Y-m-d') . ' 01:23:45' );
              foreach ( $this->examples() as $example_code )
              {
                $example_options = array(
                  'date_format' => $example_code,
                );
                echo '
                  <li data-options="' . esc_attr( json_encode( $example_options ) ) . '"> 
                    <b>' . self::_format( $example_code, $current_timestamp ) . '</b> 
                  </li>' . "\n"
                ;
              }
            
            ?>
            
          </ul>
          
          <input type="text" class="regular-text" name="options[date_format]" value="<?php echo esc_attr( $map->option( 'date_format' ) ); ?>" />
          
          <p class="description">
            You can find the list of all formatting options <a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">here</a>.
          </p>
          
        </td>
        
      </tr>
    
    <?php
    
    return ob_get_clean();
    
  }
  
}


