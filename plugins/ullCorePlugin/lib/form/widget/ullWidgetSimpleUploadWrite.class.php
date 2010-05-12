<?php

class ullWidgetSimpleUploadWrite extends sfWidgetFormInputFile
{
  
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('path');
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $return = '';
    
    $return .= ullWidgetSimpleUploadRead::renderFile($value, $this->getOption('path'), $this->getAttribute('alt'));
    
    $return .= parent::render($name, $value, $attributes, $errors);
    
    return $return;
  }
}


