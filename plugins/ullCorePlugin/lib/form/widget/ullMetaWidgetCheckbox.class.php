<?php
/**
 * ullMetaWidgetCheckbox
 * 
 * Used for checkboxes
 */
class ullMetaWidgetCheckbox extends ullMetaWidget
{
  public function __construct($columnConfig = array())
  {
    if ($columnConfig['access'] == 'w')
    {
      $this->sfWidget = new sfWidgetFormInputCheckbox($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorBoolean($columnConfig['validatorOptions']);
    }
    else
    {
      $this->sfWidget = new ullWidgetCheckbox($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorPass();
    }

  }
}

?>