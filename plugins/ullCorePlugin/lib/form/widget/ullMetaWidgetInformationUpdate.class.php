<?php
/**
 * ullMetaWidgetTextarea
 *
 * Used for strings
 */
class ullMetaWidgetInformationUpdate extends ullMetaWidget
{

  protected function configureWriteMode()
  {
    $this->addWidget(new ullWidgetInformationUpdateWrite($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    $this->addValidator(new sfValidatorString($this->columnConfig['validatorOptions']));
  }

  protected function configureSearchMode()
  {
    $this->addWidget(new sfWidgetFormInput($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    $this->addValidator(new sfValidatorString($this->columnConfig['validatorOptions']));
  }

  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetInformationUpdateRead($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    $this->addValidator(new sfValidatorPass());
  }
}
