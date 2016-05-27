<?php

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Add a menu item to WordPress dashboard
 */
 
add_action( 'admin_menu', function () {

  // Main manu item
  $hook_suffix = add_menu_page(
    __( 'Export2PDF', 'export2pdf' ),                               // Title
    __( 'Export2PDF', 'export2pdf' ),                               // Menu Title
    'administrator',                                                // Permissions
    \Export2Pdf\ExportController::PAGE,                             // Page slug
    'export2pdf_page_export',                                       // Callback  
    \Export2Pdf\Framework::assets_url() . 'images/icon.png',        // Icon
    20                                                              // Position: before Pages
  );
  add_action( 'load-' . $hook_suffix, array( "\\Export2Pdf\\ExportController", "add_assets" ) );
  
  // Main manu item
  $hook_suffix = add_submenu_page(
    \Export2Pdf\ExportController::PAGE,                             // Slug
    __( 'Export to PDF', 'export2pdf' ),                            // Title
    __( 'Export to PDF', 'export2pdf' ),                            // Menu Title
    'administrator',                                                // Permissions
    \Export2Pdf\ExportController::PAGE,                             // Page slug
    'export2pdf_page_export'                                        // Callback 
  );
  add_action( 'load-' . $hook_suffix, array( "\\Export2Pdf\\ExportController", "add_assets" ) );
  
  // Main manu item -> Templates
  $hook_suffix = add_submenu_page(
    'export2pdf',                                                   // Parent item slug
    __( 'Templates', 'export2pdf' ),                                // Page title
    __( 'Templates', 'export2pdf' ),                                // Menu title
    'administrator',                                                // Permissions
    \Export2Pdf\TemplatesController::PAGE,                          // Slug
    'export2pdf_page_templates'                                     // Callback
  );
  add_action( 'load-' . $hook_suffix, array( "\\Export2Pdf\\TemplatesController", "add_assets" ) );
  
  // Main manu item -> Settings
  $hook_suffix = add_submenu_page(
    'export2pdf',                                                   // Parent item slug
    __( 'Settings', 'export2pdf' ),                                 // Page title
    __( 'Settings', 'export2pdf' ),                                 // Menu title
    'administrator',                                                // Permissions
    \Export2Pdf\SettingsController::PAGE,                           // Slug
    'export2pdf_page_settings'                                      // Callback
  );
  add_action( 'load-' . $hook_suffix, array( "\\Export2Pdf\\SettingsController", "add_assets" ) );
  
  if ( \Export2Pdf\Translation::enabled() ) 
  {
  
    // Count all new translations
    $count = \Export2Pdf\Translation::count(array(
      'is_new'   => 1,
      'language' => \Export2Pdf\Translation::current_language(),
    ));
    
    $badge = '';
    if ( $count > 0 )
      $badge = ' <span class="update-plugins count-' . $count . '"><span class="plugin-count">' . $count . '</span></span>';
  
    // Main manu item -> Under the Hood
    $hook_suffix = add_submenu_page(
      'export2pdf',                                 // Parent item slug
      __( 'Translations', 'export2pdf' ),           // Page title
      __( 'Translations', 'export2pdf' ) . $badge,  // Menu title
      'administrator',                              // Permissions
      \Export2Pdf\TranslationsController::PAGE,     // Slug
      'export2pdf_translations'                     // Callback
    );
    add_action( 'load-' . $hook_suffix, array( "\\Export2Pdf\\TranslationsController", "add_assets" ) );
  
  }
  
  if ( \Export2Pdf\Debug::enabled() ) 
  {
  
    // Main manu item -> Under the Hood
    $hook_suffix = add_submenu_page(
      'export2pdf',                          // Parent item slug
      __( 'Under the Hood', 'export2pdf' ),  // Page title
      __( 'Under the Hood', 'export2pdf' ),  // Menu title
      'administrator',                       // Permissions
      \Export2Pdf\DebugController::PAGE,     // Slug
      'export2pdf_under_the_hood'            // Callback
    );
    add_action( 'load-' . $hook_suffix, array( "\\Export2Pdf\\DebugController", "add_assets" ) );
  
  }
  
  /*
  // Main manu item -> Import/Export
  add_submenu_page(
    'export2pdf',                // Parent item slug
    'Import/Export',             // Page title
    'Import/Export',             // Menu title
    'administrator',             // Permissions
    'export2pdf-import-export',  // Slug
    'export2pdf'                 // Callback
  );
  */
  
  /*
  // Main manu item -> Backups
  add_submenu_page(
    'export2pdf',                // Parent item slug
    'Backups',                   // Page title
    'Backups',                   // Menu title
    'administrator',             // Permissions
    'export2pdf-backups',        // Slug
    'export2pdf'                 // Callback
  );
  */
  
});
