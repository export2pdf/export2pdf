<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Handles a progress bar, just to make it user-friendly
 */
 
class Progress
{

  public $progress;
  public $step;
  public $step_min;
  public $step_max;

  private static $instance;
  private $created = false;
  
  /**
   * Get a singleton
   *
   * @return Progress Singleton
   */
  public static function get()
  {
  
    if ( self::$instance )
      return self::$instance;
    
    self::$instance = new Progress();
    
    return self::$instance;
    
  }
  
  /**
   * Set progress mark
   *
   * @param $progress int Progress for the current step
   */
  public static function set( $progress, $title = NULL )
  {
  
    if ( $title )
      self::get()->step = $title;
      
    self::get()->progress = $progress / 100.0;
    
    self::render();
    
  }
  
  /**
   * Make progress bar pulsate, probably we're doing something for a long time
   */
  public static function pulsate( $type = '' )
  {
    
    // Do not output if progress bar wasn't created
    if ( ! self::get()->created )
      return;
    
    echo '<script type="text/javascript">export2pdf_progress_pulsate("' . $type . '");</script>' . "\n";
    Tools::flush();
    
  }
  
  /**
   * Set a step mark
   *
   * @param $title string Title of the current step
   * @param $min float Minimum progress, 0 to 100
   * @param $max float Maximum progress, 0 to 100
   */
  public static function step( $title, $min = NULL, $max = NULL )
  {
  
    $progress             = self::get();
    
    // If current step progress has changed
    if ( $min != NULL )
    {
    
      if ( $max == NULL )
      {
      
        // We might supply only one argument, 
        // so that will be min and max value at the same time
        $progress->step_min   = $min;
        $progress->step_max   = $min;
        
      }
      else
      {
      
        // Both min and max were supplied
        $progress->step_min   = $min;
        $progress->step_max   = $max;
        
      }
    
    }
    
    $progress->step       = $title;
    $progress->progress   = 0.0;
    self::render();
    
  }
  
  /**
   * Get global progress value
   */
  public function progress()
  {
  
    $total_progress = $this->step_min / 100.0;
    $current_step_progress = ( $this->step_max - $this->step_min ) / 100.0 * $this->progress;
    return $current_step_progress + $total_progress;
    
  }
  
  /**
   * Render current progress
   */
  public static function render()
  {
    // Do not output if progress bar wasn't created
    if ( ! self::get()->created )
      return;
  
    $progress = self::get();
    
    $progress_data = array(
      "step"     => $progress->step,
      "progress" => $progress->progress(),
    );
    
    echo '<script type="text/javascript">export2pdf_progress(' . json_encode( $progress_data ) . ');</script>' . "\n";
    Tools::flush();
    
    //sleep( 1 );
    
  }
  
  /**
   * Initialize a progress bar
   */
  public static function initialize()
  {

    // Indicates that progress bar has been rendered to a user
    self::get()->created = true;
    
  }
  
  /**
   * Render HTML markup for the progress bar
   */
  public static function create()
  {
  
    self::get()->created = true;
     
    echo '
      <div class="export2pdf-progress">
        <div id="export2pdf-progress-title">
          Loading...
        </div>
        <div id="export2pdf-progress-bar">
          <div id="export2pdf-progress-bar-progress"></div>
        </div>
      </div>
    ';
    
  }
 
}
