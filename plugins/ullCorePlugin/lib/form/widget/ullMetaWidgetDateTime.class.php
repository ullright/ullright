<?php

class ullMetaWidgetDateTime extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      if ($this->columnConfig->getWidgetAttribute('size') == null)
      {
        $this->columnConfig->setWidgetAttribute('size', '30');
      }
    	
    	$this->addWidget(new ullWidgetDateWrite($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));      
      $this->addValidator(new sfValidatorDate($this->columnConfig->getValidatorOptions()));
    }
    else
    {
      $this->addWidget(new ullWidgetDateTimeRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    	$this->addValidator(new sfValidatorPass());
    } 
  }
  
  public function getSearchPrefix()
  {
    return 'range';
  }
}