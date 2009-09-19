<?php

class ullWidgetTimeDurationWrite extends sfWidgetFormInput
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($value && !$errors)
    {
      $value = ullCoreTools::timeToString($value);
    }
    
    $return = parent::render($name, $value, $attributes, $errors);
        
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');
      
//    $return .= javascript_tag('
//$(document).ready(function()
//{
//  $("#' . $id . '").timepickr();
//});
//    ');
          
    return $return;
  } 
}