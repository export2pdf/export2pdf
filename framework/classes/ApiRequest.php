<?php

namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();

/**
 * Makes requests to Export2PDF API
 */

class ApiRequest
{
  
  public $request_data  = array();
  public $arguments     = array();
  
  public $user_agent    = 'Export2PDF';
  public $method        = 'POST';
  public $timeout       = 60;
  
  public $decode_answer = true;
  
  public static $progress_function;
  
  /**
   * POST request will be sent to this URL
   *
   * @return string API URL
   */
  public function url()
  {
    
    // Extrat last part of the class name, which goes after "_"
    $method = get_class( $this );
    $method = str_replace( "Export2Pdf\\ApiRequest_", "", $method );
    
    return Api::ENDPOINT . $method . "?key=" . Api::key();
  }
  
  /** 
   * Set a request option
   */
  public function set( $option_name, $option_value )
  {
    
    if ( 
         preg_match( '/\_file$/', $option_name )
      && ! preg_match( '/^hash\:/', $option_value )  
    )
    {
    
      // This is a file
      if ( ! file_exists( $option_value ) )
        throw new Exception( "File doesn't exist: $option_value" );
      
      $file_data = file_get_contents( $option_value );
      $file_data = base64_encode( $file_data );
      
      $this->request_data[ $option_name ] = $file_data;
      
    }
    else
    {
    
      // This is not a file
      $this->request_data[ $option_name ] = $option_value;
    
    }
    
  }
  
  /**
   * Perform a request
   */
  public static function perform()
  {
  
    $argument_values = func_get_args();
  
    // Create a new request based on called class
    $request_class = get_called_class();
    $request = new $request_class();
    
    // Check if offline method exists
    // If it does, then we don't need API
    if ( method_exists( $request, "perform_offline" ) )
    {
    
      $result = call_user_func_array( 
        array( $request_class, "perform_offline" ),
        $argument_values
      ); 
      
      return $result;
      
    }
    
    // Set up its variables
    foreach ( $request->arguments as $argument_index => $argument )
    {
      $argument_value = $argument_values[ $argument_index ];
      $request->set( $argument, $argument_value );
    }
    
    // And submit it
    return $request->submit();
  }
  
  public function submit()
  {
  
    if ( function_exists( "curl_init" ) )
    {
      // If Curl is available, let's use it
      // Mainly, because Curl support progress indicators
      $response = $this->submit_using_curl();
    }
    else
    {
      // If not, use standard WP function
      $response = $this->submit_using_wordpress();
    }
    
    $response_code = $response[ 'code' ];
    $response_body = $response[ 'body' ];
    
    if ( ! $response_code )
        throw new Exception( "API did not send any response code." );
    
    // If reponse code is 500, and JSON is supplied, probably this is a readable error
    if ( $response_code == 500 and preg_match( '/^{"error"\:"/', $response_body ) )
    {
      $decoded_error = @json_decode( $response_body );
      if ( $decoded_error and isset( $decoded_error->error ) )
      {
      
        $error_message = $decoded_error->error;
        $error_class   = "\\ExportPdf\\Api_Error";
        
        // Set appropriate error class based on server's response
        if ( 
              isset( $decoded_error->error_type ) 
          and $decoded_error->error_type
          and class_exists( $decoded_error->error_type )
        )
        {
          $error_type = $decoded_error->error_type;
          $error_type = str_replace( "Export2Pdf\\", "", $error_type );
          $error_type = "Export2Pdf\\" . $error_type;
          if ( class_exists( $error_type ) )
            $error_class = $error_type;
        }
        
        throw new $error_class( $error_message );
        
      }
    }
    
    // Check if HTTP response code is not 200 (OK)
    
    $temporarily_not_available_codes = array( 500, 404 );
    
    if ( in_array( $response_code, $temporarily_not_available_codes ) )
      throw new Exception( "PDF generation API is not available at this moment. Please try again in a few minutes." );

    if ( $response_code != 200 )
      throw new Exception( "PDF generation API returned a bad response code: $response_code" );

    // Check if all response parameters are set
       
    if ( ! $response_body )
      throw new Exception( "API did not send any response body." );

    // If we don't need JSON, then return raw API response
    if ( ! $this->decode_answer )
      return $response_body;
      
    $json_output = @json_decode( $response_body );
      
    if ( ! $json_output )
      throw new Exception( "API returned a bad response: <code>$response_body</code>" );
      
    return $json_output;
    
  }
  
  /**
   * Submit a request using wp_remote_post
   *
   * @see https://codex.wordpress.org/Function_Reference/wp_remote_post
   */
  public function submit_using_wordpress()
  {
    
    $parameters = array(
    
      'method'        => $this->method,
      'timeout'       => $this->timeout,
      'body'          => $this->request_data,
      
      'headers'       => array(  
         'User-Agent' => $this->user_agent,
      ),
      
    );
  
    $api_response = wp_remote_post( 
      $this->url(),
      $parameters
    );
    
    if ( is_wp_error( $api_response ) )
      throw new Exception( 
        "Export2PDF wasn't able to perform an API request from your server. " . 
        $api_response->get_error_message() 
      );
      
    /**
     * $api_response has format: 
     *
     * array(5) {
     *   ["headers"]=>
     *   array(5) {
     *     ["date"]=>
     *     string(29) "Sat, 16 Apr 2016 18:06:41 GMT"
     *     ["server"]=>
     *     string(22) "Apache/2.4.12 (Ubuntu)"
     *     ["content-length"]=>
     *     string(3) "345"
     *     ["connection"]=>
     *     string(5) "close"
     *     ["content-type"]=>
     *     string(29) "text/html; charset=iso-8859-1"
     *   }
     *   ["body"]=>"BODY OF RESPONSE"
     *   ["response"]=>
     *   array(2) {
     *     ["code"]=>
     *     int(404)
     *     ["message"]=>
     *     string(9) "Not Found"
     *   }
     *   ["cookies"]=>
     *   array(0) {
     *   }
     *   ["filename"]=>
     *   NULL
     * }
     */
       
    $response_code = wp_remote_retrieve_response_code( $api_response );
    $response_body = wp_remote_retrieve_body( $api_respose );
        
    return array(
      "code" => $response_code,
      "body" => $response_body,
    );
    
  }
  
  /**
   * Submit a request using cURL
   */
  public function submit_using_curl()
  {
    
    // Initialize curl
    $curl = curl_init();
    
    // Set request options
    curl_setopt( $curl, CURLOPT_POST, 1 );
    curl_setopt( $curl, CURLOPT_URL, $this->url() );
    curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query( $this->request_data ) );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, $this->timeout ); 
    curl_setopt( $curl, CURLOPT_TIMEOUT, $this->timeout ); 
    curl_setopt( $curl, CURLOPT_USERAGENT, $this->user_agent ); 
    curl_setopt( $curl, CURLOPT_NOPROGRESS, false );
    curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, 1 );
    curl_setopt( $curl, CURLOPT_HEADER, 0);
    
    // curl_setopt( $curl, CURLOPT_VERBOSE, true );
    // curl_setopt( $curl, CURLOPT_STDERR, fopen( ABSPATH . '/debug.txt', "w+") );
    // curl_setopt( $curl, CURLOPT_HTTPHEADER, array("Content-Type: multipart/form-data") );
    
    $progress_step = Progress::get()->step;
    
    Progress::pulsate();
    
    // Buffered downloads/uploads
    curl_setopt( $curl, CURLOPT_BUFFERSIZE, 1024 );
    curl_setopt( $curl, CURLOPT_PROGRESSFUNCTION, function ( $resource, $download_size, $downloaded, $upload_size, $uploaded ) use ( $progress_step ) { 
    
      // Get coefficients of upload and download
      $koefficient_upload   = 0.5;
      $koefficient_download = 0.5;
      
      if ( $upload_size > 1024 * 100 )
      { 
      
        // Upload size is more than 100 KB
        $koefficient_upload   = 0.8;
        $koefficient_download = 0.2;
        
      }
    
      // Set current progress 
      $progress = 0;
      if ( $upload_size )
        $progress += $uploaded / $upload_size * 100.0 * $koefficient_upload;
      if ( $download_size )
        $progress += $downloaded / $download_size * 100.0 * $koefficient_download;
        
      Progress::set( $progress );
      
      if ( 
           ( $upload_size > 0 )
        && ( $upload_size == $uploaded )
        && ! $download_size
      )
      {
        Progress::pulsate();
      }
      
    });
    
    $response_body = curl_exec( $curl );
    
    if ( $response_body === FALSE )
    {
      $error = curl_error( $curl );
      throw new Exception( $error );
    }
    
    $response_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
    
    Progress::set( 100 );
    
    curl_close( $curl );
    
    return array(
      "code" => $response_code,
      "body" => $response_body,
    );
    
  }

}
