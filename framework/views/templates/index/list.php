<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<!-- List of templates -->

<table class="wp-list-table widefat fixed striped pages">

  <thead>
    <tr>
    
      <th><?php _e( 'Template', 'export2pdf' ); ?></th>
      <th><?php _e( 'PDF File', 'export2pdf' ); ?></th>
      <th><?php _e( 'Form', 'export2pdf' ); ?></th>
      <th class="column-date"><?php _e( 'Entries', 'export2pdf' ); ?></th>
      <th class="column-date"><?php _e( 'ID', 'export2pdf' ); ?></th>
      
    
    </tr>
  </thead>
  
  <tbody>
  
    <?php foreach ( $templates as $template ): ?>
    
      <tr>
      
        <td>
        
          <strong>
            <a class="row-title" href="<?php echo $template->edit_link(); ?>">
              <?php echo $template->name(); ?>
            </a>
          </strong>
        
          <div class="row-actions">
            <span class="edit">
              <a href="<?php echo $template->edit_link(); ?>"><?php _e( 'Edit' ); ?></a>
              |
            </span>
            <span class="trash">
              <a 
                href="<?php echo $controller->action_url( 'delete', array( 'template' => $template->id() ) ); ?>"
                onclick="return confirm('<?php echo addslashes( __( 'Are you sure you want to delete this template?', 'export2pdf' ) ); ?>');"
              ><?php _e( 'Trash' ); ?></a>
            </span>
          </div>  
          
        </td>
 
        <td>
        
          <?php try { ?>
          
            <?php if ( $template->file()->id() and $template->file()->url() ): ?>
              <a href="<?php echo $template->file()->url(); ?>" download="<?php echo esc_attr( $template->file()->name() ); ?>.pdf">
                <?php echo $template->file()->name(); ?>
              </a>
            <?php endif; ?>
          
          <?php } catch ( Exception $e ) { } ?>
          
        </td>
        
        <td>
        
          <?php try { ?>
        
            <?php if ( $template->form()->id() and $template->form()->url() ): ?>
              <a href="<?php echo $template->form()->url(); ?>">
                <?php echo $template->form()->name(); ?>
              </a>
            <?php endif; ?>
          
          <?php } catch ( Exception $e ) { } ?>
          
        </td>
        
        <td>
        
          <?php try { ?>
        
            <?php if ( $template->form()->id() and $template->form()->entries_url() ): ?>
              <a href="<?php echo $template->form()->entries_url(); ?>">
                <?php echo count( $template->form()->entries() ); ?>
              </a>
            <?php endif; ?>
          
          <?php } catch ( Exception $e ) { } ?>
          
        </td>
        
        <td><?php echo $template->id(); ?></td>
      
      </tr>
    
    <?php endforeach; ?>
  
  </tbody>

</table>

<!-- // List of templates -->
