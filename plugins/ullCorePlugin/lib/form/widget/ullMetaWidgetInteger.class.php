<?php

class ullMetaWidgetInteger extends ullMetaWidget
{
  protected function configure()
  {
    $suffixOption = $this->columnConfig->getOption('suffix');

    if ($suffixOption)
    {
      $this->columnConfig->setWidgetOption('suffix', $suffixOption);
    }
  }
  
  protected function configureWriteMode()
  {
    $this->addWidget(new ullWidgetFormInput($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorInteger($this->columnConfig->getValidatorOptions()));    
  }
  
  public function getSearchType()
  {
    return 'range';
  }
}