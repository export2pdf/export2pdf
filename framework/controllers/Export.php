<?php

/**
 * User interface for exporting a form
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

class ExportController extends Controller
{

  const PAGE = "export2pdf";
  
  /**
   * Shows the dropdowns to export into PDF,
   * as well as the shortcode
   */
  public function index()
  {
  
    $this->set_view_mode();
  
    // If there are no templates yet, then redirect to template creation page
    if ( ! count( Template::all() ) )
    {
      $url = Controller::url_for( 'templates', 'edit_step1' );
      Tools::redirect( $url );
      exit;
    }
  
    $template = new Template();
    $entry    = new FormEntry();
    $form     = new Form();
    
    // Get select template, form and entry from the database
    if ( isset( $_GET[ 'template' ] ) and $_GET[ 'template' ] )
    {
    
      // Template was selected already
      $template = Template::get( $_GET[ 'template' ] );
      
      try
      {
        $form = $template->form();
      }
      catch ( Exception $e )
      {
        // Probably the form is not available anymore
      }
      
    }
    else
    {
    
      // Select the first template by default
      $templates = Template::all();
      if ( count( $templates ) )
      {
      
        $template = $templates[ 0 ];
        
        try
        {
          $form = $template->form();
        }
        catch ( Exception $e )
        {
          // Probably the form is not available anymore
        }
        
      }
    
    }
    
    if ( $template->id() and $form->id() )
    {
      if ( isset( $_GET[ 'entry' ] ) and $_GET[ 'entry' ] )
      {
        // Entry was selected already
        try
        {
          $entry = $template->entry( $_GET[ 'entry' ] );
        }
        catch ( Exception $e )
        {
          // Entry was not found.
        }
      }
      if ( count( $form->entries() ) and ! $entry->id() )
      {
        // There's only one entry
        // Select it right away
        $entries = $form->entries();
        $entry = array_shift( $entries );
      }
    }
    
    if ( ! $template->available() )
    {
      $entry    = new FormEntry();
      $form     = new Form();
    }

    // Provide current values to the template
    $this->variables[ 'template' ] = $template;
    $this->variables[ 'form' ]     = $form;
    $this->variables[ 'entry' ]    = $entry;
  
  }
  
  /**
   * Displays shortcode builder
   */
  public function shortcode()
  {
   
    $this->is_modal = true;
  
    // Execute the same functions as for the main page,
    // where users download files
    $this->index();
    
    // Set list mode
    $this->variables[ 'mode' ]     = 'list';
     
  }
  
}
