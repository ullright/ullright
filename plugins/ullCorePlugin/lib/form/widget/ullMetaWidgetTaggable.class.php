<?php
/**
 * ullMetaWidgetString 
 * 
 * Used for strings
 */
class ullMetaWidgetTaggable extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      if (!isset($this->columnConfig['widgetAttributes']['size']))
      {
        $this->columnConfig['widgetAttributes']['size'] = '30';
      }
    	
    	$this->addWidget(new ullWidgetTaggable($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new ullValidatorTaggable($this->columnConfig['validatorOptions']));
    }
    else
    {
      $this->addWidget(new ullWidget($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }
    
  }  
}

?>