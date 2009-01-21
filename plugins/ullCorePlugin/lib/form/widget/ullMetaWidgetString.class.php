<?php
/**
 * ullMetaWidgetString 
 * 
 * Used for strings
 */
class ullMetaWidgetString extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      if (!isset($this->columnConfig['widgetAttributes']['size']))
      {
        $this->columnConfig['widgetAttributes']['size'] = '50';
      }
      
      $this->addWidget(new sfWidgetFormInput($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorString($this->columnConfig['validatorOptions']));
    }
    else
    {
      $this->addWidget(new ullWidget($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }
    
  }  
}

?>