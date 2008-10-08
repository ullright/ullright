<?php

class ullMetaWidgetForeignKey extends ullMetaWidget
{
  public function __construct($columnConfig = array())
  {
    $columnConfig['widgetOptions']['model'] = $columnConfig['relation']['model'];
    
    if ($columnConfig['access'] == 'w')
    {
      $columnConfig['validatorOptions']['model'] = $columnConfig['relation']['model'];
      
      $this->sfWidget = new sfWidgetFormDoctrineSelect($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorDoctrineChoice($columnConfig['validatorOptions']);
    }
    else
    {
      $this->sfWidget = new ullWidgetForeignKey($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorPass();
    }
  }  
}

?>