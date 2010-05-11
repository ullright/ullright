<?php

class ullMetaWidgetPhoneNumber extends ullMetaWidgetInteger
{

  protected function configureReadMode()
  {
    if ($this->columnConfig->getOption('show_base_number') == true)
    {
      $this->columnConfig->setWidgetOption('show_base_number', true);
    }
    
    $this->addWidget(new ullWidgetPhoneNumber($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }
  
  protected function configureWriteMode()
  {
    $this->addWidget(new sfWidgetFormInput(
      $this->columnConfig->getWidgetOptions(), 
      $this->columnConfig->getWidgetAttributes()
    ));
    $this->addValidator(new ullValidatorPhoneNumber($this->columnConfig->getValidatorOptions()));
  }
}