<?php

class ullMetaWidgetTimeDuration extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      if ($this->columnConfig->getWidgetAttribute('size') == null)
      {
        $this->columnConfig->setWidgetAttribute('size', '5');
      }
    	
    	$this->addWidget(new ullWidgetTimeDurationWrite($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));      
      $this->addValidator(new ullValidatorTimeDuration($this->columnConfig->getValidatorOptions()));
    }
    else
    {
      $this->addWidget(new ullWidgetTimeDurationRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    	$this->addValidator(new sfValidatorPass());
    } 
  }
  
  public function getSearchType()
  {
    return 'range';
  }
}