<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Format as phone
 */
 
class Format_Phone extends Format
{
  
  public $name = 'Phone';
  
  public function default_options()
  {
    return array(
      'phone_format' => '###-###-###d',
    );
  }
  
  public function process( $value, $options = array() )
  {
    extract( $options );
    $value = self::_format( $phone_format, $value );
    return $value;
  }
  
  public function examples()
  {
    
    return array(
      '###-###-####',
      '###-####',
      '(###) ###-####',
      '+1-###-###-###',
      '1-###-###-###',
      '001-###-###-###',
    );
  
  }
  
  public static function _format( $phone_format, $number )
  {
   
    $number = preg_replace( '/[^0-9]+/', '', $number ); // Remove all non-numeric characters
    $number = str_split( $number . '' );                // Transform it into an array
    $number = array_reverse( $number );                 // Reverse it, because we will replace 'd' starting from the end of the string
        
    $phone_format = str_split( $phone_format . '' );
    $phone_format = array_reverse( $phone_format );
    
    $number_iterator = 0;
    for ( $phone_iterator = 0; $phone_iterator < count( $phone_format ); $phone_iterator++ )
    {
      
      $phone_digit = $phone_format[ $phone_iterator ];
      
      // This is not a 'd', so we don't change anything
      if ( strtolower( $phone_digit ) != '#' )
        continue;
      
      // Replace each 'd' symbol with a number
      if ( isset( $number[ $number_iterator ] ) )
      {
        $digit = $number[ $number_iterator ];
        $phone_format[ $phone_iterator ] = $digit;
        $number_iterator++;
      }
      
    }
    
    // Transform it back into a string
    $phone_format = array_reverse( $phone_format );
    $phone_format = implode( '', $phone_format );
    
    // And remove all remaining d's
    $phone_format = preg_replace( '/\#/', '', $phone_format );
    
    return $phone_format;
    
  }
  
  public function show_options( $map = NULL )
  {
  
    ob_start();
    
    ?>
    
      <tr class="export2pdf-additional-options">
        
        <th scope="row">
          <label for="map_formating">
            Phone Number Format
          </label>
        </th>
        
        <td>
          
          <p class="description">
            Click on one of the formats below, <br />
            or type number format in the text field below.
          </p>
             
          <ul class="export2pdf-formatting-options-list list">
             
            <?php
            
              $current_phone = 'test 0 541 754-3010 test';
              foreach ( $this->examples() as $example_code )
              {
                $example_options = array(
                  'phone_format' => $example_code,
                );
                echo '
                  <li data-options="' . esc_attr( json_encode( $example_options ) ) . '"> 
                    <b>' . self::_format( $example_code, $current_phone ) . '</b> 
                  </li>' . "\n"
                ;
              }
            
            ?>
            
          </ul>
          
          <input type="text" class="regular-text" name="options[phone_format]" value="<?php echo esc_attr( $map->option( 'phone_format' ) ); ?>" />
          
          <p class="description">
            Each <b>#</b> will be replaced by a digit from the field value.
          </p>
          
        </td>
        
      </tr>
    
    <?php
    
    return ob_get_clean();
    
  }
  
}


