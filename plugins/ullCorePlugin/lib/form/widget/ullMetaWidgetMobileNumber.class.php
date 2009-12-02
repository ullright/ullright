<?php

class ullMetaWidgetMobileNumber extends ullMetaWidgetString
{

  protected function configureReadMode()
  {
    if ($this->columnConfig->getOption('show_local_short_form') == true)
    {
      $this->columnConfig->setWidgetOption('show_local_short_form', true);
    }
    
    $this->addWidget(new ullWidgetMobileNumberRead(
      $this->columnConfig->getWidgetOptions(), 
      $this->columnConfig->getWidgetAttributes()
    ));
    $this->addValidator(new sfValidatorPass());
  }
  
protected function configureWriteMode()
  {
    $this->addWidget(new sfWidgetFormInput(
      $this->columnConfig->getWidgetOptions(), 
      $this->columnConfig->getWidgetAttributes()
    ));
    $this->addValidator(new ullValidatorMobileNumber($this->columnConfig->getValidatorOptions()));
  }
}