<?php
/**
 * ullMetaWidgetUpload
 *
 * Used for uploads in ullFlow
 */
class ullMetaWidgetUpload extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      $this->addWidget(new ullWidgetUpload($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorString($this->columnConfig['validatorOptions']));
    }
    else
    {
      $this->addWidget(new ullWidgetUploadRead($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }

  }
}

?>