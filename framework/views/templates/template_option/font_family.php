<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<?php foreach ( \Export2Pdf\Font::all() as $font ): ?>

  <div id="<?php echo esc_attr( $font->family ); ?>"></div>
  
  <a 
    class="export2pdf-like-button<?php if ( $font->family == $option_value ) echo ' export2pdf-selected'; ?>" 
    href="#" 
    data-value="<?php echo esc_attr( $font->family ); ?>">
    
    <span class="export2pdf-like-button-bg" style="background-image: url('<?php echo $font->preview_url(); ?>');">
    </span>
    
  </a>

<?php endforeach; ?>
