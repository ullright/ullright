<?php

class ullWidgetDateRead extends ullWidget
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    //ull_format_date renders the current date when $value is NULL
    //but we actually need an empty field
    return ($value) ? ull_format_date($value) : '';
    //return ull_format_date($value);
  } 
}