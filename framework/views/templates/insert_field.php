<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<div class="wrap export2pdf">

  <?php if ( $shortcode ): ?>

    <div class="updated inline">
      <p>
        Inserting shortcode...
      </p>
    </div>

    <script type="text/javascript">
      top.export2pdf_insert_shortcode( '<?php echo addslashes( $shortcode ) ?>' );
      top.tinymce.activeEditor.windowManager.close();
    </script>

  <?php else: ?>


    <form method="POST">

      <!--
      
        <?php if ( isset( $error ) ): ?>

          <div class="error inline">
            <p>
              <?php echo $error; ?>
            </p>
          </div>
          
        <?php elseif ( isset( $success ) ): ?>

          <div class="updated inline">
            <p>
              Field map has been saved.
            </p>
          </div>
          
        <?php endif; ?>
      
      -->

      <table class="form-table">
      
        <tbody>
        
          <tr>
            
            <th scope="row">
              <label for="map_source_id">
                Corresponding form field
              </label>
            </th>
            
            <td>
              <select name="map[source_id]" id="map_source_id" class="regular-text">
              
                <option value="">(no corresponding field)</option>
                
                <?php 
                  $previous_format = '';
                  foreach ( $template->form()->fields() as $form_field ): 
                ?>
                
                  <?php 
                  
                    // Show formatting group name
                    if ( $previous_format != $form_field->group )
                    {
                    
                      if ( $previous_format )
                        echo '</optgroup>';
                        
                      echo ' <optgroup label="' . esc_attr( $form_field->group ) . '">';
                      
                      $previous_format = $form_field->group;
                      
                    }
                    
                  ?>
                
                  <option 
                    value="<?php echo esc_attr( $form_field->id() ); ?>"
                    <?php if ( $form_field->id() == $map->source_id ) echo ' selected="selected"'; ?>
                  ><?php echo esc_html( $form_field->name() ); ?></option>
                  
                
                <?php endforeach; ?> 
                
                </optgroup>
                
              </select>
              
              <p class="description">
                Please select a form field that <?php echo $field->name(); ?> field corresponds to.
              </p>
              
            </td>
            
          </tr>
          
          <tr>
            
            <th scope="row">
              <label for="map_formating">
                Format
              </label>
            </th>
            
            <td>
              <select name="map[formatting]" id="map_formating" class="regular-text">
              
                  <option value="">(no formatting)</option>
                
                  <?php  
                    $previous_format = '';
                    foreach ( $formats as $format ): 
                  ?>
                  
                    <?php 
                    
                      // Show formatting group name
                      if ( $previous_format != $format->group )
                      {
                      
                        if ( $previous_format )
                          echo '</optgroup>';
                          
                        echo ' <optgroup label="' . esc_attr( $format->group ) . '">';
                        
                        $previous_format = $format->group;
                        
                      }
                      
                    ?>
                  
                    <option 
                      value="<?php echo esc_attr( $format->id() ); ?>"
                      <?php if ( $format->id() == $map->formatting ) echo ' selected="selected"'; ?>
                    ><?php echo esc_html( $format->name() ); ?></option>
                  
                  <?php endforeach; ?> 
                
                </optgroup>
                
              </select>
              
              <p class="description">
                If you want to change or format the value of a form field, 
                please select one of the formatting options above.
              </p>
              
            </td>
            
          </tr>
          
          <?php
            // Show additional formatting options
            echo $map->format()->show_options( $map ); 
          ?>
          
          <tr>
            
            <th scope="row">
            </th>
            
            <td>
            
              <button class="button button-primary" type="submit">
              
                <?php if ( $map->source_id and !isset( $_REQUEST[ 'shortcode_doesnt_exist' ] ) ): ?>
                  Update Field
                <?php else: ?>
                  Insert Field
                <?php endif; ?>
                
              </button>
              
              <button class="button" onclick="top.tinymce.activeEditor.windowManager.close(); return false;">
                Cancel
              </button>
              
            </td>
            
          </tr>
          
        </tbody>
      
      </table>

    </form>
  
  <?php endif; ?>

</div>
