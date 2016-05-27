<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<div class="wrap export2pdf">

  <h1>
    <?php _e( 'Translations', 'export2pdf' ); ?>
  </h1>
  
  <form method="POST">
  
    <?php if ( \Export2Pdf\Tools::is_post() ): ?>
    
      <div class="updated inline">
        <p>
          <?php _e( 'Translations have been saved.', 'export2pdf' ); ?>
        </p>
      </div>
    
    <?php endif; ?>
  
    <table class="wp-list-table widefat fixed striped pages">

      <thead>
        <tr>
        
          <th>Original</th>
          <th>Translated</th>
          
        
        </tr>
      </thead>
      
      <tbody>
      
        <?php foreach ( $translations as $translation ): ?>
        
          <tr>
          
            <td>   
              <?php echo htmlspecialchars( $translation->original ); ?>
            </td>
     
            <td>
            
              <input
                type="text"
                name="translations[<?php echo $translation->id() ?>]"
                value="<?php echo esc_attr( $translation->translated ); ?>"
                />
              
            </td>
          
          </tr>
        
        <?php endforeach; ?>
      
      </tbody>

    </table>
    
    <br />
    
    <button class="button button-primary" type="submit" name="save">
      <?php _e( 'Save', 'export2pdf' ); ?>
    </button>
    
  </form>
  
</div>
