<?php

class ullWidgetFloatRead extends ullWidget
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $currentCulture = sfContext::getInstance()->getUser()->getCulture();
    $numberFormatInfo = sfNumberFormatInfo::getInstance($currentCulture);
    $numberFormat = new sfNumberFormat($numberFormatInfo);
    
    $value = $numberFormat->format($value, ',##0.00');
    
    return parent::render($name, $value, $attributes, $errors);
  } 
}