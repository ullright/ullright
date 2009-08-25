<?php

class ullWidgetFormInput extends sfWidgetFormInput
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('suffix');
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $currentCulture = sfContext::getInstance()->getUser()->getCulture();
    $numberFormatInfo = sfNumberFormatInfo::getInstance($currentCulture);
    $numberFormat = new sfNumberFormat($numberFormatInfo);
    
    $value = $numberFormat->format($value, ',##0.00');
    
    $suffix = $this->getOption('suffix');
    return parent::render($name, $value, $attributes, $errors) . ' ' . $suffix;
  }
}
