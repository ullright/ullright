<?php

class ullWidgetTimeDurationRead extends ullWidget
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($value)
    {
      return ullCoreTools::timeToString($value);
    } 
//    return date('g', $value) . '<sup style="vertical-align: text-top; font-size: .8em;">' . date('i', $value) . '</sup>';
  }
  
}