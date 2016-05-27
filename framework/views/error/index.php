<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?><!DOCTYPE html>
<html>

  <head>
  
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    
    <title><?php echo 'Error'; ?></title>
    
    <style type="text/css">
      <?php
        $assets_path = \Export2Pdf\Framework::assets_path();
        echo file_get_contents( $assets_path . 'css/global/error.css' );
        echo file_get_contents( $assets_path . 'css/error/index.css' );
      ?>
    </style>
    
    <script type="text/javascript">
      <?php
        echo file_get_contents( $assets_path . 'js/global/error.js' );
      ?>
    </script>
    
  </head>
  
  <body>
  
    <div class="inner-content export2pdf" id="inner-content">
    
      <h1>
        <?php echo $this->getMessage(); ?>
      </h1>
      
      <?php $this->show_trace(); ?>
      
    </div>
    
  </body>
</html>
