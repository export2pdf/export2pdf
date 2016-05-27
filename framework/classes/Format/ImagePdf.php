<?php
 
namespace Export2Pdf;

if ( ! defined( 'EXPORT2PDF_LOADED' ) ) 
  die();
 
/**
 * Replaces a PDF field with an image
 */
 
class Format_ImagePdf extends Format
{
  
  public $name  = 'Image';
  public $group = 'Media';
  
  public function default_options()
  {
    return array(
      'image_vertical_alignment'   => 'center',
      'image_horizontal_alignment' => 'center',
    );
  }
  
  public function visible()
  {
    return ( $this->template and ! ( $this->template instanceof TemplateHtml ) );
  }
  
  public function process( $value, $options = array() )
  {
    
    $value = Tools::trim( $value );
    
    // If <img /> tag in HTML format is supplied as $value, then extract src attribute
    if ( preg_match( '/<img.*?src=["\']{1}([^"\']+)["\']{1}/', $value, $matches ) )
      $value = $matches[ 1 ];
    
    return $value;
    
  }
  
  public function show_options( $map = NULL )
  {
    
    ob_start();
    
    $alignments = array(
      'image_vertical_alignment' => array(
        'top'    => 'Top',
        'center' => 'Center',
        'bottom' => 'Bottom',
      ),
      'image_horizontal_alignment' => array(
        'left'   => 'Left',
        'center' => 'Center',
        'right'  => 'Right',
      ),
    );
    
    ?>
    
      <tr class="export2pdf-additional-options">
        
        <th scope="row">
          <label for="map_formating">
            Vertical Alignment
          </label>
        </th>
        
        <td>
          
          <select 
            class="regular-text" 
            name="options[image_vertical_alignment]">
            
            <?php foreach ( $alignments[ 'image_vertical_alignment' ] as $option_key => $option_name ): ?>
            
              <option
                value="<?php echo esc_attr( $option_key ); ?>"
                <?php if ( $map->option( 'image_vertical_alignment' ) == $option_key ) echo ' selected="selected"'; ?>
                ><?php echo $option_name; ?></option>
            
            <?php endforeach; ?>
            
          </select>
          
        </td>
        
      </tr>
      
      <tr class="export2pdf-additional-options">
        
        <th scope="row">
          <label for="map_formating">
            Horizontal Alignment
          </label>
        </th>
        
        <td>
          
          <select 
            class="regular-text" 
            name="options[image_horizontal_alignment]">
            
            <?php foreach ( $alignments[ 'image_horizontal_alignment' ] as $option_key => $option_name ): ?>
            
              <option
                value="<?php echo esc_attr( $option_key ); ?>"
                <?php if ( $map->option( 'image_horizontal_alignment' ) == $option_key ) echo ' selected="selected"'; ?>
                ><?php echo $option_name; ?></option>
            
            <?php endforeach; ?>
            
          </select>
          
        </td>
        
      </tr>
    
    <?php
    
    return ob_get_clean();
    
  }
  
}


