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
      if ($this->columnConfig->getWidgetAttribute('size') == null)
      {
        $this->columnConfig->setWidgetAttribute('size', '50');
      }
    	
    	$this->addWidget(new sfWidgetFormInput($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
      $this->addValidator(new sfValidatorString($this->columnConfig->getValidatorOptions()));
    }
    else
    {
      $this->columnConfig->removeWidgetAttribute('size');
      $this->columnConfig->removeWidgetAttribute('maxlength');
 
      $this->addWidget(new ullWidgetLink($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
      $this->addValidator(new sfValidatorPass());
    }
    
  }  
}
