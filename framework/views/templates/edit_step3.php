<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<?php require __DIR__ . '/edit/header.php'; ?>

  </div></div></div> <!-- Close div's, and put designer on default background -->

  <?php add_thickbox(); ?>

  <?php if ( isset( $error ) ): ?>
  
    <div class="error inline">
      <p>
        <?php echo esc_html( $error ); ?>
      </p>
    </div>
  
  <?php endif; ?>

  <iframe
    class="export2pdf-hidden-iframe"
    src="about:blank"
    data-src="<?php 
    
      /*
        echo $controller->action_url(
          'get_preview',
          array( 'template' => $template->id() )
        );
      */
      
      $preview_params = array(
        'action'   => 'export2pdf_get_preview',
        'template' => $template->id(),
      );
      echo admin_url( 'admin-ajax.php' ) . '?' . http_build_query( $preview_params );
      
      ?>"
    ></iframe> <!-- just a hidden iframe, so that previews are generated asynchroneously -->

  <script type="text/javascript">
    
    // List of available fonts
    window.export2pdf_fonts = <?php echo json_encode( \Export2Pdf\Font::all() ); ?>;
    // List of available paper sizes
    window.export2pdf_paper_sizes = <?php echo json_encode( \Export2Pdf\TemplateHtml::$paper_sizes ); ?>;
    // List of available paper orientations
    window.export2pdf_paper_orientations = <?php echo json_encode( \Export2Pdf\TemplateHtml::$paper_orientations ); ?>;
    // Template options
    window.export2pdf_template_options = <?php echo json_encode( $template->options() ); ?>;
    // Form fields by groups
    window.export2pdf_form_fields_groupped = <?php 
    
      $fields = array();
      
      // Organize fields by groups
      foreach ( $template->form()->fields() as $field )
      {
        
        $group = $field->group();
        if ( ! $group )
          $group = '(none)';
        
        if ( ! isset( $fields[ $group ] ) )
          $fields[ $group ] = array();
      
        $fields[ $group ][] = array(
          "name"       => $field->name(),
          "id"         => $field->id(),
          "shortcode"  => $field->shortcode(),
        );
        
      }
      
      echo json_encode( $fields );
      
    ?>;
    // Form field names
    window.export2pdf_form_fields = <?php 
    
      $fields = array();
      
      foreach ( $template->form()->fields() as $field )
        $fields[ $field->id() ] = $field->name();
    
      echo json_encode( $fields );
      
    ?>;
    
  </script>

  <form method="POST" enctype="multipart/form-data">

    <?php include __DIR__ . '/designer/' . $template->type() . '.php'; ?>
  
  </form>
  
  <div><div><div> <!-- Restore closing div's -->

<?php require __DIR__ . '/edit/footer.php'; ?>
