<?php 

  if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
    die();
    
?>

<div class="wrap export2pdf">

  <h1>
    <?php _e( 'Templates', 'export2pdf' ); ?>
    <a href="<?php echo $page_create_template; ?>" class="page-title-action"><span class="glyphicon glyphicon-plus"></span> <?php _e( 'Create a template', 'export2pdf' ); ?></a>
  </h1>
  
  <noscript>
    <div class="error inline">
      <p>
        Seems like JavaScript is disabled. Export2PDF plugin requires JavaScript to upload PDFs. <br />
        Please enable JavaScript and refresh this page.
      </p>
    </div>
  </noscript>
  
  <?php if ( ! count( $templates ) ): ?>
  
    <!-- Message that shows when there are no templates -->
  
    <p>
      The list of your PDF templates will appear here.
    </p>
  
    <div class="error inline">
      <p>
        <?php _e( "You don't have any templates yet.", 'export2pdf' ); ?> <a href="<?php echo $page_create_template; ?>" class="button"><span class="glyphicon glyphicon-plus"></span> <?php _e( 'Create a template', 'export2pdf' ); ?></a>
      </p>
    </div>
    
    <!-- // Message that shows when there are no templates -->
  
  <?php else: ?>
    
    <div class="wp-filter"> 
      <div class="media-toolbar-secondary">
        <div class="view-switch media-grid-view-switch">
		      <a 
		        href="<?php echo $controller->action_url( 'index', array( 'mode' => 'grid' ) ); ?>" 
		        class="view-grid<?php if ( $mode == 'grid' ) echo ' current'; ?>"
	          title="<?php echo esc_attr( __( 'Grid View', 'export2pdf' ) ); ?>"
		      >
		        <span class="screen-reader-text"><?php _e( 'Grid View', 'export2pdf' ); ?></span>
		      </a>
		      <a 
		        href="<?php echo $controller->action_url( 'index', array( 'mode' => 'list' ) ); ?>" 
		        class="view-list<?php if ( $mode == 'list' ) echo ' current'; ?>"
	          title="<?php echo esc_attr( __( 'List View', 'export2pdf' ) ); ?>"
		      >
		        <span class="screen-reader-text"><?php _e( 'List View', 'export2pdf' ); ?></span>
		      </a>
	      </div>
      </div>
    </div>
    
    <?php include __DIR__ . '/index/' . $mode . '.php'; ?>
  
  <?php endif; ?>

</div>

