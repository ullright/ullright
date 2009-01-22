<?php

class ullWidgetDateTimeRead extends ullWidget
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return ull_format_datetime($value);
  } 
}