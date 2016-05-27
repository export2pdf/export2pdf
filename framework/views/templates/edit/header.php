<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<div class="wrap export2pdf export2pdf-<?php echo $controller->action_name(); ?> export2pdf-<?php echo $controller->controller_name(); ?>">

  <script type="text/javascript">
    window.export2pdf = <?php 
      $data = array(
        'template' => $template,
        'ajax_url' => admin_url( 'admin-ajax.php' ),
      );
      echo json_encode( $data );
    ?>;
  </script>

  <h1>
    <?php
      if ( $template->id() )
        echo 'Edit Template <strong>' . $template->name() . '</strong>';
      else
        echo 'Create a Template';
    ?>
  </h1>
  
  <div id="poststuff">
    <div class="postbox">
      <h3 class="hndle">
        <span>
          <?php 
          
            // List of steps to create a template
            $steps = array( 
              'edit_step1' => 'Choose Name',
              'edit_step2' => 'Select PDF',
              'edit_step3' => 'Map Fields',
              'edit_step4' => 'Settings',
            );
            
            // Display the steps as a chain of links
            $step_passed = true;
            foreach ( $steps as $action_name => $step_title )
            {
              // If the step is passed, then we need an additional variable,
              // which will indiciate if we need a link or plain text.
              // We don't want people to switch, for example, from step #1 to step #4
              if ( $action_name == $controller->action_name() )
                $step_passed = false;
              
              $step_url = $controller->action_url(
                $action_name,
                array(
                  'template' => $template->id(),
                )
              );
              
              // Calculate CSS class for the link
              $link_class = 'available';
              if ( ! $step_passed )
              {
                $link_class = 'nolink';
                $step_url   = '#';
              }
              if ( $action_name == $controller->action_name() )
                $link_class = 'active';
              
              // Add a chain symbol
              if ( $action_name != 'edit_step1' )
                echo '<a class="nolink"> &raquo; </a>';
              
              // Add step counter
              $step_number = str_replace( 'edit_step', '', $action_name );
              
              // Print the link
              printf(
                '<a class="%s" href="%s"><span class="step_number">%s</span>%s</a>',
                $link_class,
                $step_url,
                $step_number,
                $step_title
              );
            }
          
          ?>
        </span>
        
        <?php if ( $controller->action_name() == 'edit_step3' ): // Show "Next step" button only on step 3 (field designer) ?>
        
          <!--
          
            <a href="<?php 
              echo $controller->action_url(
                'edit_step4',
                array(
                  'template' => $template->id(),
                )
              );
            ?>" class="button button-primary">Next Step &raquo;</a>
          
          -->
          
          <a href="#" class="button button-primary export2pdf-next-step">Next Step &raquo;</a>
          
          <?php if ( count( $entries = $template->form()->entries() ) ): // If form has some entries, display a preview button ?>
          
            <a href="#" data-url="<?php 
              echo \Export2Pdf\ShortcodeExport::generate_link( $template, $entries[ 0 ] );
            ?>" class="button export2pdf-preview-button" target="_blank">Save &amp; Preview</a>
          
          <?php endif; ?>
          
        <?php endif; ?>
        
      </h3>
      <div class="inside">
