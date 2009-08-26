<?php

class ullWidgetFloatWrite extends ullWidgetFormInput
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (!(get_class($errors) === 'sfValidatorError') && ($value != ''))
    {
      $value = ullMetaWidgetFloat::formatNumber($value);
    }
    
    return parent::render($name, $value, $attributes, $errors);
  }
}


