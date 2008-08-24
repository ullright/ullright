<?php

class ullMetaWidgetForeignKey extends ullMetaWidget
{
  public function __construct($columnConfig = array())
  {
    if ($columnConfig['access'] == 'w')
    {
      $columnConfig['widgetOptions']['model'] = $columnConfig['relation']['model'];
      $columnConfig['validatorOptions']['model'] = $columnConfig['relation']['model'];
      
      $this->sfWidget = new sfWidgetFormDoctrineSelect($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorDoctrineChoice($columnConfig['validatorOptions']);
    }
    else
    {
      $this->sfWidget = new ullWidget($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorPass();
    }
  }  
}

?>