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

  <div class="export2pdf-html-designer">

    <p class="description text-center">
      <?php _e( 'Text that you write below will be transformed into a PDF.', 'export2pdf' ); ?>
    </p>

    <input type="hidden" name="template" value="<?php echo $template->id(); ?>" />

    <?php 
    
      wp_editor(
      
        $template->file()->content(),
        'html_content',
        
        array(
          // Editor options
          // 'editor_height' => 1000,
          'tinymce' => array(
            'autoresize_on_init'  => true,
            'wp_autoresize_on'    => true,
          ),
        )
        
      );
    
    ?>
    
  </div>
  
</div>
