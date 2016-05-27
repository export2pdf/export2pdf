<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Framework option class
 */
 
class Option extends Model
{
  
  const TABLE = "options";
  
  public $key;
  public $value;

}
