<?php

class ullWidgetGalleryWrite extends sfWidgetFormTextarea
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
  public static function renderPreview($string)
  {
    $images = self::getImagesAsArray($string);
    
    sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
    
    $return = get_partial('ullWidget/galleryPreview', array(
      'images'        => $images,
    ));
    
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
      if (trim($image))  
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
      '/ullCorePlugin/js/ullWidgetGallery.js',
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

