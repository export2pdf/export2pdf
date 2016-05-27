<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<!-- List of templates -->

<div class="media-frame mode-grid">
  <ul class="attachments">
    
    <?php foreach ( $templates as $template ): ?>
    
      <li class="attachment">
      
        <a class="attachment-preview" href="<?php echo $template->edit_link(); ?>">
          
          <div class="thumbnail thumbnail-<?php echo $template->format(); ?>">
          
            <?php if ( $template->preview_url() ): ?>
              <img 
                alt="<?php echo esc_attr( $template->name() ); ?>" 
                src="<?php echo $template->preview_url(); ?>"
              />
            <?php endif; ?>
            
          </div>
          <div class="filename">
            <div>
              <?php echo $template->name(); ?>
            </div>
          </div>
          
        </a>
      
      </li>
    
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
