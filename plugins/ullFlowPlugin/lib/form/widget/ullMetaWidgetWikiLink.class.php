<?php
/**
 * ullMetaWidgetUpload
 *
 * Used for uploads in ullFlow
 */
class ullMetaWidgetUpload extends ullMetaWidget
{
  public function __construct($columnConfig = array())
  {
    if ($columnConfig['access'] == 'w')
    {
      $this->sfWidget = new ullWidgetUpload($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorString($columnConfig['validatorOptions']);
    }
    else
    {
      $this->sfWidget = new ullWidgetUploadRead($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorPass();
    }

  }
}

?>