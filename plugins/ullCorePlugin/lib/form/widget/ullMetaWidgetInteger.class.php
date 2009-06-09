<?php

class ullMetaWidgetInteger extends ullMetaWidget
{
  protected function configureWriteMode()
  {
    $this->addWidget(new sfWidgetFormInput($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorInteger($this->columnConfig->getValidatorOptions()));    
  }
  
  public function getSearchPrefix()
  {
    return 'range';
  }
}