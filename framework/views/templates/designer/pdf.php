<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<div class="export2pdf-designer-loading">
  
  <div class="export2pdf-designer-loading-message">
    <p class="description text-center">
      <img src="<?php echo admin_url( 'images/wpspin_light.gif' ); ?>" alt="Loading..." width="16" height="16" />
      <?php _e( 'Loading', 'export2pdf' ); ?>...
    </p>
  </div>

  <div class="export2pdf-pdf-designer">

    <p class="description text-center">
      <?php _e( 'Your PDF file is shown below. Click on a PDF form field to change its options.', 'export2pdf' ); ?>
    </p>

    <div class="export2pdf-designer">

      <?php foreach ( $template->file()->pages() as $page ): ?>

        <div 
          class="export2pdf-page export2pdf-page-loading" 
          data-info="<?php echo esc_attr( json_encode( $page ) ); ?>" 
          data-loading="<?php echo __( 'Loading', 'export2pdf' ); ?>"
          style="padding-bottom: <?php echo ( $page->height / $page->width ) * 100.0; ?>%;" 
          data-preview="<?php echo $page->preview_url(); ?>">
        
          <div class="export2pdf-fields">
            
            <?php foreach ( $page->fields_with_occurrences() as $field ): ?>
            
              <div 
                class="export2pdf-field field_<?php echo $field->id(); ?> occurrence" 
                data-info="<?php echo esc_attr( json_encode( $field->info( $template ) ) ); ?>" 
                title="<?php echo $field->name(); ?>">                
              </div>
            
            <?php endforeach; ?>
            
          </div>
          
        </div>

      <?php endforeach; ?>
      
    </div>
    
  </div>

</div>
