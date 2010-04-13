<?php

class ullWidgetSimpleUploadWrite extends ullWidgetFormInput
{
  
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('path');
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $widget = new sfWidgetFormInputFile(array(
      'label' => __('Photo', null, 'common'),
    ));
    
    $return = '';
    
    if ($value !== null)
    {
      $path = str_replace(sfConfig::get('sf_web_dir'), '', $this->getOption('path'));
      
      if (ullCoreTools::isValidImage($this->getOption('path'), $value))
      {
        $return .= '<div class="ull_widget_simple_upload_write_image"><img src="' . $path . '/' . $value . '" /></div>';
      }
      else
      {
        $return .= '<p>'. ull_link_to($value, $path . '/' . $value) .'</p>';
      }
    }
    $return .= $widget->render($name, $value, $attributes, $errors);
   
    return $return;
  }
}


