<?php

class ullWidgetLink extends ullWidget
{
 
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $value = parent::render($name, $value, $attributes, $errors);
    
    return '<b>' . $this->renderContentTag('a', $value, $attributes) . '</b>';
  }

}
