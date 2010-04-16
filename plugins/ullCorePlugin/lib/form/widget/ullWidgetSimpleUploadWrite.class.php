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
    
    return 
      ullWidgetSimpleUploadRead::renderFile($value, $this->getOption('path')) .
      $widget->render($name, $value, $attributes, $errors);
  }
}


