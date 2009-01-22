<?php

class ullMetaWidgetDate extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      if (!isset($this->columnConfig['widgetAttributes']['size']))
      {
        $this->columnConfig['widgetAttributes']['size'] = '30';
      }
    	
    	$this->addWidget(new sfWidgetFormInput($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));      
      $this->addValidator(new sfValidatorDateTime($this->columnConfig['validatorOptions']));
    }
    else
    {
      $this->addWidget(new ullWidgetDateRead($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    	$this->addValidator(new sfValidatorPass());
    }
    
  }  
}

?>