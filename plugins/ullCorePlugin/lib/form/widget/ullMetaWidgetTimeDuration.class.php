<?php
/**
 * Meta widget for time durations
 * Example: Buying shoes: 1:30h
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
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