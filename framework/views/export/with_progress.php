<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?><!DOCTYPE html>
<html>

  <head>
  
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    
    <title><?php 
    
      printf(
        __( 'Downloading %s ...', 'export2pdf' ),
        $this->filename()
      );
    
    ?></title>
    
    <style type="text/css">
      <?php
        $assets_path = \Export2Pdf\Framework::assets_path();
        echo file_get_contents( $assets_path . 'css/export/with_progress.css' );
        echo file_get_contents( $assets_path . 'css/global/progress.css' );
      ?>
    </style>
    
    <script type="text/javascript">
      <?php
        echo file_get_contents( $assets_path . 'js/export/with_progress.js' );
        echo file_get_contents( $assets_path . 'js/global/progress.js' );
      ?>
    </script>
    
  </head>
  
  <body>
  
    <div class="inner-content" id="inner-content">
      <?php \Export2Pdf\Progress::create(); ?>
    </div>
    
    
