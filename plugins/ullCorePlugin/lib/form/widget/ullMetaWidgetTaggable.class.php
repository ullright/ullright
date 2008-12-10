<?php
/**
 * ullMetaWidgetString 
 * 
 * Used for strings
 */
class ullMetaWidgetTaggable extends ullMetaWidget
{
  public function __construct($columnConfig = array())
  {
    if ($columnConfig['access'] == 'w')
    {
      $this->sfWidget = new ullWidgetTaggable($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new ullValidatorTaggable($columnConfig['validatorOptions']);
    }
    else
    {
      $this->sfWidget = new ullWidget($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorPass();
    }
    
  }  
}

?>