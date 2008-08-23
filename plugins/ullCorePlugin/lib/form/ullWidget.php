<?php

class ullWidget extends sfWidgetForm
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    //return $this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value), $attributes));
    return 'hohoho ' . $value;
  }
  
}

?>