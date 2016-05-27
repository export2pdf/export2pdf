<?php

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Add AJAX actions
 */
 
// Create a preview for a PDF page
add_action( 'wp_ajax_export2pdf_pdf_page_preview', function () {

  $controller = new \Export2Pdf\TemplatesController();
  $controller->render();
  wp_die();

});

// Map a field to form field from PDF designer automatically
add_action( 'wp_ajax_export2pdf_map_field_automatically', function () {

  $controller = new \Export2Pdf\TemplatesController();
  $controller->render();
  wp_die();

});
 
// Window that appears in a popup,
// when editing a field in field map designer
add_action( 'wp_ajax_export2pdf_edit_field', function () {

  $controller = new \Export2Pdf\TemplatesController();
  $controller->render();
  wp_die();

});

// Window that appears in a popup,
// when editing a template option (font size, font color, paper size, ...)
add_action( 'wp_ajax_export2pdf_template_option', function () {

  $controller = new \Export2Pdf\TemplatesController();
  $controller->render();
  wp_die();

});

// Window that appears in a popup,
// when inserting a field in HTML designer
add_action( 'wp_ajax_export2pdf_insert_field', function () {

  $controller = new \Export2Pdf\TemplatesController();
  $controller->render();
  wp_die();

});

// Window that appears in a popup,
// when adding a shortcode using TinyMCE
add_action( 'wp_ajax_export2pdf_shortcode', function () {

  $controller = new \Export2Pdf\ExportController();
  $controller->render();
  wp_die();

});

// Renders styles for an HTML template
add_action( 'wp_ajax_export2pdf_template_styles', function () {

  $controller = new \Export2Pdf\TemplatesController();
  $controller->render();
  wp_die();

});

// Save template option
add_action( 'wp_ajax_export2pdf_set_template_option', function () {

  $controller = new \Export2Pdf\TemplatesController();
  $controller->render();
  wp_die();

});

// Get template preview image (async for user)
add_action( 'wp_ajax_export2pdf_get_preview', function () {

  $controller = new \Export2Pdf\TemplatesController();
  $controller->render();
  wp_die();

});
