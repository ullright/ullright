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
      //escape the string (to prevent injection of js, etc.)
      //and convert newlines to br tags
      $value = nl2br(esc_entities($value));
    }
    else
    {
      $value = '';
    }
    
    $value = $this->handleDecodeMimeOption($value);
    
    sfContext::getInstance()->getConfiguration()->loadHelpers('Text');
    
    return auto_link_text($value);
  }
}
