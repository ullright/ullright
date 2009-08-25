<?php

class ullWidgetFormInput extends sfWidgetFormInput
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('suffix');
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $suffix = $this->getOption('suffix');
    return parent::render($name, $value, $attributes, $errors) . ' ' . $suffix;
  }
}
