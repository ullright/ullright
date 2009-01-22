<?php
/**
 * ullMetaWidgetTextarea
 *
 * Used for strings
 */
class ullMetaWidgetTextarea extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      if (!isset($this->columnConfig['widgetAttributes']['cols']))
      {
        $this->columnConfig['widgetAttributes']['cols'] = '58';        
      }
      if (!isset($this->columnConfig['widgetAttributes']['rows']))
      {
        $this->columnConfig['widgetAttributes']['rows'] = '4';        
      }     
      
      $this->addWidget(new sfWidgetFormTextarea($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorString($this->columnConfig['validatorOptions']));
    }
    else
    {
      $this->addWidget(new ullWidgetTextarea($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }

  }
}

?>