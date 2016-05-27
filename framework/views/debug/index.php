<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<div class="wrap export2pdf">

  <h1>
    Under the Hood
  </h1>
  
  <table class="wp-list-table widefat fixed striped pages">

    <thead>
      <tr>
      
        <th>Test</th>
        <th>Result</th>
        
      
      </tr>
    </thead>
    
    <tbody>
    
      <?php foreach ( $tests as $test ): ?>
      
        <tr>
        
          <td>   
            <?php echo $test->name(); ?>:
          </td>
   
          <td>
          
            <?php
            
              // If there are no errors, then print the success message
              if ( ! count( $test->errors ) )
              {
                echo '<mark class="success">' . \Export2Pdf\Tools::trim( $test->result ) . '</mark>';
              }
              else
              {
                foreach ( $test->errors as $error )
                  echo '<mark class="error">' . \Export2Pdf\Tools::trim( $error ) . '</mark>';
              }
              
              // And if there are some warnings, print them too
              foreach ( $test->warnings as $warning )
                echo '<mark class="warning">' . \Export2Pdf\Tools::trim( $warning ) . '</mark>';
            
            ?>
            
          </td>
        
        </tr>
      
      <?php endforeach; ?>
    
    </tbody>

  </table>
  
</div>
