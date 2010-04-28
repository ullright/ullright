<?php

class ullWidgetSimpleUploadRead extends ullWidget
{
  
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('path');
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    //$widget = new sfWidgetForm(array(
      //'label' => __('Photo', null, 'common'),
    //));
    
    return self::renderFile($value, $this->getOption('path'), $this->getAttribute('alt'));
  }
  
  /**
   * Renders a file. An image will be rendered with an image-tag. Other content gets a link to the source
   * 
   * @param string $value
   * @param string $path
   * @param string $alt
   * @return string
   */
  public static function renderFile($value, $path, $alt = 'image')
  {
    $return = '';
    
    if ($value != null)
    {
      $filePath = str_replace(sfConfig::get('sf_web_dir'), '', $path);
      
      if (ullCoreTools::isValidImage($path, $value))
      {
        $return .= '<div class="ull_widget_simple_upload_write_image"><img src="' . $filePath . '/' . $value . '" alt="' . $alt . '"/></div>';
      }
      else
      {
        $return .= '<p>'. ull_link_to($value, $filePath . '/' . $value) .'</p>';
      }
    }
    
    return $return;
  }
}


