<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<!-- List of templates -->

<div class="media-frame mode-grid">
  <ul class="attachments">
    
    <?php foreach ( \Export2Pdf\Template::all() as $template_list ): ?>
      
      <input 
        type="radio" name="template" 
        value="<?php echo $template_list->id(); ?>" 
        id="template_<?php echo $template_list->id(); ?>"
        <?php if ( $template_list->id() == $template->id() ) echo ' checked="checked"'; ?> 
      />
    
      <label class="attachment" for="template_<?php echo $template_list->id(); ?>">
      
        <div class="attachment-preview">
          
          <div class="thumbnail thumbnail-<?php echo $template_list->format(); ?>">
          
            <?php if ( $template_list->preview_url() ): ?>
              <img 
                alt="<?php echo esc_attr( $template_list->name() ); ?>" 
                src="<?php echo $template_list->preview_url(); ?>"
              />
            <?php endif; ?>
            
          </div>
          <div class="filename">
            <div>
              <?php echo $template_list->name(); ?>
            </div>
          </div>
          
        </div>
      
      </label>
    
    <?php endforeach; ?>
    
    <a class="attachment" href="<?php echo \Export2Pdf\Controller::url_for( 'templates', 'edit_step1' ); ?>">
    
      <div class="attachment-preview">
        
        <div class="thumbnail thumbnail-portrait">
          <img 
            alt="<?php _e( 'New Template', 'export2pdf' ); ?>" 
            src="<?php echo \Export2Pdf\Framework::assets_url() . 'images/plus_icon.jpg'; ?>"
          />
        </div>
        <div class="filename">
          <div>
            <?php _e( 'New Template', 'export2pdf' ); ?>
          </div>
        </div>
        
      </div>
    
    </a>

  </ul>
</div>

<!-- // List of templates -->
