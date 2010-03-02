<?php
/**
 * ullMetaWidgetString 
 * 
 * Used for strings
 */
class ullMetaWidgetString extends ullMetaWidget
{
  
  protected function configureReadMode()
  {
    $this->columnConfig->removeWidgetOption('disablePurification');
    
    $this->addWidget(new ullWidget($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }
  
  protected function configureWriteMode()
  {
    if (!$this->columnConfig->getWidgetAttribute('size'))
    {
      $this->columnConfig->setWidgetAttribute('size', '40');
    }
    
    if ($this->columnConfig->getWidgetOption('disablePurification'))
    {
      $this->addValidator(new sfValidatorString($this->columnConfig->getValidatorOptions())); 
    }
    else
    {
      $this->addValidator(new ullValidatorPurifiedString($this->columnConfig->getValidatorOptions())); 
    }
    
    $this->columnConfig->removeWidgetOption('disablePurification');
    
    $this->addWidget(new sfWidgetFormInput($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
  }
}
