<?php

class ullWidgetTimeWrite extends sfWidgetFormInput
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');
    
    $return = '';
    
    $return .= javascript_tag('
$(document).ready(function()
{
  $("#' . $id . '").replaceTimeDurationSelect();
});
    ');
    
    if ($value && !$errors)
    {
      $value = ullCoreTools::isoTimeToHumanTime($value);
    }
    
    $return .= parent::render($name, $value, $attributes, $errors);
          
    return $return;
  } 
}