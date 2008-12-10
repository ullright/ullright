<?php
/**
 * ullMetaWidgetPassword
 *
 * Used for passwords
 */
class ullMetaWidgetPassword extends ullMetaWidget
{
  public function __construct($columnConfig = array())
  {
    if ($columnConfig['access'] == 'w')
    {
      $this->sfWidget = new sfWidgetFormInputPassword($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorString($columnConfig['validatorOptions']);
    }
    else
    {
      unset($columnConfig['widgetAttributes']['maxlength']);
      $this->sfWidget = new ullWidgetPassword($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorPass();
    }

  }
}

?>