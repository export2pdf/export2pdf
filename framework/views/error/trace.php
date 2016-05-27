<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
  if ( ! \Export2Pdf\Debug::enabled() )
    return;
    
  $traces = $this->getTrace();
  //print_r( $traces );
  
  if ( ! count( $traces ) )
    return;
    
  echo '<div class="traces" id="traces">';
  
  echo '<p><a href="#traces">Show trace log</a></p>';
  
  foreach ( $traces as $trace ):
  
    if ( 
         ! isset( $trace[ 'file' ] )
      or ! isset( $trace[ 'line' ] )
    )
    {
      continue;
    } 
  
    $file_path = $trace[ 'file' ];
    $file_path = str_replace( \Export2Pdf\Framework::path(), "/", $file_path );
    $file_path = str_replace( ABSPATH, "/", $file_path );
    
    $line      = $trace[ 'line' ];
    
    $function  = $trace[ 'function' ];
    
    // Get called class, if it was a class
    $class = false;
    if ( isset( $trace[ 'class' ] ) )
    {
      $class = $trace[ 'class' ];
      $class = str_replace( "Export2Pdf\\", "", $class );
    }
    
    // Get code preview of this PHP file
    $code_preview = false;
    if ( $line >= 0 )
    if ( $php_file = @file_get_contents( $trace[ 'file' ] ) )
    {
      
      // Extract preview lines
      $lines_to_display = 3;
      $starting_line    = $line - $lines_to_display - 1;
      $ending_line      = $line + $lines_to_display - 1;
      
      $all_php_lines        = explode( "\n", $php_file );
      
      if ( $starting_line < 0 )
        $starting_line = 0;
      if ( $ending_line >= count( $all_php_lines ) )
        $ending_line = count( $all_php_lines ) - 1;
      if ( $ending_line < 0 )
        $ending_line = 0;
        
      // Find lines to show
      $php_lines = array();
      foreach ( $all_php_lines as $line_index => $php_line )
      {
      
        if ( $line_index > $ending_line )
          continue;              
        if ( $line_index < $starting_line )
          continue;
      
        $is_current = ( $line_index == $line - 1 );
      
        $php_line = self::highlight_php( $php_line, $is_current );
        $php_lines[] = $php_line;
        
      }
      $php_lines = implode( "<br />", $php_lines );
      
      $code_preview = $php_lines;
      
    }
    
    // Get function arguments
    $args = array();
    if ( isset( $trace[ 'args' ] ) )
    {
      foreach ( $trace[ 'args' ] as $argument_number => $argument_value )
      {
        $args[ $argument_number ] = $argument_value;
      }
    }
  
    // Get link to WordPress plugin editor
    $editor = false;
    if ( strpos( $trace[ 'file' ], WP_CONTENT_DIR ) === 0 )
    {
    
      $relative_path = str_replace( WP_CONTENT_DIR . '/plugins/' , '', $trace[ 'file' ] );  
      
      $editor_params = array(
        'file'     => $relative_path,
        'scrollto' => $trace['line'] - 1,
      );
      $editor = admin_url( 'plugin-editor.php' ) . '?' . http_build_query( $editor_params );
      
    }
  
    ?>
    
      <div class="trace">
        
        <!-- file and line -->
        
        In file
        
        <?php if ( $editor ): ?>
        
          <a href="<?php echo $editor; ?>" target="_blank"><?php echo $file_path; ?></a>
          
        <?php else: echo $file_path; endif; ?>
        
        on line <?php echo $line; ?>,
        
        <!-- class and function -->
        
        function <code><?php 
        
          if ( $class )
            echo $class . "::";
          echo $function;
          
          echo "()"; 
          
        ?></code>
        
        <!-- arguments -->
        
        <?php if ( count( $args ) ): ?>
        
          <br />
        
          with arguments:
          
          <?php foreach ( $args as $argument => $argument_value ): ?>

            <?php
              
              if ( is_object( $argument_value ) or is_array( $argument_value ) )
                $argument_value = print_r( $argument_value, true );
               
              $maximum_length = 50; 
              $argument_value_formatted = htmlspecialchars( $argument_value );
              if ( strlen( $argument_value ) > $maximum_length )
              {
                $argument_value_formatted = htmlspecialchars( substr( $argument_value, 0, $maximum_length ) ) . "...";
              }
              
              echo '<br /><code>' . $argument_value_formatted . '</code>';
              
            ?>

          <?php endforeach; ?>               
        
        <?php endif; ?>
        
        <!-- php code -->
        
        <?php if ( $code_preview ): ?>
          
          <code class="preview"><?php echo $code_preview; ?></code>  
        
        <?php endif; ?>
        
        <!-- end of trace -->
        
      </div>
    
    <?php
  
  endforeach;
  
  echo '</div>';


