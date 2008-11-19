<?php

class ullMetaWidgetDateTime extends ullMetaWidget
{
  public function __construct($columnConfig = array())
  {
    if ($columnConfig['access'] == 'w')
    {
//      $columnConfig['widgetOptions']['culture'] = sfContext::getInstance()->getUser()->getCulture();
      $this->sfWidget = new sfWidgetFormInput($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);      
//      $this->sfWidget = new sfWidgetFormI18nDateTime($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
//      $this->sfWidget = new sfWidgetFormJQueryDate($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorDateTime($columnConfig['validatorOptions']);
    }
    else
    {
      $this->sfWidget = new ullWidget($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorPass();
    }
    
  }  
}

?>