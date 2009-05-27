<?php

class ullMetaWidgetInteger extends ullMetaWidget
{
  protected function configureWriteMode()
  {
    //TODO: refactor to allow generic usage by all metaWidgets?
    if (isset($this->columnConfig['widgetOptions']['is_hidden']))
    {
      $this->addWidget(new sfWidgetFormInputHidden($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    }
    else
    {
      $this->addWidget(new sfWidgetFormInput($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    }    
    $this->addValidator(new sfValidatorInteger($this->columnConfig['validatorOptions']));    
  }
  
  public function getSearchPrefix()
  {
    return 'range';
  }
}