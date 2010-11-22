<?php

/**
 * This class represents a string the same way ullMetaWidgetString
 * does, but validates it with a given regular expression using
 * sfValidatorRegex.
 * 
 * Note: The validation option 'pattern' is mandatory.
 */
class ullMetaWidgetStringRegex extends ullMetaWidgetString
{
  protected function configureWriteMode()
  {
    if (!$this->columnConfig->getWidgetAttribute('size'))
    {
      $this->columnConfig->setWidgetAttribute('size', '40');
    }
    
    $this->addValidator(new sfValidatorRegex($this->columnConfig->getValidatorOptions())); 

    //disable injection of identifier because sfWidgetFormInput can't handle it
    $this->columnConfig->setInjectIdentifier(false);
    
    $this->addWidget(new sfWidgetFormInput($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
  }
}
