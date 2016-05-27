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
            Select a color
          </label>
        </th>
        
        <td>

          <input 
            type="text" 
            name="option_value" 
            id="option_value"
            class="regular-text"
            value="<?php echo esc_attr( $option_value ); ?>" 
          />
          
        </td>
        
      </tr>
      
      <tr>
      
        <th scope="row"></th>
        
        <td>
        
          <button class="button button-primary" type="submit">
            Set font color            
          </button>
        
        </td>
      
      </tr>
      
    </tbody>
    
  </table>
  
</form>
