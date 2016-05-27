<?php

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

$addons_path = $framework_path . '/addons/';
$addon_folders = \Export2Pdf\Tools::files_in_folder( $addons_path );
foreach ( $addon_folders as $addon_folder )
{
  $addon_file = $addon_folder . '/' . basename( $addon_folder ) . '.php';
  if ( file_exists( $addon_file ) )
    require_once $addon_file;
}
