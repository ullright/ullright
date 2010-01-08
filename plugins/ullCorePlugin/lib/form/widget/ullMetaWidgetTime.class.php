<?php

/**
 * Meta widget for times
 * Example: Woke up at 6:50h
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullMetaWidgetTime extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      if ($this->columnConfig->getWidgetAttribute('size') == null)
      {
        $this->columnConfig->setWidgetAttribute('size', '5');
      }
    	
    	$this->addWidget(new ullWidgetTimeWrite($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));      
      $this->addValidator(new ullValidatorTime($this->columnConfig->getValidatorOptions()));
    }
    else
    {
      $this->addWidget(new ullWidgetTimeRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    	$this->addValidator(new sfValidatorPass());
    } 
  }
  
  public function getSearchType()
  {
    return 'range';
  }
}