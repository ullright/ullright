<?php

class ullMetaWidgetIpAddress extends ullMetaWidget
{
  protected function configureWriteMode()
  {
    $this->addWidget(new sfWidgetFormInput($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(
      new sfValidatorRegex(array_merge(array('pattern' =>
        '/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/'
        ), $this->columnConfig->getValidatorOptions())));
  }
}