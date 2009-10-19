<?php

class ullWidgetTextarea extends ullWidget
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (is_array($value))
    {
      $value = $value['value'];
    }
    
    if ($value)
    {
      $value = nl2br($value);
    }
    else
    {
      $value = '';
    }
    return auto_link_text($value);
  }
}
