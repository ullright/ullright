<?php

class ullMetaWidgetIpAddress extends ullMetaWidget
{
  protected function configureWriteMode()
  {
    $this->addWidget(new sfWidgetFormInput($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(
      new sfValidatorRegex(array_merge(array('pattern' =>
        '\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b'
        ), $this->columnConfig->getValidatorOptions())));
  }
}