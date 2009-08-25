<?php

class ullMetaWidgetFloat extends ullMetaWidget
{
  protected function configureWriteMode()
  {
    $this->addWidget(new ullWidgetFormInput($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new ullValidatorNumberI18n($this->columnConfig->getValidatorOptions()));    
  }
  
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetFloatRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }
  
  public function getSearchType()
  {
    return 'range';
  }
}