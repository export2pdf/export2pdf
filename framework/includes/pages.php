<?php

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
  
/**
 * Callbacks for WordPress menus
 */
 
function export2pdf_page_export()
{
  $controller = new \Export2Pdf\ExportController();
  $controller->render();
}

function export2pdf_page_templates()
{
  $controller = new \Export2Pdf\TemplatesController();
  $controller->render();
}

function export2pdf_page_settings()
{
  $controller = new \Export2Pdf\SettingsController();
  $controller->render();
}

function export2pdf_under_the_hood()
{
  $controller = new \Export2Pdf\DebugController();
  $controller->render();
}

function export2pdf_translations()
{
  $controller = new \Export2Pdf\TranslationsController();
  $controller->render();
}
