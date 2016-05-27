<?php

/**
 * Manage PDF files
 */
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
  
class TemplatesController extends Controller
{
  
  const PAGE = "export2pdf-templates";
  
  /**
   * Constructor
   */
  public function __construct()
  {
  
    // Assign URLs for use in views
    $this->variables[ 'page_templates_file_upload' ] = self::action_url( 'upload' );
    $this->variables[ 'page_templates' ]             = self::action_url( 'index' );
    $this->variables[ 'page_create_template' ]       = self::action_url( 'edit_step1' );
  
    // Assign template variable for use in views
    if ( ! empty( $_GET['template'] ) )
      $template = Template::get( $_GET['template'] );
    else
      $template = new Template();
      
    $this->variables[ 'template' ] = $template;
    
  }

  /**
   * Generate a preview for a PDF page
   */
  public function pdf_page_preview()
  {
    
    $page_number = intval( $_GET[ 'page' ] );
    $file_id     = intval( $_GET[ 'file' ] );
    
    $file = PdfFile::get( $file_id );
    $page = $file->page( $page_number );
    if ( ! $page->has_preview() )
      $page->generate_preview();
      
    header( 'Pragma: public' );
    header( 'Cache-Control: max-age=86400' );
    header( 'Expires: '. gmdate( 'D, d M Y H:i:s \G\M\T', time() + 86400 * 200 ) );
    header( 'Content-Type: image/png' );
    header( 'Content-Length: ' . filesize( $page->preview_path() ) );
      
    readfile( $page->preview_path() );
    
    // For debugging:
    // @unlink( $page->preview_path() );
    
    exit;
    
  }

  /** 
   * Insert a field (HTML)
   */
  public function insert_field()
  {
  
    // Use the same styles and scripts as for editing a field
    self::add_asset( 'templates/edit_field' ); 
    $this->is_modal = true;
  
    $template = $this->variables[ 'template' ];
    $file = $template->file();
    $field = $template->field();
    $shortcode = false;

    $map = $template->map( $field );
    
    if ( isset( $_GET[ 'shortcode' ] ) )
    {
      // Shortcode was passed. Restore options from the shortcode
      $passed_shortcode = stripslashes( $_GET[ 'shortcode' ] );
      
      // Launch it once, so that we have the field map
      add_shortcode( \Export2Pdf\ShortcodeField::NAME, array( "\\Export2Pdf\\ShortcodeField", "process" ) );
      do_shortcode( $passed_shortcode );
      remove_shortcode( \Export2Pdf\ShortcodeField::NAME, array( "\\Export2Pdf\\ShortcodeField", "process" ) );
      
      // If the field map was found, then we'll use it
      if ( ShortcodeField::$last_map )
        $map = ShortcodeField::$last_map;
    }
    
    if ( Tools::is_post() )
    {
      try
      {
        // Save field mapping to the database
        $map_data = $_POST['map'];
        $map->source_id = $map_data['source_id'];
        $map->formatting = $map_data['formatting'];
        
        if ( isset( $_POST[ 'options' ] ) )
        {
          $options = stripslashes_deep( (array)$_POST[ 'options' ] );
          $map->options = array_merge( $map->options, (array)$options );
        }
        
        // When no_save variable is posted, 
        // we don't want to save the map yet
        if ( ! isset( $_POST[ 'no_save' ] ) )
        {
          
          $options = array();
          
          if ( $map->formatting )
          {
            // Only if formatting is set
            $options = array_merge( $options, $map->options() );
            $options[ 'formatting' ] = $map->formatting;
          }
          
          $shortcode = ShortcodeField::generate( $map->source(), $options );
          
        }
        
        $this->variables[ 'success' ] = 1;
      }
      catch ( Exception $e )
      {
        $this->variables[ 'error' ] = $e->getMessage();
      }
    }
    
    $this->variables[ 'field' ]     = $field;
    $this->variables[ 'map' ]       = $map;
    $this->variables[ 'shortcode' ] = $shortcode;
    
    // Get all possible formats, and show only those that should be visible
    $formats = \Export2Pdf\Format::all();
    foreach ( $formats as $format )
      $format->template = $template;
    $formats = array_filter( 
      $formats,
      function ( $format )
      {
        return $format->visible();
      }
    );
    $this->variables[ 'formats' ]   = $formats;
    
  }
  
  /**
   * Map a field automatically
   */
  public function map_field_automatically()
  {
  
    $template   = $this->variables[ 'template' ];
    $file       = $template->file();
    $pdf_field  = $file->field( $_REQUEST['field'] );
    $map = $template->map( $pdf_field );
    
    if ( ! empty( $_REQUEST['to_form_field'] ) )
    {
    
      // Add mapping
      $form_field = $template->form()->field( $_REQUEST['to_form_field'] );
      $map->source_id = $form_field->id();
      $map->save();
    
    }
    else
    {
    
      // Remove mapping
      $map->destroy();
    
    }
    
    exit;
  
  }
  
  /** 
   * Edit a field (PDF)
   */
  public function edit_field()
  {

    $this->is_modal = true;
  
    $template = $this->variables[ 'template' ];
    $file = $template->file();
    $field = $file->field( $_REQUEST['field'] );

    $map = $template->map( $field );
    
    if ( Tools::is_post() )
    {
      try
      {
        // Save field mapping to the database
        $map_data = $_POST['map'];
        $map->source_id = $map_data['source_id'];
        $map->formatting = $map_data['formatting'];
        
        
        // Save map options
        if ( isset( $_POST[ 'options' ] ) )
        {
        
          $options = stripslashes_deep( (array)$_POST[ 'options' ] );
          
          foreach ( $options as $option_name => $option_value )
            $map->set_option( $option_name, $option_value );
            
        }
        
        // When no_save variable is posted, 
        // we don't want to save the map yet
        if ( ! isset( $_POST[ 'no_save' ] ) )
        {
          
          if ( $map->source_id )
          {
            // Save if corresponding field was selected
            $map->save();
          }
          else
          {
            // Remove if it was not selected
            $map->destroy();
          }
            
        }
        
        $this->variables[ 'success' ] = 1;
      }
      catch ( Exception $e )
      {
        $this->variables[ 'error' ] = $e->getMessage();
      }
    }
    
    $this->variables[ 'field' ] = $field;
    $this->variables[ 'map' ]   = $map;
    
    // Get all possible formats, and show only those that should be visible
    $formats = \Export2Pdf\Format::all();
    foreach ( $formats as $format )
      $format->template = $template;
    $formats = array_filter( 
      $formats,
      function ( $format )
      {
        return $format->visible();
      }
    );
    $this->variables[ 'formats' ]   = $formats;
    
  }
  
  /**
   * Step 1: Choose name
   */
  public function edit_step1()
  {
    
    // Get the list of all available addons
    $addons = Addon::all();
    $this->variables[ 'addons' ] = $addons;
    
    // Get the template
    $template = $this->variables[ 'template' ];
    
    // Let's see if the addon is still available
    try
    {
      $template->addon();
    }
    catch ( Exception $e )
    {
      // It's not available anymore
      $template->addon = NULL;
    }
    
    // Let's see if the form is still available
    try
    {
      $template->form();
    }
    catch ( Exception $e )
    {
      // It's not available anymore
      $template->form = NULL;
    }
    
    // Assign default addon for a new template
    if ( ! $template->addon )
    {
    
      if ( isset( $_GET[ 'addon' ] ) and ! empty( $_GET[ 'addon' ] ) )
      {
      
        // If addon is supplied as GET variable, then we will use it
        $template->addon = $_GET[ 'addon' ];
        
      }
      else
      {
      
        // Assign the first available template
        if ( count( $addons ) > 1 )
          $template->addon = $addons[ 0 ]->id();
          
      }
      
    }
    else
    {
      if ( isset( $_GET[ 'addon' ] ) and ! empty( $_GET[ 'addon' ] ) )
      {
      
        // If addon is supplied as GET variable, then we will use it
        $template->addon = $_GET[ 'addon' ];
        
      }
    }
    
    // Assign default form for a new template
    if ( $template->addon and ! $template->form )
    {
    
      $forms = $template->addon()->forms();
      if ( count( $forms ) > 1 )
        $template->form = $forms[ 0 ]->id();
        
    }
    
    if ( Tools::is_post() )
    {
      // Form is submitted
      $template_data = stripslashes_deep( $_POST[ 'template' ] );
      
      // Update template data
      $template->name     = trim( $template_data['name'] );
      $template->addon    = trim( $template_data['addon'] );
      $template->form     = trim( $template_data['form'] );
      $template->save();
      
      // Redirect to the next step
      $step_2_url = self::action_url( 
        'edit_step2', 
        array( 
          'template' => $template->id()
        ) 
      );
      Tools::redirect( $step_2_url );
    }
    
    $this->variables[ 'template' ] = $template;
    
  }
  
  /**
   * Step 2: Choose/upload PDF or HTML
   */
  public function edit_step2()
  {
    
    self::add_asset( 'global/progress' ); 
    
    if ( isset( $_GET[ 'set_type' ] ) and ( $_GET[ 'set_type' ] == 'PdfHtmlFile' ) )
    {
      
      // We're going to use HTML instead of a PDF template  
      $template = $this->variables[ 'template' ];
      
      $template = new TemplateHtml( $template->id() );
      $template->add_html_file();
      $template->save();
      
      // Redirect to the designer
      $step_3_url = self::action_url( 
        'edit_step3', 
        array( 
          'template' => $template->id()
        ) 
      );
      Tools::redirect( $step_3_url );
    }
    
  }
  
  /**
   * Step 3: Designer
   */
  public function edit_step3()
  {
    
    try
    {
    
      if ( Tools::is_post() )
      {
      
        $template = $this->variables[ 'template' ];
      
        // If this is an HTML template, let's save it's content
        if ( $template instanceof TemplateHtml )
        {
          $template->file()->set_content( stripslashes( $_POST['html_content'] ) );
        }
        
        // Redirect to the designer
        $step_4_url = self::action_url( 
          'edit_step4', 
          array( 
            'template' => $template->id()
          ) 
        );
        Tools::redirect( $step_4_url );
        
      }
    
    }
    catch ( Exception $e )
    {
      
      $this->variables[ 'error' ] = $e->getMessage(); 
      
    }
    
  }
  
  /**
   * Step 4: Template settings
   */
  public function edit_step4()
  {
    
    if ( Tools::is_post() )
    {
      // Form is submitted
      $template = $this->variables[ 'template' ];
      $template_data = stripslashes_deep( $_POST[ 'template' ] );
      
      // Update template data
      $template->password                = trim( $template_data['password'] );
          
      if ( isset( $template_data['flatten'] ) )
        $template->flatten                 = trim( $template_data['flatten'] );
      else
        $template->flatten = 1;
        
      // TODO: this is needed only for some addons, not all of them
      // $template->form_primary_field      = trim( $template_data['form_primary_field'] );
      
      // Sum up all optimisation options
      $template->optimize = 0;
      if ( isset( $template_data['optimize'] ) and is_array( $template_data['optimize'] ) )
        foreach ( $template_data['optimize'] as $optimization_option )
          $template->optimize += intval( $optimization_option );
      
      // Save template
      $template->save();
      
      // Re-create form actions
      foreach ( $template->actions() as $action )
        $action->destroy();
      if ( isset( $template_data['actions'] ) and is_array( $template_data['actions'] ) )
        foreach ( $template_data['actions'] as $action_id )
        {
          $action = new TemplateAction();
          $action->data        = $action_id;
          $action->template_id = $template->id();
          $action->save();
        }
      $template->actions = false;
      
      if ( isset( $_POST[ 'save_and_export' ] ) )
      {
      
        // Save and redirect to download page
        $overview_url = Controller::url_for( 
          'Export', 
          'index', 
          array( 'template' => $template->id() ) 
        );
        Tools::redirect( $overview_url );
        
      }
      else
      {
      
        /*
        
          // Just save and show the same page
          $step4_url = $this->action_url( 
            'edit_step4', 
            array( 'template' => $template->id() ) 
          );
          Tools::redirect( $step4_url );
        
        */
        
        // Re-initialize the template
        $template = Template::get( $template->id() );
        
      }
      
    }
    
  }
  
  /**
   * Render styles for an HTML template
   */
  public function template_styles()
  {
    
    if ( isset( $_GET[ 'template' ] ) )
    {
      $template_id = $_GET[ 'template' ];
      $template    = Template::get( $template_id );
    }
    else
    {
      $template    = new TemplateHtml();
    }
    
    $styles = $template->style( $for_editor = TRUE );
    
    header( 'Content-Type: text/css; charset=utf-8' );
    header( 'Content-Length: ' . strlen( $styles ) );
    echo $styles;
    exit;
    
  }
  
  /**
   * Render a template option
   */
  public function template_option()
  {
  
    $this->is_modal = true;
  
    $template_id  = $_GET[ 'template' ];
    $template     = Template::get( $template_id );
    $option       = preg_replace( '/[^a-zA-Z0-9\_]+/', '', $_GET['option'] );
    $option_value = $template->option( $option );
    
    self::add_asset( 'templates/template_option/options/' . $option ); 
    
    $this->variables[ 'template' ]       = $template;
    $this->variables[ 'option' ]         = $option;
    $this->variables[ 'option_value' ]   = $option_value;
    
  }
  
  /**
   * Get preview for this template
   */
  public function get_preview()
  {
  
    $template_id  = $_GET[ 'template' ];
    
    // Generate PDF and it's preview image
    try
    {
    
      $template = Template::get( $template_id );
    
      if ( ! ( $template instanceof TemplateHtml ) )
      {
        // throw new Exception( "Only HTML templates require previews" );
        wp_die();
      }
        
      $entries = $template->form()->entries();
      $export  = \Export2Pdf\Export::create( $template, $entries[ 0 ] );
      
      $export->generate_preview();
      
    }
    catch ( Exception $e )
    {      
      $e->show_and_die();
    }
    
    wp_die();
  
  }
  
  /**
   * Save template option
   */
  public function set_template_option()
  { 
    
    // Set template option
    $template_id = $_GET[ 'template' ];
    $template    = Template::get( $template_id );
    $key         = $_GET['option'];
    $value       = $_GET['value'];
    
    $template->set_option( $key, $value );
    
    // And render new styles
    
    $styles = $template->style( $for_editor = TRUE );
    
    header( 'Content-Type: text/css; charset=utf-8' );
    header( 'Content-Length: ' . strlen( $styles ) );
    echo $styles;
    exit;
    
  }
  
  /**
   * Delete a template
   */
  public function delete()
  {
  
    $template = $this->variables[ 'template' ];
    $template->destroy();
    Tools::redirect(
      $this->action_url()
    );
    
  }
  
  /**
   * Shows the list of PDF files
   */
  public function index()
  {

    $this->set_view_mode();
    
    $templates = Template::all();
    $this->variables[ 'templates' ] = $templates;
    
  }
  
}
