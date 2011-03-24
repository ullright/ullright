<?php

class ullWidgetDateTimeRead extends ullWidget
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (!$value)
    {
      return '';
    }
    
    $value = ull_format_datetime($value);
    
    $value = $this->encloseInSpanTag($value, $attributes);
    
    return $value;
  } 
}