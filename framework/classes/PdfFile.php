<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Prototype for PDF files
 */
 
class PdfFile extends Model
{
  
  const TABLE = "files";
  
  const PDF_TEMPLATE_FILENAME = 'template.pdf';
  
  public $path;
  
  public $pages;
  public $fields;
  public $type = 'PdfFile';
  
  /**
   * Get pages
   */
  public function pages()
  {
    if ( $this->pages )
      return $this->pages;
    
    $this->pages = PdfPage::all( array ( 
      'pdf_file_id' => $this->id(),
    ));
    
    // Sort by page number
    usort( $this->pages, function ( $page1, $page2 ) {
    
      return ( $page1->number > $page2->number ? 1 : -1 );
    
    });
    
    return $this->pages;
  }
  
  /**
   * Get a page by its number
   *
   * @param int $number Page number
   * @return PdfPage Corresponding PDF page
   */
  public function page( $number )
  {
  
    foreach ( $this->pages() as $page )
      if ( $page->number() == $number )
        return $page;
    
    throw new Exception( "Page $number not found" );
    
  }
  
  /**
   * Get fields
   */
  public function fields()
  {
    if ( $this->fields )
      return $this->fields;
    
    $this->fields = PdfField::all( array ( 
      'pdf_file_id' => $this->id(),
    ));
    
    return $this->fields;
  }
  
  
  /**
   * Get a field
   *
   * @return PdfField Field
   */
  public function field( $id )
  {
  
    foreach ( $this->fields() as $field )
      if ( $field->id() == $id )
        return $field;
    
    throw new Exception( 'Could not find field with ID ' . $id );
    
  }
  
  /**
   * Path to template file
   */
  public function path()
  {
    if ( $this->path )
      return $this->path;
      
    $path = Framework::pdf_data_path() . $this->id();
    Tools::mkdir( $path );
    $path .= DIRECTORY_SEPARATOR . static::PDF_TEMPLATE_FILENAME;
    $this->path = $path;
    
    return $this->path;
    
  }
  
  /**
   * Get number of pages in this file
   */
  public function number_of_pages()
  {
    return count( $this->pages() );
  }
  
  /**
   * URL Path to tempalte file
   */
  public function url()
  {      
    $url = Framework::pdf_data_url() . $this->id();
    $url .= '/' . self::PDF_TEMPLATE_FILENAME;
    return $url;
  }
  
  /**
   * Get MD5 hash of this PDF
   *
   * @return string MD5 hash of the template file
   */
  public function hash()
  {
  
    if ( file_exists( $this->path() ) )
      return md5_file( $this->path() );
      
    return "UNEXISTING FILE";
    
  }
  
  /**
   * Get filename of this PDF
   */
  public function name()
  {
    return $this->name;
  }
  
  /**
   * Get a page by its number
   * 
   * @param $page_number int Page number starting from 1
   *
   * @return PdfPage PDF page object
   */
  public function get_page( $page_number )
  {
  
    foreach ( $this->pages() as $page )
      if ( $page->number() == $page_number )
        return $page;
        
    throw new Exception( "Page $page_number not found for this template.");
    
  }
  
  /**
   * Get PDF field position information
   * (page number, X and Y coordinates relative to that page)
   */
  public function process_info()
  {
  
    Progress::step( 'Getting information about fields...', 5, 95 );
  
    $json_output = ApiRequest_PdfInfo::perform( $this->path() );
    
    /*
    if ( ! isset( $json_output->pages ) )
      throw new Exception( "PDF file doesn't have any pages." );    
      
    if ( ! isset( $json_output->fields ) )
      throw new Exception( "PDF file doesn't have any fields." );
    */
    
    if ( isset( $json_output->error ) )
    {
    
      $processing_error = $json_output->error;
      
      // TODO: Better formatting for encrypted PDFs
      // instead of: Cannot decrypt PDF, the password is incorrect
      // if ( preg_match( '/the password is incorrect/', $processing_error ) )
      //   throw new Exception( "Uploading encrypted PDF files is not supported. Please remove PDF password and re-upload the file.");
      
      throw new Exception( "There was an error while getting information about this PDF: " . $processing_error );
      
    }
    
    // Get information from JSON
    $pages_array  = $json_output->pages;
    $fields_array = $json_output->fields;
    
    // Create pages
    $this->pages = array();
    foreach ( $pages_array as $page_array )
    {
    
      $page = new PdfPage();
      $page->pdf_file_id = $this->id();
      
      $page->number      = $page_array->number;
      $page->width       = $page_array->width;
      $page->height      = $page_array->height;
      
      $this->pages[] = $page;
        
    }
    
    // Create fields
    $this->fields = array();
    foreach ( $fields_array as $field_array )
    {
    
      $field = new PdfField();
      $field->pdf_file_id = $this->id();
      
      $field->page        = $this->get_page( $field_array->page );
      $field->x           = $field_array->x;
      $field->y           = $field_array->y;
      $field->width       = $field_array->width;
      $field->height      = $field_array->height;
      $field->name        = $field_array->name;
      $field->type        = $field_array->type;
      $field->options     = $field_array;
      
      $this->fields[] = $field;
        
    }
    
    if ( ! count( $this->pages ) )
      throw new Exception( 'This PDF file does not have any pages.' );
      
    if ( ! count( $this->fields ) )
      throw new Exception( 'This PDF file does not have any form fields. You can add fields using <a href="https://www.pdfescape.com/" target="_blank">pdfescape.com</a>.' );
    
    $pages_count = count( $this->pages );
    $pages_count_text = sprintf( _n( '%s page', '%s pages', $pages_count), $pages_count, 'export2pdf' ); 
    
    $fields_count = count( $this->fields );
    $fields_count_text = sprintf( _n( '%s field', '%s fields', $fields_count), $fields_count, 'export2pdf' ); 
    
    Progress::step( 'File contains ' . $pages_count_text . ' and ' .  $fields_count_text . '.', 95 );
    
  }
  
  /**
   * Generate page previews
   */
  public function process_pages_previews()
  {
    
    foreach ( $this->pages() as $page )
      $page->generate_preview();
    
  }
  
  /**
   * Process this PDF file
   */
  public function process()
  {
  
    // Clear up existing data
    foreach ( $this->pages() as $page )
      $page->destroy();
    foreach ( $this->fields() as $field )
      $field->destroy();
    
    // Get PDF data
    $this->process_info();
    
    // Save pages and fields
    foreach ( $this->pages as $page )
      $page->save();
    foreach ( $this->fields as $field )
      $field->save();
      
    // Generate page previews
    // Transferred this function to PDF designer
    // $this->process_pages_previews();
      
    // Reset the arrays so that the fresh data is taken from the database
    $this->pages = null;
    $this->pages();

    $this->fields = null;
    $this->fields();
    
    Progress::step( 'File has been successfully added.', 100 );
    
  }
  
  /**
   * Delete all data
   */
  public function destroy()
  {
    // Delete file information in MySQL table
    foreach ( $this->pages() as $page )
      $page->destroy();
    foreach ( $this->fields() as $field )
      $field->destroy();
    
    // Clean up files
    try
    {
      @unlink( $this->path() );
      @rmdir( dirname( $this->path() ) ); // TODO: delete all files in this folder before rmdir()
    }
    catch ( Exception $e )
    {
    }
    
    // Call parent methid to destroy this MySQL entry
    parent::destroy();
  }
  
  /**
   * Get binary data of the file
   */
  public function content()
  {
    return @file_get_contents( $this->path() );
  }
  
  /**
   * Set binary data of the file (write to disk)
   */
  public function set_content( $binary_data = '' )
  {    

    $result = @file_put_contents( $this->path(), $binary_data );
    if ( ( $result === FALSE ) or ! file_exists( $this->path() ) )
      throw new Exception( 'Saving file ' . $this->path() . ' failed.' );
    
  }
  
  /** 
   * Take this file and save it to the database
   */
  public function save_to_database()
  {
    // Create an entry in the database
    $this->hash = $this->hash();
    $this->name = preg_replace( '/\.pdf$/i', '', basename( $this->path() ) );
    
    $pdf_path = $this->path();
    
    /*
     * TODO:
     * It's too complicated and produces too many errors
     *
     
        // Try to look up the database to see if we have this file already
        $existing_file = Db::selectOne( 
          self::TABLE,
          array(
            'hash' => $this->hash,
            'name' => $this->name,
          )
        );
        
        if ( $existing_file )
        {
          // Found a match with $name and $hash in the database
          try
          {
            // Try to load existing file data
            $this->path = false;
            $this->__construct( $existing_file->id );
            // Get PDF file data and save it
            $this->process();
            $this->save();
            return;
          }
          catch ( Exception $e )
          {
            // An error occured. Try to re-create the file
            // throw $e;
          }
        }
    
     *
     */
    
    // Save to the database
    $this->id = false;
    $this->save();
    
    // Copy file to our folder
    $this->path = false;
    Tools::copy( $pdf_path, $this->path() );
    
    // Get PDF file data
    $this->process();
  }

}
