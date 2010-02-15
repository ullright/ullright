<?php

class ullWidgetTimeDurationRead extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('show_zero');
    $this->addOption('show_negative_red');
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $negativeClass = '';
    if ($value < 0 && $this->getOption('show_negative_red'))
    {
      $negativeClass = ' ull_widget_time_negative';
    }

    if ($value || (($value == 0) && $this->getOption('show_zero')))
    {
      return '<span class="ull_widget_time' . $negativeClass . '">' . 
        ullCoreTools::timeToString($value) .
        '</span>';
    }
    
//    return date('g', $value) . '<sup style="vertical-align: text-top; font-size: .8em;">' . date('i', $value) . '</sup>';
  }
  
}