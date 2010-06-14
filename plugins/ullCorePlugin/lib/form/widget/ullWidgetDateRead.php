<?php

class ullWidgetDateRead extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('show_weekday', false);
    $this->addOption('show_only_year', false);
    
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    //ull_format_date renders the current date when $value is NULL
    //but we actually need an empty field
    
    if (!$value)
    {
      return '';
    }
    
    if ($this->getOption('show_only_year'))
    {
      $value = date('Y', strtotime($value));
    }
    else
    {
      $value = ull_format_date($value, true, $this->getOption('show_weekday'));
    }
    
    return $value;
  } 
}