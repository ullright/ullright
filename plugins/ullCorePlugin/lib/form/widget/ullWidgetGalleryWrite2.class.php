<?php

class ullWidgetGalleryWrite2 extends sfWidgetFormTextarea
{
  
  public function __construct($options = array(), $attributes = array())
  {
    $this->addRequiredOption('config');
    
    parent::__construct($options, $attributes);
  }
  
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $attributes['cols'] = 80;
    $attributes['rows'] = 10;
    
    $this->setAttribute('name', $name);
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');   
    
    $field = parent::render($name, $value, $attributes, $errors);
    
    $upload_url = url_for('ullWidget/galleryUpload?' .
      's_config=' . json_encode($this->getOption('config'))        
    );
    
    $preview_url = url_for('ullWidget/galleryPreview');

    $markup = get_partial('ullWidget/gallery', array(
      'id'            => $id,
      'field'         => $field,
      'upload_url'    => $upload_url,
      'preview_url'   => $preview_url,
    ));
    
    return $markup;
  }
  
  
  /** 
   * Render the gallery preview 
   * 
   * @param string $images separated by newlines \n
   * @param boolean $renderUl
   * @return string
   */
  public static function renderPreview($images)
  {
    $return = '';
    
    $images = explode("\n", $images);
    
    foreach ($images as $image)
    {
      // ignore empty lines
      if (trim($image))
      {
        // ignore invalid stuff
        if (file_exists(ullCoreTools::webToAbsolutePath($image)))
        {
          // Check for thumbnails
          $thumbnail = ullCoreTools::calculateThumbnailPath($image);
          $thumbnailAbsolutePath = ullCoreTools::webToAbsolutePath($thumbnail);
          if (!file_exists(ullCoreTools::webToAbsolutePath($thumbnail)))
          {
            $thumbnail = $image;
          }
          
          $return .= '<li>';
          $return .= '<div class="ull_widget_gallery_preview_image_container">';
          $return .= '  <div class="ull_widget_gallery_preview_image">';
          $return .= '    <a href="'. $image . '" target="_blank"><img src="' . $thumbnail .'" alt="preview image" rel="' . $image . '" /></a>';
          $return .= '  </div>';
          $return .= '</div>';
          $return .= '  <div class="ull_widget_gallery_actions">';
          $return .= '    ' . ull_icon('ullPhoto/imageDelete?s_image=' . $image, 'delete');
          $return .= '  </div>';        
          $return .= '</li>';
        }
      }
    }
    
    return $return;
  }
  
  /**
   * Get array of images by the fields's value format
   * 
   * @param string $value
   */
  public static function getImagesAsArray($value)
  {
    $images = str_replace("\r", '', $value);
    $images = explode("\n", $images);
    
    $return = array();
    
    foreach ($images as $image)
    {
      // Ignore blank lines
      if ($image)  
      {
        $return[] = $image;
      }
    }
    
    return $return;
  }
  
  
  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    return array(
      '/ullCorePlugin/js/jq/jquery-min.js',
      '/ullCorePlugin/js/jq/jquery-ui-min.js',
      '/ullCorePlugin/js/plupload/plupload.full.js',
      '/ullCorePlugin/js/ullWidgetGallery2.js',
    );
  }
  
  
  public function getStylesheets()
  {
    return array(
      '/ullCorePlugin/css/jqui/jquery-ui.css' => 'all',
      '/ullCorePlugin/css/ull_gallery.css' => 'all',
    );
  }
  
}

