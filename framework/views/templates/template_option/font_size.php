<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<form>

  <table class="form-table">
  
    <tbody>
    
      <tr>
        
        <th scope="row">
          <label for="option_value">
            Select a font size in pixels:
          </label>
        </th>
        
        <td>

          <input 
            type="number"
            step="1"
            min="10"
            max="40" 
            name="option_value" 
            id="option_value"
            class="regular-text"
            value="<?php echo esc_attr( $option_value ); ?>" 
          />
          
          <p class="description">
            Recommended values: between 12 and 16.
          </p>
          
        </td>
        
      </tr>
      
      <tr>
      
        <th scope="row"></th>
        
        <td>
        
          <button class="button button-primary" type="submit">
            Set font size            
          </button>
        
        </td>
      
      </tr>
      
    </tbody>
    
  </table>
  
</form>
