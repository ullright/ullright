<?php
/**
 * ullMetaWidgetTextarea
 *
 * Used for strings
 */
class ullMetaWidgetTextarea extends ullMetaWidgetString
{
  
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetTextarea($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());  
  }
  
  protected function configureWriteMode()
  {
    if ($this->columnConfig->getWidgetAttribute('cols') == null)
    {
      $this->columnConfig->setWidgetAttribute('cols', '58');        
    }
    if ($this->columnConfig->getWidgetAttribute('rows') == null)
    {
      $this->columnConfig->setWidgetAttribute('rows', '4');        
    }     
    
    $this->addWidget(new sfWidgetFormTextarea($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorString($this->columnConfig->getValidatorOptions()));  
  }
  
  protected function configureSearchMode()
  {
    parent::configureWriteMode();
  }
  
}