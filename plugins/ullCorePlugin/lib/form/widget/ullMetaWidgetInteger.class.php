<?php

class ullMetaWidgetInteger extends ullMetaWidget
{
  protected function configureWriteMode()
  {
    //TODO: refactor to allow generic usage by all metaWidgets?
    if ($this->columnConfig->getWidgetOption('is_hidden') != null)
    {
      $this->addWidget(new sfWidgetFormInputHidden($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    }
    else
    {
      $this->addWidget(new sfWidgetFormInput($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    }    
    $this->addValidator(new sfValidatorInteger($this->columnConfig->getValidatorOptions()));    
  }
  
  public function getSearchPrefix()
  {
    return 'range';
  }
}