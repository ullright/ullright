<?php

class ullMetaWidgetDateTime extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      if ($this->columnConfig->getWidgetAttribute('size') == null)
      {
        $this->columnConfig->setWidgetAttribute('size', '10');
      }

      //add fake_timestamp option to validator to create "date 00:00:00" values
      $fixedValidatorOptions = array('fake_timestamp' => true);
      
      $this->addWidget(new ullWidgetDateWrite($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
      $this->addValidator(new ullValidatorDate(array_merge($fixedValidatorOptions, $this->columnConfig->getValidatorOptions())));
    }
    else
    {
      $this->addWidget(new ullWidgetDateTimeRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    	$this->addValidator(new sfValidatorPass());
    } 
  }
  
  public function getSearchType()
  {
    return 'rangeDate';
  }
}