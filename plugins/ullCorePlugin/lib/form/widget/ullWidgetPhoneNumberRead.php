<?php

class ullWidgetPhoneNumberRead extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('show_local_short_form');
    $this->addOption('click_to_dial', true);
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $id = null;
    
    if (is_array($value))
    {
      $id = $value['id'];
      $value = $value['value'];
    }
    
    $rawNumber = str_replace(' ', '', $value);
    
    if ($this->getOption('show_local_short_form') && $value)
    {
      $parts = explode(' ', $value);
      array_shift($parts);
      $value = '0' . implode(' ', $parts);
    }
    
    $value = $this->handleOptions($value);
    
    if ($this->getOption('click_to_dial') && $value)
    {
      $value = '<a href="tel:' . $rawNumber . '">' . $value . '</a>';
    }    
    
    return $value;
  }
}
