<?php

class ullWidgetFCKEditorRead extends ullWidget
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $value =  html_entity_decode(parent::render($name, $value, $attributes, $errors));
    
    $value = $this->handleDecodeMimeOption($value);
    
    return $value;
  }
}
