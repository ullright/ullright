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
    $this->columnConfig->removeWidgetOption('decode_mime');
    
    if (!$this->columnConfig->getWidgetAttribute('size'))
    {
      $this->columnConfig->setWidgetAttribute('size', '40');
    }
    
    $this->addValidator(new sfValidatorString($this->columnConfig->getValidatorOptions())); 

    //disable injection of identifier because sfWidgetFormInput can't handle it
    $this->columnConfig->setInjectIdentifier(false);
    
    $this->addWidget(new sfWidgetFormInput($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
  }
}
