<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<div class="wrap export2pdf">

  <h1>
    <?php _e( 'Settings', 'export2pdf' ); ?>
  </h1>

  <form method="POST">

    <?php if ( \Export2Pdf\Tools::is_post() ): ?>
    
      <div class="updated inline">
        <p>
          <?php _e( 'Settings have been saved.', 'export2pdf' ); ?>
        </p>
      </div>
    
    <?php endif; ?>

    <table class="form-table">
      <tbody>
          
        <!-- Measurements system options -->

        <tr>
          
          <th scope="row">  
            <label>
              <?php _e( 'Measurement units', 'export2pdf' ); ?>
            </label>
          </th>
          
          <td>
          
            <fieldset>
              
              <?php 
              
                $options = array(
                  'in' => __( 'Imperial (inches)', 'export2pdf' ),
                  'mm' => __( 'Metric (millimeters)', 'export2pdf' ),
                );
                
                $current_value = \Export2Pdf\Settings::get( 'measurement_unit' );
              
                foreach ( $options as $value => $title ): 
              ?>
              
                <label>
                  <input 
                    type="radio" name="settings[measurement_unit]" 
                    value="<?php echo $value; ?>" 
                    <?php if ( $current_value == $value ) echo ' checked="checked"'; ?> 
                  />
                  <?php echo $title; ?>
                </label>
                
                <br />
              
              <?php endforeach;?>
            
            </fieldset>
            
            <p class="description">
              <?php _e( 'Useful when changing PDF page margins.', 'export2pdf' ); ?>
            </p>
          
        </tr>
        
        <!-- End of measurements system -->
        
        <!-- Download window -->

        <tr>
          
          <th scope="row">  
            <label>
              <?php _e( 'Download window', 'export2pdf' ); ?>
            </label>
          </th>
          
          <td>
          
            <fieldset>
              
              <?php 
              
                $options = array(
                  '1' => __( "Show a progress bar while exporting to PDF", 'export2pdf' ),
                  '0' => __( "Don't show a progress bar, and download PDF without redirections", 'export2pdf' ),
                );
                
                $current_value = \Export2Pdf\Settings::get( 'download_show_progress' );
              
                foreach ( $options as $value => $title ): 
              ?>
              
                <label>
                  <input 
                    type="radio" name="settings[download_show_progress]" 
                    value="<?php echo $value; ?>" 
                    <?php if ( $current_value == $value ) echo ' checked="checked"'; ?> 
                  />
                  <?php echo $title; ?>
                </label>
                
                <br />
              
              <?php endforeach;?>
            
            </fieldset>
            
            <p class="description">
              <?php _e( 'This window shows up when you click a link to download your PDF file.', 'export2pdf' ); ?>
            </p>
          
        </tr>
        
        <!-- End of download window -->
        
        <?php if ( \Export2Pdf\Api::enabled() ): ?>
        
        <!-- API key -->
        
        <tr>
          
          <th scope="row">  
            <label>
              <?php _e( 'API key', 'export2pdf' ); ?>
            </label>
          </th>
          
          <td>
          
            <?php _e( 'Your API key is', 'export2pdf' ); ?>
            
            <strong>
              <?php echo \Export2Pdf\Api::key(); ?>
            </strong>
            
            <p class="description">
              Note: Plugin is still in development. Continuous usage of Export2PDF will require a paid subscription soon.
            </p>
            
          </td>
          
        </tr>
        
        <!-- end of API key -->
        
        <?php endif; ?>
        
        <!-- Debug mode -->

        <tr>
          
          <th scope="row">  
            <label>
              <?php _e( 'Debugging', 'export2pdf' ); ?>
            </label>
          </th>
          
          <td>
          
            <input 
              type="hidden" name="settings[debug_mode]" 
              value="0"
            />
          
            <fieldset>
              
                <label>
                  <input 
                    type="checkbox" name="settings[debug_mode]" 
                    value="1" 
                    <?php if ( \Export2Pdf\Debug::enabled() ) echo ' checked="checked"'; ?> 
                  />
                  <?php _e( 'Enable debug mode (for advanced users)', 'export2pdf' ); ?>
                </label>
            
            </fieldset>
            
            <p class="description">
              <?php _e( "Shows more information about what's happening behind the scenes.", 'export2pdf' ); ?>
            </p>
          
        </tr>
        
        <!-- End of debug mode -->
      
      </tbody>
    </table>
    
    <button class="button button-primary" type="submit" name="save">
      <?php _e( 'Save', 'export2pdf' ); ?>
    </button>

  </form>
  
</div>
