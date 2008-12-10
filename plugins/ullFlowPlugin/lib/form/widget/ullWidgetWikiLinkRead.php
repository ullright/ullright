<?php

class ullWidgetWikiLinkRead extends ullWidgetWikiLink
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {

    return self::renderWikiLinkList($value);
  }
  
}