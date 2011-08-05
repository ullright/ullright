<?php

class ullWidgetDateTimeRead extends ullWidget
{
  
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('zero_padding', true);
    $this->addOption('show_seconds', true);
    
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (!$value)
    {
      return '';
    }
    
    $value = ull_format_datetime(
      $value, 
      $this->getOption('zero_padding'), 
      $this->getOption('show_seconds')
    );
    
    $value = $this->encloseInSpanTag($value, $attributes);
    
    return $value;
  } 
}