<?php

class ullWidgetFloatRead extends ullWidget
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $value = ullMetaWidgetFloat::formatNumber($value);
    
    return parent::render($name, $value, $attributes, $errors);
  } 
}