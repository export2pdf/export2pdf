<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<?php require __DIR__ . '/edit/header.php'; ?>

<?php if ( \Export2Pdf\Tools::is_post() ): // Show success message and a preview button ?>

  <div class="updated inline">
    <p>
      
      Settings have been updated.

      <?php if ( count( $entries = $template->form()->entries() ) ): // If form has some entries, display a preview button ?>
      
        <a href="<?php 
          echo \Export2Pdf\ShortcodeExport::generate_link( $template, $entries[ 0 ] );
        ?>" class="button" target="_blank">Preview</a>
      
      <?php endif; ?>
      
    </p>
  </div>
      
<?php endif; ?>

<form method="POST">

  <table class="form-table">
    <tbody>
        
      <!-- Flatten options -->

      <?php if ( ! ( $template instanceof \Export2Pdf\TemplateHtml ) ): ?>

        <tr>
          
          <th scope="row">  
            <label for="template_flatten">
              <?php _e( 'Flatten PDF form?', 'export2pdf' ); ?>
            </label>
          </th>
          
          <td>
          
            <fieldset>
              
              <?php 
              
                $flatten_options = array(
                
                  '0' => 'No',
                  '1' => 'Yes',
                  
                  // TODO: Add transform to images
                  // '2' => 'Yes, and transform text to images'
                  
                );
              
                foreach ( $flatten_options as $value => $title ): 
              ?>
              
                <label>
                  <input 
                    type="radio" name="template[flatten]" 
                    value="<?php echo $value; ?>" 
                    <?php if ( $template->flatten == $value ) echo ' checked="checked"'; ?> 
                  />
                  <?php echo $title; ?>
                </label>
                
                <br />
              
              <?php endforeach;?>
            
            </fieldset>
            
            <p class="description">
              <?php 
                // TODO: place two links with examples
              ?>
              "Flattening" means removing all form fields from the final PDF file.<br />
              If you select "Yes", generated PDFs won't be editable.
            </p>
          
        </tr>
        
      <?php endif; ?>
      
      <!-- End of flatten options -->
      
      <!-- Optimization options -->

      <tr<?php if ( ! $template->flatten and ! ( $template instanceof \Export2Pdf\TemplateHtml ) ) echo ' style="display: none;"'; ?> id="template_optimization">
        
        <th scope="row">  
          <label for="template_flatten">
            <?php _e( 'PDF Optimisations', 'export2pdf' ); ?>
          </label>
        </th>
        
        <td>
        
          <fieldset>
            
            <?php 
            
              $optimisation_options = array(
              
                '1' => 'Basic optimisation',
                '2' => 'Images',
                '4' => 'Fonts',
                
              );
            
              foreach ( $optimisation_options as $value => $title ): 
            ?>
            
              <label>
                <input 
                  type="checkbox" name="template[optimize][]" 
                  value="<?php echo $value; ?>" 
                  <?php if ( $template->optimize & $value ) echo ' checked="checked"'; ?> 
                />
                <?php echo $title; ?>
              </label>
              
              <br />
            
            <?php endforeach;?>
          
          </fieldset>
          
          <p class="description">
            By default, PDFs will be optimized. <br />
            Uncheck the options above to disable optimisations.
          </p>
        
      </tr>
      
      <!-- End of optimization options -->
      
      <!-- Email options -->

      <?php 
        $emails = $template->form()->emails();
        if ( count( $emails ) > 0 ):
      ?>

      <tr>
        
        <th scope="row">  
          <label for="template_flatten">
            <?php _e( 'Attach file to these e-mail notifications', 'export2pdf' ); ?>
          </label>
        </th>
        
        <td>
        
          <fieldset>
            
            <?php foreach ( $emails as $email ): ?>
            
              <label>
                <input 
                  type="checkbox" name="template[actions][]" 
                  value="<?php echo $email->id(); ?>" 
                  <?php 
                    foreach ( $template->actions() as $action )
                      if ( $action->data == $email->id() ) 
                        echo ' checked="checked"'; 
                  ?> 
                />
                <?php echo $email->name(); ?>
              </label>
              
              <br />
            
            <?php endforeach; ?>
          
          </fieldset>
          
          <p class="description">
            If you check an e-mail notification above, then a PDF will be attached to this e-mail.
          </p>
        
      </tr>
      
      <?php endif; ?>
      
      <!-- End of email options -->
      
      <!-- Password options -->

      <tr>
        
        <th scope="row">  
          <label for="template_password">
            <?php _e( 'PDF password', 'export2pdf' ); ?>
          </label>
        </th>
        
        <td>
          <input 
            class="regular-text" 
            id="template_password" 
            type="text" 
            name="template[password]" 
            value="<?php echo esc_attr( $template->password ); ?>" 
            placeholder="(no password)"
            />
          <p class="description">
            Don't fill in if password shouldn't be set.
          </p>
        </td>
        
      </tr>
      
      <!-- End of password options -->
      
      <!-- Primary field options -->

      <!-- 
      
      TODO: Primary field is only needed for some addons, not all of them

      <tr>
        
        <th scope="row">  
          <label for="template_primary_field">
            <?php _e( 'Entry name', 'export2pdf' ); ?>
          </label>
        </th>
        
        <td>
          <select class="regular-text" id="template_primary_field" name="template[form_primary_field]">
          
            <?php foreach ( $template->form()->fields() as $field ): ?>
            
              <option
                value="<?php echo $field->id(); ?>"
                <?php if ( $field->id() == $template->form_primary_field ) echo ' selected="selected"'; ?>
              ><?php echo $field->name(); ?></option>
            
            <?php endforeach; ?>
            
          </select>
          <p class="description">
            Please choose one of your form fields, that identifies each entry.
          </p>
        </td>
        
      </tr>
      
      -->
      
      <!-- End of primary field options -->
      
    
    </tbody>
  </table>
  
  <button class="button button-primary" type="submit" name="save">
    <?php _e( 'Save', 'export2pdf' ); ?>
  </button>
  
  <button class="button" type="submit" name="save_and_export">
    <?php _e( 'Save and export', 'export2pdf' ); ?> &raquo;
  </button>

</form>

<?php require __DIR__ . '/edit/footer.php'; ?>
