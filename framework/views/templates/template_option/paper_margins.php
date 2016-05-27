<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();

  // Format: "N,N,N,N" → to array(0, N, N, N, N)
  $margins = explode( ",", $option_value ); 
  array_unshift( $margins, 0 );
  
  // Measurement unit (mm or inch)
  $unit = \Export2Pdf\Settings::get( "measurement_unit" );
  
  // Remove mm or in at the end
  foreach ( $margins as $margin_index => $margin )
  {  
    
    // Margin is in mm, but the unit system is inch
    if ( 
          preg_match( '/mm$/', $margin ) 
      and ( $unit == 'in' )
    )
    {
    
      $margin = floatval( $margin ) / \Export2Pdf\TemplateHtml::MM_PER_INCH;
      $margin = round( $margin, 1 );
            
    }
    
    // Margin is in in, but the unit system is mm
    elseif ( 
          preg_match( '/in$/', $margin ) 
      and ( $unit == 'mm' )
    )
    {
    
      $margin = floatval( $margin ) * \Export2Pdf\TemplateHtml::MM_PER_INCH;
      $margin = round( $margin );
            
    }
  
    // Just remove mm and in at the end
    else
    {
      $margin = preg_replace( '/[^\d]+/', '', $margin );
    }
    
    $margins[ $margin_index ] = $margin;
  
  }
  
  // For mm, the step would be 1; for inches it would be 0.1
  if ( $unit == 'mm' )
    $margin_step = 1;
  else
    $margin_step = 0.1;
  
?>

<form>

  <input 
    type="hidden" 
    name="option_value" 
    id="option_value"
    value="<?php echo esc_attr( $option_value ); ?>" 
  />
  
  <input 
    type="hidden" 
    name="measurement_unit" 
    id="measurement_unit"
    value="<?php echo esc_attr( $unit ); ?>" 
  />

  <table class="form-table">
  
    <tbody>
    
      <!-- Start margin options -->
      
      <tr>
        
        <th scope="row">
          <label>
            Top (<?php echo $unit; ?>):
          </label>
        </th>
        
        <td>

          <input 
            type="number"
            step="<?php echo $margin_step; ?>"
            min="0"
            max="200" 
            required="required"
            name="margin1" 
            class="regular-text"
            value="<?php echo esc_attr( $margins[ 1 ] ); ?>" 
          />
          
        </td>
        
      </tr>
      
      <!-- End margin options -->
      
      <!-- Start margin options -->
      
      <tr>
        
        <th scope="row">
          <label>
            Bottom (<?php echo $unit; ?>):
          </label>
        </th>
        
        <td>

          <input 
            type="number"
            step="<?php echo $margin_step; ?>"
            min="0"
            max="200" 
            name="margin3" 
            required="required"
            class="regular-text"
            value="<?php echo esc_attr( $margins[ 3 ] ); ?>" 
          />
          
        </td>
        
      </tr>
      
      <!-- End margin options -->
      
      <!-- Start margin options -->
      
      <tr>
        
        <th scope="row">
          <label>
            Left (<?php echo $unit; ?>):
          </label>
        </th>
        
        <td>

          <input 
            type="number"
            step="<?php echo $margin_step; ?>"
            min="0"
            max="200" 
            required="required"
            name="margin4" 
            class="regular-text"
            value="<?php echo esc_attr( $margins[ 4 ] ); ?>" 
          />
          
        </td>
        
      </tr>
      
      <!-- End margin options -->
      
      <!-- Start margin options -->
      
      <tr>
        
        <th scope="row">
          <label>
            Right (<?php echo $unit; ?>):
          </label>
        </th>
        
        <td>

          <input 
            type="number"
            step="<?php echo $margin_step; ?>"
            min="0"
            max="200" 
            required="required"
            name="margin2" 
            class="regular-text"
            value="<?php echo esc_attr( $margins[ 2 ] ); ?>" 
          />
          
        </td>
        
      </tr>
      
      <!-- End margin options -->
      
      <tr>
      
        <th scope="row"></th>
        
        <td>
        
          <button class="button button-primary" type="submit">
            Set margins           
          </button>
        
        </td>
      
      </tr>
      
    </tbody>
    
  </table>
  
</form>
