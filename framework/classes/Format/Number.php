<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Format as number or price
 */
 
class Format_Number extends Format
{
  
  public $name  = 'Number';
  public $group = 'Numbers';
  
  public function default_options()
  {
    return array(
      'number_format_decimals'            => '2',
      'number_format_decimal_separator'   => '.',
      'number_format_thousands_separator' => ',',
      'number_format_prefix'              => '',
      'number_format_suffix'              => '',
      'number_format_approximation'       => '',
    );
  }
  
  public function process( $value, $options = array() )
  {
    extract( $options );
    $value = self::_format( $options, $value );
    return $value;
  }
  
  public static function _format( $options, $number )
  {
    extract( $options );
    
    $number = floatval( $number );
    
    if ( isset( $number_format_approximation ) )
    {
      if ( $number_format_approximation == 'round' )
        $number = round( $number );
      if ( $number_format_approximation == 'floor' )
        $number = floor( $number );
      if ( $number_format_approximation == 'ceil' )
        $number = ceil( $number );
    }
    
    return 
      $number_format_prefix . 
      number_format( 
        $number, 
        absint( $number_format_decimals ),
        $number_format_decimal_separator,
        $number_format_thousands_separator
      ) .
      $number_format_suffix
    ;
  }
  
  public function examples()
  {
  
    $examples = array();
    
    // 1000
    $examples[] = array(
      'number_format_decimals'            => '0',
      'number_format_decimal_separator'   => '',
      'number_format_thousands_separator' => '',
      'number_format_prefix'              => '',
      'number_format_suffix'              => '',
    );
    
    // 1,000
    $examples[] = array(
      'number_format_decimals'            => '0',
      'number_format_decimal_separator'   => '',
      'number_format_thousands_separator' => ',',
      'number_format_prefix'              => '',
      'number_format_suffix'              => '',
    );
    
    // 1.000
    $examples[] = array(
      'number_format_decimals'            => '0',
      'number_format_decimal_separator'   => '',
      'number_format_thousands_separator' => '.',
      'number_format_prefix'              => '',
      'number_format_suffix'              => '',
    );
    
    // 1 000
    $examples[] = array(
      'number_format_decimals'            => '0',
      'number_format_decimal_separator'   => '',
      'number_format_thousands_separator' => ' ',
      'number_format_prefix'              => '',
      'number_format_suffix'              => '',
    );

    // 1000,00
    $examples[] = array(
      'number_format_decimals'            => '2',
      'number_format_decimal_separator'   => ',',
      'number_format_thousands_separator' => '',
      'number_format_prefix'              => '',
      'number_format_suffix'              => '',
    );
    
    // 1 000,00
    $examples[] = array(
      'number_format_decimals'            => '2',
      'number_format_decimal_separator'   => ',',
      'number_format_thousands_separator' => ' ',
      'number_format_prefix'              => '',
      'number_format_suffix'              => '',
    );
    
    // 1 000.00
    $examples[] = array(
      'number_format_decimals'            => '2',
      'number_format_decimal_separator'   => '.',
      'number_format_thousands_separator' => ' ',
      'number_format_prefix'              => '',
      'number_format_suffix'              => '',
    );
    
    // 1,000.00
    $examples[] = array(
      'number_format_decimals'            => '2',
      'number_format_decimal_separator'   => '.',
      'number_format_thousands_separator' => ',',
      'number_format_prefix'              => '',
      'number_format_suffix'              => '',
    );
    
    // 1.000,00
    $examples[] = array(
      'number_format_decimals'            => '2',
      'number_format_decimal_separator'   => ',',
      'number_format_thousands_separator' => '.',
      'number_format_prefix'              => '',
      'number_format_suffix'              => '',
    );
    
    // 1. 000, 00
    $examples[] = array(
      'number_format_decimals'            => '2',
      'number_format_decimal_separator'   => ', ',
      'number_format_thousands_separator' => '. ',
      'number_format_prefix'              => '',
      'number_format_suffix'              => '',
    );
    
    return $examples;
  
  }
  
  public function show_options( $map = NULL )
  {
  
    $approximation_options = array(
      'round' => 'Round',
      'ceil'  => 'Ceil',
      'floor' => 'Floor',
    );
  
    ob_start();
    
    ?>
    
    <tr class="export2pdf-additional-options">
    
      <th scope="row">
        Number Format
      </th>
      
      <td>
      
          <p class="description">
            Click on one of the formats below, <br />
            or change number format in the text fields below.
          </p>
             
          <ul class="export2pdf-formatting-options-list list">
             
            <?php
            
              $example_number = 1000.00;
              foreach ( $this->examples() as $example_options )
              {
                echo '
                  <li data-options="' . esc_attr( json_encode( $example_options ) ) . '"> 
                    <b>' . self::_format( $example_options, $example_number ) . '</b> 
                  </li>' . "\n"
                ;
              }
            
            ?>
            
          </ul>
          
        </td>
        
      </tr>
    
      <tr class="export2pdf-additional-options">
        
        <th scope="row">
          <label for="map_formating">
            Number of decimal points
          </label>
        </th>
        
        <td>
        
          <input 
            type="number" 
            placeholder="0"
            min="0"
            step="1"
            max="10"
            class="regular-text" 
            name="options[number_format_decimals]" 
            value="<?php echo esc_attr( $map->option( 'number_format_decimals' ) ); ?>" 
          />
          
          <p class="description">
            Sets the number of decimal points.
          </p>
          
        </td>
        
      </tr>
    
      <tr class="export2pdf-additional-options">
        
        <th scope="row">
          <label for="map_formating">
            Separator for the decimal point
          </label>
        </th>
        
        <td>
        
          <input 
            type="text" 
            placeholder="(empty)"
            class="regular-text" 
            name="options[number_format_decimal_separator]" 
            value="<?php echo esc_attr( $map->option( 'number_format_decimal_separator' ) ); ?>" 
          />
          
          <p class="description">
            Sets the separator for the decimal point.<br />
            This is usually a comma or a dot.<br />
            Leave empty if the number of decimal points is zero.
          </p>
          
        </td>
        
      </tr>
      
      <tr class="export2pdf-additional-options">
        
        <th scope="row">
          <label for="map_formating">
            Thousands separator
          </label>
        </th>
        
        <td>
        
          <input 
            type="text" 
            placeholder="(empty)"
            class="regular-text" 
            name="options[number_format_thousands_separator]" 
            value="<?php echo esc_attr( $map->option( 'number_format_thousands_separator' ) ); ?>" 
          />
          
          <p class="description">
            Sets the thousands separator.<br />
            This is usually a comma or a dot.<br />
            Leave empty if you don't need any thousands separator.
          </p>
          
        </td>
        
      </tr>
      
      <?php if ( $this instanceof FormatPrice ): ?>
        <!-- Custom price options -->
      
        <tr class="export2pdf-additional-options">
          
          <th scope="row">
            <label for="map_formating">
              Prefix
            </label>
          </th>
          
          <td>
          
            <input 
              type="text" 
              placeholder="(empty)"
              class="regular-text" 
              name="options[number_format_prefix]" 
              value="<?php echo esc_attr( $map->option( 'number_format_prefix' ) ); ?>" 
            />
            
            <p class="description">
              Prepends some text to the number.<br />
              For example, it can be a dollar symbol followed by a space character.
            </p>
            
          </td>
          
        </tr>
        
        <tr class="export2pdf-additional-options">
          
          <th scope="row">
            <label for="map_formating">
              Suffix
            </label>
          </th>
          
          <td>
          
            <input 
              type="text" 
              placeholder="(empty)"
              class="regular-text" 
              name="options[number_format_suffix]" 
              value="<?php echo esc_attr( $map->option( 'number_format_suffix' ) ); ?>" 
            />
            
            <p class="description">
              Appends some text to the number.<br />
              For example, it can be a space character followed by a euro symbol.
            </p>
            
          </td>
          
        </tr>
        
      <!-- End custom price options -->
      <?php else: ?>
      
        <input type="hidden" name="options[number_format_prefix]" value="" />
        <input type="hidden" name="options[number_format_suffix]" value="" />
      
      <?php endif; ?>
    
      <tr class="export2pdf-additional-options">
        
        <th scope="row">
          <label for="map_formating">
            Approximation
          </label>
        </th>
        
        <td>
        
          <select type="regular-text" name="options[number_format_approximation]">
          
            <option value="">(default)</option>
            
            <?php foreach ( $approximation_options as $approximation_option_id => $approximation_option_title ): ?>
            
              <option 
                value="<?php echo esc_attr( $approximation_option_id ); ?>"
                <?php if ( $map->option( 'number_format_approximation' ) == $approximation_option_id ) echo ' selected="selected"'; ?>
              >
                <?php echo esc_html( $approximation_option_title ); ?>
              </option>
            
            <?php endforeach; ?>
          
          </select>
          
          <p class="description">
            If you use numbers with floating points, you can round your numbers.<br />
            Round: 0.5 will be rounded to 1.0; 0.4 to 0.0<br />
            Ceil: 0.5 will be rounded to 1.0; 0.4 to 1.0<br />
            Floor: 0.5 will be rounded to 0.0; 0.4 to 0.0
          </p>
          
        </td>
        
      </tr>
    
    <?php
        
    return ob_get_clean();
    
  }
  
}


