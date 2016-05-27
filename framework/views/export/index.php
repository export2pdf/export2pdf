<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<div class="wrap export2pdf">

  <?php if ( ! $is_modal ): ?>

    <h1>
      <?php _e( 'Export to PDF', 'export2pdf' ); ?>
    </h1>
    
    <div class="wp-filter"> 
      <div class="media-toolbar-secondary">
        <div class="view-switch media-grid-view-switch">
	        <a 
	          href="<?php echo $controller->action_url( 'index', array( 'mode' => 'grid' ) ); ?>" 
	          class="view-grid<?php if ( $mode == 'grid' ) echo ' current'; ?>"
	          title="<?php echo esc_attr( __( 'Grid View', 'export2pdf' ) ); ?>"
	        >
		        <span class="screen-reader-text"><?php _e( 'Grid View', 'export2pdf' ); ?></span>
	        </a>
	        <a 
	          href="<?php echo $controller->action_url( 'index', array( 'mode' => 'list' ) ); ?>" 
	          class="view-list<?php if ( $mode == 'list' ) echo ' current'; ?>"
	          title="<?php echo esc_attr( __( 'List View', 'export2pdf' ) ); ?>"
	        >
		        <span class="screen-reader-text"><?php _e( 'List View', 'export2pdf' ); ?></span>
	        </a>
        </div>
      </div>
    </div>
  
  <?php endif; ?>
  
  <form action="<?php echo $controller->action_url( 'index' ); ?>" method="POST" class="export2pdf-preview-shortcode">

    <input type="hidden" name="action" value="<?php echo esc_attr( $controller->action_name() ); ?>" />

    <table class="form-table">
      <tbody>
      
        <!-- Template selection -->
        
        <tr>
          
          <th scope="row">  
            <label for="template"><?php _e( 'Select template:', 'export2pdf' ); ?></label>
          </th>
          
          <td>
          
            <?php include __DIR__ . '/index/' . $mode . '.php'; ?>
          
          </td>
          
        </tr>
         
        <!-- End of template selection -->
        
        <!-- Check if template is available -->
        
        <tr
          <?php if ( $template->id() and ! $template->available() ) echo ''; else echo ' style="display: none;"'; ?>
        >
        
          <th scope="row"></th>
          
          <td>
            
              <div class="error inline">
                <p>
                  Template <a href="<?php echo $template->edit_link(); ?>"><?php echo $template->name(); ?></a> is not available for exporting.<br />
                  Please <a href="<?php echo $template->edit_link(); ?>">click here</a> to check its configuration.
                </p>
              </div>
            
          </td>
          
        </tr>
        
        <!-- End of check if template is available -->
        
        <!-- Entry selection -->
        
        <tr
          <?php if ( ! $form->id() ) echo ' style="display: none;"'; ?>
        >
          
          <th scope="row">  
            <label for="entry"><?php _e( 'Select entry:', 'export2pdf' ); ?></label>
          </th>
          
          <td>
          
            <?php if ( ! count( $form->entries() ) ): ?>
            
              <div class="error inline">
                <p>
                  <?php 
                    $translation = __( "Form <i>%s</i> doesn't have any entries yet.", 'export2pdf' ); 
                    echo sprintf( $translation, $form->name() );
                  ?>
                </p>
              </div>
            
            <?php else: ?>

              <select class="regular-text" name="entry">
              
                <?php 
                
                  foreach ( $form->entries() as $entry_list ): 
                ?>
                
                    <option
                      value="<?php echo $entry_list->id(); ?>" 
                      <?php if ( $entry_list->id() == $entry->id() ) echo ' selected="selected"'; ?> 
                    >
                    <?php echo $entry_list->name(); ?></option>
                
                <?php endforeach;?>
            
              </select>
            
            <?php endif; ?>
            
            <?php if ( ! $is_modal ): ?>
            
              <?php if ( $form->add_entry_url() ): ?>
              
                <a class="button" href="<?php echo $form->add_entry_url(); ?>" target="_blank">
                  <?php _e( 'New Entry', 'export2pdf' ); ?>
                </a>
                
              <?php endif; ?>
            
              <p class="description">
              
                <?php 
                
                  $entries_count = count( $form->entries() );
                  $entries_count_text = sprintf( _n( '%s entry', '%s entries', $entries_count), $entries_count, 'export2pdf' ); 
                
                  $translation = __( "Template %s is is designed to work with form %s which has %s.", 'export2pdf' ); 
                  echo sprintf( 
                    $translation, 
                    '<a href="' . $template->url() . '" target="_blank">' . $template->name() . '</a>',
                    '<a href="' . $form->entries_url() . '" target="_blank">' . $form->name() . '</a>',
                    $entries_count_text
                  );
                ?>
                  
              </p>
            
            <?php endif; ?>
          
          </td>
          
        </tr>
         
        <!-- End of entry selection -->
        
        <!-- Shortcode output -->
        
        <tr
          <?php if ( ! $entry->id() ) echo ' style="display: none;"'; ?>
        >
          
          <th scope="row">  
            <label for="entry"><?php _e( 'Shortcode:', 'export2pdf' ); ?></label>
          </th>
          
          <td>
          
            <?php if ( $entry->id() ): ?>
            
                <input 
                  type="text" 
                  value="<?php echo esc_attr( \Export2Pdf\ShortcodeExport::generate( $template, $entry ) ); ?>" 
                  class="regular-text"
                  id="shortcode_text"
                />
                
                <?php if ( ! $is_modal ): ?>
                
                  <span class="export2pdf-copy-paste">
                    <a class="button" href="#"><?php _e( 'Copy', 'export2pdf' ); ?></a>
                    <span></span>
                  </span>
                  
                  <p class="description">
                    <?php _e( 'You can copy-paste the shortcode above to use it on your pages or posts.', 'export2pdf' ); ?><br />
                    <?php _e( 'You can also use shortcode builder button in the WordPress editor.', 'export2pdf' ); ?>
                  </p>
              
                <?php endif; ?>
            
            <?php endif; ?>
            
          
          </td>
          
        </tr>
         
        <!-- End of shortcode output -->
        
        <!-- Download file output -->
        
        <tr
          <?php if ( ! $entry->id() or $is_modal ) echo ' style="display: none;"'; ?>
        >
          
          <th scope="row"></th>
          
          <td>
          
            <?php if ( $entry->id() ): ?>
            
              <a href="<?php echo esc_attr( \Export2Pdf\ShortcodeExport::generate_link( $template, $entry ) ); ?>" class="button button-primary" target="download_window">
                <?php _e( 'Download PDF', 'export2pdf' ); ?>
              </a>
              
              <p class="description">
                <?php _e( 'Click the button above to export your entry into a PDF file.', 'export2pdf' ); ?>
              </p>
            
            <?php endif; ?>
          
          </td>
          
        </tr>
         
        <!-- End of download file output -->
        
        <!-- Insert shortcode output -->
        
        <tr
          <?php if ( ! $is_modal ) echo ' style="display: none;"'; ?>
        >
          
          <th scope="row"></th>
          
          <td>
          
            <?php if ( $entry->id() ): ?>
            
              <a href="#" class="button button-primary export2pdf-insert-shortcode" onclick="top.tinymce.activeEditor.selection.setContent( jQuery('#shortcode_text').val() ); top.tinymce.activeEditor.windowManager.close(); return false;">
                <?php _e( 'Insert Shortcode', 'export2pdf' ); ?>
              </a>
            
            <?php endif; ?>
          
            <button class="button" onclick="top.tinymce.activeEditor.windowManager.close(); return false;">
              <?php _e( 'Cancel', 'export2pdf' ); ?>
            </button>
          
          </td>
          
        </tr>
         
        <!-- End of insert shortcode output -->
        
      </tbody>
    </table>
    
  </form>
  
</div>
