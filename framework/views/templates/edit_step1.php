<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<?php require __DIR__ . '/edit/header.php'; ?>

<form method="POST">

  <table class="form-table">
    <tbody>
        
      <!-- Addon selection -->

      <tr>
        
        <th scope="row">  
          <label for="template_addon">
            <?php _e( 'From which plugin to export?', 'export2pdf' ); ?>
          </label>
        </th>
        
        <td>
        
          <?php if ( count( $addons ) > 0 ): ?>
              
            <fieldset>
              
              <?php foreach ( $addons as $index => $addon ): ?>
              
                <label>
                  <input 
                    type="radio" name="template[addon]" 
                    value="<?php echo esc_attr( $addon->id() ); ?>" 
                    <?php if ( $template->addon == $addon->id() ) echo ' checked="checked"'; ?> 
                    <?php if ( ! $template->addon and ! $index ) echo ' checked="checked"'; // First entry if current addon is empty ?> 
                  />
                  <?php echo $addon->name(); ?>
                </label>
                
                <br />
              
              <?php endforeach;?>
            
            </fieldset>
          
          <?php else: ?>
          
            <div class="error inline">
              <p>
                <?php 
                  _e(   
                    "Seems like you don't have any compatible plugins enabled.", 
                    'export2pdf' 
                  ); 
                ?>
              </p>
            </div>
          
          <?php endif; ?>
          
          
          <?php
          
            // Get the list of enabled, but not available addons
            $plugins = array();
            foreach ( \Export2Pdf\Addon::$addons as $addon )
              if ( ! $addon->available() )
                $plugins[] = sprintf( 
                  '<a href="%s" target="_blank">%s</a>',
                  $addon->url(),
                  $addon->name()
                );
            
            // If some addons are enabled, but not available, then list them here.
            if ( count( $plugins ) )
            {
            
              if ( count( $addons ) > 0 )
              {
              
                echo 
                  '<p class="description">' . 
                  __( 'Export2PDF also works with ' , 'export2pdf' ) .
                  implode( ', ', $plugins ) . '.' .
                  '</p>'
                ;
              
              }
              else
              {
              
                echo 
                  '<p class="description">' . 
                  __( 'Export2PDF works with ' , 'export2pdf' ) .
                  implode( ', ', $plugins ) . '.' .
                  '</p>'
                ;
              
              }
            }
          ?>
          
        </td>
        
      </tr>
      
      <!-- End of addon selection -->
      
      <!-- Addon form selection -->
      
      <?php foreach ( $addons as $addon ): ?>
      
        <?php 
          // Show only forms from the selected addon
          if ( $template->addon != $addon->id() )
            continue;
        ?>
      
        <tr id="export2pdf-form-selection">
          
          <th scope="row">  
            <label for="template_addon"><?php _e( 'What to export?', 'export2pdf' ); ?></label>
          </th>
          
          <td>
        
            <?php if ( count( $addon->forms() ) > 0 ): ?>
                
              <fieldset>
                
                <?php foreach ( $addon->forms() as $index => $form ): ?>
                
                  <label>
                    <input 
                      type="radio" 
                      name="template[form]" 
                      value="<?php echo esc_attr( $form->id() ); ?>" 
                      data-name="<?php echo esc_attr( $form->name() ); ?>"
                      <?php if ( $template->form == $form->id() ) echo ' checked="checked"'; ?> 
                      <?php if ( ! $template->form and ! $index ) echo ' checked="checked"'; // First entry if current form is empty ?> 
                    />
                    <?php echo $form->name(); ?>
                  </label>
                  
                  <br />
              
                <?php endforeach;?>  
              
              </fieldset>
            
            <?php else: ?>
            
              <div class="error inline">
                <p>
                  <?php 
                    _e( "Seems like there are no forms for ", 'export2pdf' ); 
                    echo $addon->name();
                    echo '.';
                  ?>
                </p>
              </div>
            
            <?php endif; ?>
            
          </td>
          
        </tr>
      

      <?php endforeach;?>
      
      <!-- End of addon form selection -->
    
      <tr<?php if ( ! count( $addons ) ) echo ' style="display: none;"'; ?>>
        
        <!-- Template name selection -->
        
        <th scope="row">  
          <label for="template_name"><?php _e( 'Choose template name:', 'export2pdf' ); ?></label>
        </th>
        
        <td>
        
          <input 
            class="regular-text" 
            id="template_name" 
            type="text" 
            name="template[name]" 
            value="<?php echo esc_attr( $template->name() ); ?>" 
            placeholder="<?php if ( $template->form and $template->form()->id() ) echo esc_attr( $template->form()->name() ); ?>" 
            />
            
        </td>
        
      </tr>
       
      <!-- End of template name selection -->
    
    </tbody>
  </table>
  
  <?php if ( count( $addons ) > 0 ): ?>
  
    <button class="button button-primary" type="submit">
      <?php _e( 'Next step', 'export2pdf' ); ?> &raquo;
    </button>
  
  <?php endif; ?>

</form>

<?php require __DIR__ . '/edit/footer.php'; ?>
