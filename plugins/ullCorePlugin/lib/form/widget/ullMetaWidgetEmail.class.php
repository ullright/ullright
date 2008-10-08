<?php

class ullMetaWidgetEmail extends ullMetaWidget
{
  public function __construct($columnConfig = array())
  {
    if ($columnConfig['access'] == 'w')
    {
      $this->sfWidget = new sfWidgetFormInput($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorEmail($columnConfig['validatorOptions']);
    }
    else
    {
      unset($columnConfig['widgetAttributes']['maxlength']);
      $this->sfWidget = new ullWidgetEmail($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorPass();
    }
    
  }  
}

?>