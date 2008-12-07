<?php

class ullWidgetTextarea extends ullWidget
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($value) {
      $value = nl2br($value);
    } else {
      $value = '';
    }
    return $value;
  }

}

?>