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
     $this->addWidget(new ullWidget($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
     $this->addValidator(new sfValidatorPass());
  }
  
  protected function configureWriteMode()
  {
    if (!$this->columnConfig->getWidgetAttribute('size'))
    {
      $this->columnConfig->setWidgetAttribute('size', '50');
    }
    
    $this->addWidget(new sfWidgetFormInput($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorString($this->columnConfig->getValidatorOptions())); 
  }
  
}
