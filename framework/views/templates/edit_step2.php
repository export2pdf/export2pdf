<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<?php require __DIR__ . '/edit/header.php'; ?>

<?php if ( \Export2Pdf\Tools::is_post() ): // Process file upload ?>

  <?php
  
    try
    {
      
      // Get attachment
      $attachment_id = $_POST[ 'attachment_id' ];
      $pdf_path = get_attached_file( $attachment_id, true );
      
      \Export2Pdf\Progress::create();
      \Export2Pdf\Progress::step( 'Uploading file...' );
      
      $template->add_pdf_file( $pdf_path );
      
      $redirect_url = \Export2Pdf\Controller::url_for(
        'templates',
        'edit_step3',
        array(
          'template' => $template->id(),
        )
      );
      
      // Redirect to the next step
      \Export2Pdf\Tools::redirect( $redirect_url );
      
    }
    catch ( Exception $e )
    {
    
      // An error occured
    
      ?>
      
      <?php $e->show(); ?>
      
      <script type="text/javascript">
        jQuery( ".export2pdf-progress" ).remove();
      </script>
      
      <a href="<?php echo $controller->action_url( 'edit_step2', array( 'template' => $template->id() ) ); ?>" class="button button-primary">
        <span class="glyphicon glyphicon-cloud-upload"></span> Upload another file
      </a>
      
      <?php
      
    }
  
  ?>

<?php else: ?>

  <form method="POST" id="export2pdf-upload-form">
    
    <?php
    
      $use_html_url = \Export2Pdf\Controller::url_for(
        'templates',
        'edit_step2',
        array(
          'template' => $template->id(),
          'set_type' => 'PdfHtmlFile',
        )
      );
    
      try
      {
      
        $pdf = $template->file();
        
        $redirect_url = \Export2Pdf\Controller::url_for(
          'templates',
          'edit_step3',
          array(
            'template' => $template->id(),
          )
        );
        
        echo '<p class="description description-template-exists" style="text-align: center;">';
        
        if ( $template instanceof \Export2Pdf\TemplateHtml )
        {
          echo __( 'You have already created an HTML template.', 'export2pdf' );
        }
        else
        {
        
          printf(
            __( 'You have already uploaded a <a href="%s" target="_blank">PDF file</a> for this template.', 'export2pdf' ),
            $pdf->url()
          );
          
        }
        
        printf(
          __( '<br /><br /><a href="%s" class="button button-primary">Skip this step and keep current template</a>
               <br /><br />To use a new template, please click on of the buttons below:', 
               'export2pdf' 
            ),
          $redirect_url
        );
      
        echo '</p>';
        
      }
      catch ( Exception $e )
      {
      }
    
    ?>
    
    <div class="export2pdf-large-button">
      <a href="<?php echo $use_html_url; ?>" class="button button-hero" data-confirmation="<?php echo esc_attr( __( 'Your current template will be overwritten. Are you sure you want to overwrite it? This action cannot be undone.' , 'export2pdf' ) ) ?>"><?php _e( 'Create a PDF', 'export2pdf' ); ?></a>
      <span style="padding: 0 15px; text-align: center;"><?php _e( 'or', 'export2pdf' ); ?></span>
      <a href="#" class="button button-hero button-upload-pdf" data-confirmation="<?php echo esc_attr( __( 'Your current template will be removed. Are you sure you want to overwrite it? This action cannot be undone.' , 'export2pdf' ) ) ?>"><?php _e( 'Upload a PDF', 'export2pdf' ); ?></a>
    </div>
    
    <input type="hidden" name="attachment_id" value="" />
    
  </form>

<?php endif; ?>

<?php require __DIR__ . '/edit/footer.php'; ?>
