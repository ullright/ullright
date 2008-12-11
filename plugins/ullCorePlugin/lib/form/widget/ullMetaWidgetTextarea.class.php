<?php
/**
 * ullMetaWidgetTextarea
 *
 * Used for strings
 */
class ullMetaWidgetTextarea extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      $this->addWidget(new sfWidgetFormTextarea($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorString($this->columnConfig['validatorOptions']));
    }
    else
    {
      $this->addWidget(new ullWidgetTextarea($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }

  }
}

?>