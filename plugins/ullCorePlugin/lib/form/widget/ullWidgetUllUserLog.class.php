<?php

class ullWidgetUllUserLog extends ullWidget
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return UllUser::decodeLog($value);
    
  }
}
