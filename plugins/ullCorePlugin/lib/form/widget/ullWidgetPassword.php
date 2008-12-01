<?php

class ullWidgetPassword extends ullWidget
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($value) {
      $value = '********';
    }
    return $value;
  }

}

?>