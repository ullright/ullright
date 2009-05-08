<?php

class ullWidgetAjaxSelectWrite extends sfWidgetFormDoctrineSelect
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');        
    
    $output = '<input id="autocomplete5"/>';
    
    $output .= parent::render($name, $value = null, $attributes = array(), $errors = array());
    
    $output .= " foobar";
    

    
    $output .= javascript_tag('
$("#autocomplete5").autocomplete({
  source:"#' . $id . '",
  
  fillin:true
});
    ');
    
    return $output;
  }
  
}
