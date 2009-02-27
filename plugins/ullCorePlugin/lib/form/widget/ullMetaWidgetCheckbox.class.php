<?php
/**
 * ullMetaWidgetCheckbox
 * 
 * Used for checkboxes
 */
class ullMetaWidgetCheckbox extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      $this->addWidget(new ullWidgetCheckboxWrite($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorBoolean($this->columnConfig['validatorOptions']));
    }
    else
    {
      $this->addWidget(new ullWidgetCheckbox($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }

  }
}