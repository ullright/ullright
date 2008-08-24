<?php

class ullMetaWidgetString extends ullMetaWidget
{
  public function __construct($columnConfig = array())
  {
    
    if ($columnConfig['access'] == 'w')
    {
      $this->sfWidget = new sfWidgetFormInput($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorString($columnConfig['validatorOptions']);
    }
    else
    {
      $this->sfWidget = new ullWidget($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorPass();
    }
    
  }  
}

?>