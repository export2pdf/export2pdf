<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<input type="hidden" name="current_option" value="<?php echo esc_attr( $option ); ?>" />

<?php include __DIR__ . '/template_option/' . $option . '.php'; ?>
