<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Extends stadard Exception class to add custom methods
 * (showing user-friendly errors, ...)
 */

class Exception extends \Exception
{

  /**
   * Show an error in the admin dashboard
   */
  public function show_in_dashboard()
  {
    ?>
    
      <div class="error inline">
        <p>
          <?php echo $this->getMessage(); ?>
        </p>
      </div>
      
      <?php $this->show_trace(); ?>
      
      <p>&nbsp;</p>
      
    <?php
  }
 
  /**
   * Show PHP trace information
   */
  public function show_trace()
  {
    require __DIR__ . '/../views/error/trace.php';
  }
  
  /**
   * Show an error in AJAX requests
   */
  public function show_in_ajax()
  {
  
    status_header( 500 );
    
    // Show the template
    require __DIR__ . '/../views/error/index.php';
    
  }

  /**
   * Show an error
   *
   * This can be an HTML template, if we're doing AJAX,
   * or it can be an error in the dashboard (inline)
   */
  public function show()
  {
  
    if ( did_action( 'admin_head' ) )
    {
      $this->show_in_dashboard();
    }
    else
    {
      $this->show_in_ajax();
    }
    
    export2pdf_log( $this );
  
  }
  
  /**
   * Show the error and die
   */
  public function show_and_die()
  {
    $this->show();
    wp_die();
  }
  
  /**
   * Highlight PHP code
   * (basic, just for debugging)
   */
  public function highlight_php( $line, $is_current = FALSE )
  {
  
    $line = htmlspecialchars( $line );
    
    // Highlight keywords
    $keywords = array(
      "return[\\\\; ]",
      "public ",
      "private ",
      "static ",
      "function ",
    );
    
    foreach( $keywords as $keyword )
      $line = preg_replace( "/(" . $keyword . ")/", "<strong>$1</strong>", $line );
      
    // Highlight variables
    $line = preg_replace( "/([ \n\t]+)(\\\$[a-z0-9A-Z\_]+)/", "$1<var>$2</var>", $line );
      
    // Highlight comments
    $line = preg_replace( "/([ \n\t])(\/\/.*?)$/", "$1<del>$2</del>", $line );
    
    $line = str_replace( " ", "&nbsp;", $line );
    
    // Highlight current line
    if ( $is_current )
      $line = '<div class="current">' . $line . '</div>';
    
    return $line;
    
  }

}
