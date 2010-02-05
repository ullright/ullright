<?php

class ullWidgetTimeDurationRead extends ullWidget
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $negativeClass = '';
    if ($value <0 && isset($attributes['show_negative_red']) && $attributes['show_negative_red'])
    {
      $negativeClass = ' ull_widget_time_negative';
    }

    if ($value || (($value == 0) && isset($attributes['show_zero']) && $attributes['show_zero']))
    {
      return '<span class="ull_widget_time' . $negativeClass . '">' . 
        ullCoreTools::timeToString($value) .
        '</span>';
    }
    
//    return date('g', $value) . '<sup style="vertical-align: text-top; font-size: .8em;">' . date('i', $value) . '</sup>';
  }
  
}