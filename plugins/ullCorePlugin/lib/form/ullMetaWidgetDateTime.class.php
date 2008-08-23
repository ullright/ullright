<?php

class ullMetaWidgetDateTime extends ullMetaWidget
{
  public function __construct($options = array())
  {
    $widgetOptions = array();
    
    if ($options['access'] == 'w')
    {
      $widgetOptions['culture'] = sfContext::getInstance()->getUser()->getCulture();
      $this->sfWidget = new sfWidgetFormI18nDateTime($widgetOptions);
      $this->sfValidator = new sfValidatorDateTime();
    }
    else
    {
      $this->sfWidget = new ullWidget();
      $this->sfValidator = new sfValidatorPass();
    }
    
  }  
}

?>