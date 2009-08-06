<?php

class ullMetaWidgetMacAddress extends ullMetaWidget
{
  protected function configureWriteMode()
  {
    $this->addWidget(new sfWidgetFormInput($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new ullValidatorMacAddress($this->columnConfig->getValidatorOptions()));
  }
}