<?php

/**
 * If sending an e-mail fails, then log the error
 */
 
add_action( 'wp_mail_failed', function ( $wp_error ) {

  if ( $wp_error instanceof WP_Error )
    export2pdf_log( 'error_mail', $wp_error->get_error_message(), $wp_error );

});
