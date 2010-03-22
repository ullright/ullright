<?php

/**
 * Provides a js-enhanced color picker in write mode
 * and a colored rectangle in read mode; saves
 * hex-code internally
 */
class ullMetaWidgetColorPicker extends ullMetaWidget
{
  protected function configureWriteMode()
  {
    $this->addWidget(new dcWidgetFormColorPicker($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new dcValidatorColorPicker($this->columnConfig->getValidatorOptions()));
  }
  
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetColorRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass($this->columnConfig->getValidatorOptions()));
  }
}
