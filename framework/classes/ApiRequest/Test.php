<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Test API request. Just to see if we can make API requests
 */

class ApiRequest_Test extends ApiRequest
{
  public $decode_answer = false;
}
