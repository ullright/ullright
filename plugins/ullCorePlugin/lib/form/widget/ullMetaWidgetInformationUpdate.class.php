<?php
/**
 * ullMetaWidgetTextarea
 *
 * Used for strings
 */
class ullMetaWidgetInformationUpdate extends ullMetaWidgetString
{

  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetInformationUpdateRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }
  
  protected function configureWriteMode()
  {
    $this->addWidget(new ullWidgetInformationUpdateWrite($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorString($this->columnConfig->getValidatorOptions()));
  }

  protected function configureSearchMode()
  {
    parent::configureWriteMode();
  }

}
