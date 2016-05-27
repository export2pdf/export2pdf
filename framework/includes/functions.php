<?php

/**
 * Functions that can be used by client or any other plugin
 */
 
/**
 * Export a template into a PDF file
 *
 * @param $template Template Template to export from
 * @param $entry FormEntry Entry that will be exported
 *
 * @return string Path to PDF file
 */
function export2pdf_export_template( $template, $entry )
{

  // Initialize the export
  $export  = \Export2Pdf\Export::create( $template, $entry );
  
  // Prepare the file
  $path = $export->path();
  
  // Check if file was generated
  if ( ! $path )
    throw new \Export2Pdf\Exception( "PDF path wasn't specified." );
  if ( ! file_exists( $path ) )
    throw new \Export2Pdf\Exception( "PDF wasn't generated." );
    
  // We should generate a pretty file name here
  $temporary_folder = new \Export2Pdf\TempFolder();
  $new_temporary_path = $temporary_folder->path() . $export->filename();
  @copy( $path, $new_temporary_path );
    
  return $new_temporary_path;

}

/**
 * Get a link to download a file
 *
 * @param $template Template Template to be used
 * @param $entry Entry that will be exported
 */
function export2pdf_download_link( $template, $entry )
{
  return \Export2Pdf\ShortcodeExport::generate_link( $template, $entry );
}
