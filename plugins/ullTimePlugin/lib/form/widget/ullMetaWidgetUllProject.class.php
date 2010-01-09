<?php

class ullMetaWidgetUllProject extends ullMetaWidgetForeignKey
{
    
  protected function configureWriteMode()
  {
    $this->parseOptions();
    
    $this->addWidget(new ullWidgetUllProjectWrite($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorDoctrineChoice($this->columnConfig->getValidatorOptions()));
    
    $this->handleAllowCreate();
  }
  
}