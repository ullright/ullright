<?php

class ullWidgetTimeRead extends ullWidget
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($value)
    {
      return ullCoreTools::isoTimeToHumanTime($value);
    } 
  }
  
}