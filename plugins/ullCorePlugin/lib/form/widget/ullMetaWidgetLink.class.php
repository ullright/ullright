<?php
/**
 * ullMetaWidgetLink 
 * 
 * Used for strings
 */
class ullMetaWidgetLink extends ullMetaWidget
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
      unset(
        $this->columnConfig['widgetAttributes']['size'],
        $this->columnConfig['widgetAttributes']['maxlength']
      );
      $this->addWidget(new ullWidgetLink($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }
    
  }  
}

?>