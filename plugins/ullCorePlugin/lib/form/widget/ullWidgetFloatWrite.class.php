<?php

class ullWidgetFloatWrite extends ullWidgetFormInput
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    //TODO: why is $errors expected to be an object?  (throws an error in ullWidgetFloatWriteTest
    if (!(@get_class($errors) === 'sfValidatorError') && ($value != ''))
    {
      $value = ullMetaWidgetFloat::formatNumber($value);
    }
    
    return parent::render($name, $value, $attributes, $errors);
  }
}


