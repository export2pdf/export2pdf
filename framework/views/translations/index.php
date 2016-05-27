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
  
    <table class="wp-list-table widefat striped pages">

      <thead>
        <tr>
        
          <th colspan="2"><?php _e( 'Original', 'export2pdf' ); ?></th>
          <th><?php _e( 'Translated', 'export2pdf' ); ?></th>
        
        </tr>
      </thead>
      
      <tbody>
      
        <?php foreach ( $translations as $translation ): ?>
        
          <tr data-id="<?php echo $translation->id(); ?>" class="<?php if ( ! $translation->translated ) echo 'export2pdf-not-translated'; ?>">
          
            <!-- Delete translation link -->
          
            <td width="10">
              <a 
                href="#" 
                class="export2pdf-delete-translation" 
                title="<?php _e( 'Delete this translation', 'export2pdf' ); ?>"
                onclick="return confirm('<?php echo addslashes( __( 'Do you really want to remove this translation?' , 'export2pdf' ) ); ?>');"
                ><span class="dashicons dashicons-no"></span></a>
            </td>
          
            <!-- English text -->
          
            <td width="50%">   
              <?php echo htmlspecialchars( $translation->original ); ?>
            </td>
     
            <!-- Translated text -->
            
            <td width="50%">
            
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
