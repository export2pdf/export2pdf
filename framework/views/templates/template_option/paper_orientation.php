<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<a 
  class="export2pdf-like-button<?php if ( 'portrait' == $option_value ) echo ' export2pdf-selected'; ?>" 
  href="#" 
  data-value="portrait">
  
  <span>Portrait</span>
  
</a>

<a 
  class="export2pdf-like-button<?php if ( 'landscape' == $option_value ) echo ' export2pdf-selected'; ?>" 
  href="#" 
  data-value="landscape">
  
  <span>Landscape</span>
  
</a>
