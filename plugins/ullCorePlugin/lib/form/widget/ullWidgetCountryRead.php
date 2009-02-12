<?php

class ullWidgetCountryRead extends ullWidget
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $culture = sfContext::getInstance()->getUser()->getCulture();
    $fc = format_country($value, $culture);
    if ($fc == '')
      throw new InvalidArgumentException('Not a valid ISO 3166 country code.');
    
    return $fc;
  }
}
