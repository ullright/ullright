<?php

class ullWidgetUploadRead extends ullWidgetUpload
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {

    return self::renderUploadList($value);
  }
  
}