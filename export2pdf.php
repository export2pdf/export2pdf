<?php
/*
Plugin Name: Export2PDF
Plugin URI: http://www.export2pdf.com/
Description: Easily export data to PDF files. Works with WooCommerce, Formidable and WordPress posts.
Version: 1.0.2
Author: Export2PDF
Author URI: http://www.export2pdf.fr/
License: AGPL
License URI: http://www.gnu.org/licenses/agpl-3.0.en.html
Text Domain: export2pdf
*/

if ( ! defined( 'ABSPATH' ) ) 
  die();

define( 'EXPORT2PDF_LOADED', true );

require_once dirname( __FILE__ ) . '/activate.php';
register_activation_hook( __FILE__, 'export2pdf_activate' );  

require_once dirname( __FILE__ ) . '/framework/framework.php';
