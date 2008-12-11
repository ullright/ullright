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