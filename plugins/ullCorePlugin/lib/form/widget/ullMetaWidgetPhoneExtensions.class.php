<?php

/**
 * ullMetaWidgetPhoneExtension
 * 
 *  This widget is user for phone extensions
 *  Option show_base_number added the base phone number to the extension
 */
class ullMetaWidgetPhoneExtension extends ullMetaWidgetInteger
{

  protected function configureReadMode()
  {
    if ($this->columnConfig->getOption('show_base_number') == true)
    {
      $this->columnConfig->setWidgetOption('show_base_number', true);
    }
    
    $this->addWidget(new ullWidgetPhoneExtensionRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }
}