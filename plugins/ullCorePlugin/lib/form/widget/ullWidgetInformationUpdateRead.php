<?php

class ullWidgetInformationUpdateRead extends ullWidget
{
  //atm, this is the same as ullWidgetTextarea
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
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