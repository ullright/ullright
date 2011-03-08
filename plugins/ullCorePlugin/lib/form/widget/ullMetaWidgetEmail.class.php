<?php

class ullMetaWidgetEmail extends ullMetaWidget
{
  protected function configureWriteMode($withValidator = true)
  {
    if ($this->columnConfig->getWidgetAttribute('size') == null)
    {
      $this->columnConfig->setWidgetAttribute('size', '30');
    }
     
    $this->addWidget(new sfWidgetFormInput($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(
      ($withValidator == true) ?
        new ullValidatorEmail($this->columnConfig->getValidatorOptions()) :
        new sfValidatorString($this->columnConfig->getValidatorOptions()));
  }

  protected function configureSearchMode()
  {
    $this->configureWriteMode(false);
  }

  protected function configureReadMode()
  {
    $this->columnConfig->setWidgetAttribute('maxlength', null);
    $this->addWidget(new ullWidgetEmail($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }
}