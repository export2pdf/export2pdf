<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<fieldset>
  
  <select class="regular-text" name="template">
    
    <!-- <option value="">(select template)</option> -->

    <?php 
    
      foreach ( \Export2Pdf\Template::all() as $template_list ): 
    ?>
    
      <label>
        
        <option
          value="<?php echo $template_list->id(); ?>" 
          <?php if ( $template_list->id() == $template->id() ) echo ' selected="selected"'; ?> 
        ><?php echo $template_list->name(); ?></option>
    
    <?php endforeach;?>
    
  </select>
  
  <?php if ( ! $is_modal ): ?>
    <a class="button" href="<?php echo \Export2Pdf\Controller::url_for( 'templates', 'edit_step1' ); ?>"><?php _e( 'New Template', 'export2pdf' ); ?></a>
  <?php endif; ?>

</fieldset>
