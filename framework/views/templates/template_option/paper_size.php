<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
  $paper_sizes = array();

  $unit = \Export2Pdf\Settings::get( "measurement_unit" );
  
  foreach ( \Export2Pdf\TemplateHtml::$paper_sizes as $paper_size => $dimensions )
  {
  
    $preview_scale = 0.8;
    
    $width  = round( $dimensions[ 0 ] * $preview_scale );
    $height = round( $dimensions[ 1 ] * $preview_scale );
    
    $css_style  = "";
    $css_style .= "width: {$height}px; ";
    $css_style .= "-webkit-transform: translate(0px, " . ( $height - 18 ) . "px) rotateZ(-90deg); ";
    $css_style .= "-moz-transform: translate(0px, " . ( $height - 18 ) . "px) rotateZ(-90deg); ";
    $css_style .= "transform: translate(0px, " . ( $height - 18 ) . "px) rotateZ(-90deg); ";
    
    if ( $unit == 'in' )
    {
    
      // Convert to inches
      $dimensions[ 0 ] /= \Export2Pdf\TemplateHtml::MM_PER_INCH;
      $dimensions[ 1 ] /= \Export2Pdf\TemplateHtml::MM_PER_INCH;
      
      // Round up to 1 digit after comma
      $dimensions[ 0 ] = round( $dimensions[ 0 ], 1 );
      $dimensions[ 1 ] = round( $dimensions[ 1 ], 1 );
      
    }
    
    $paper_sizes[ $paper_size ] = array( 
    
      $width, $height,                          // Size in pixels
      $dimensions[ 0 ], $dimensions[ 1 ],       // Size in millimeters or inches
      $css_style,                               // CSS style for transform
      
    );
    
    $maximum_width = max( $maximum_width, $width );

  }
  
  
?>

<?php foreach ( $paper_sizes as $paper_size => $dimensions ): ?>

  <span 
    class="export2pdf-paper-container"
    style="width: <?php echo $maximum_width; ?>px;"
    >

    <a 
      class="export2pdf-like-button<?php if ( $paper_size == $option_value ) echo ' export2pdf-selected'; ?>" 
      href="#" 
      data-value="<?php echo esc_attr( $paper_size ); ?>"
      style="width: <?php echo $dimensions[ 0 ]; ?>px; height: <?php echo $dimensions[ 1 ]; ?>px;"
      >
      
      <span class="export2pdf-size export2pdf-size-width">
        <i></i>
        <span>
          <?php echo $dimensions[ 2 ]; ?> <?php echo $unit; ?>
        </span>
      </span>

      <span class="export2pdf-size export2pdf-size-height" style="<?php echo $dimensions[ 4 ]; ?>">
        <i></i>
        <span>
          <?php echo $dimensions[ 3 ]; ?> <?php echo $unit; ?>
        </span>
      </span>
      
      <strong>
        <?php echo $paper_size; ?>
      </strong>
      
    </a>
  
  </span>

<?php endforeach; ?>

