<?php

/**
 *  ullMetaWidgetPhoneNumber
 * 
 *  This widget is used for phone numbers (incl. mobile numbers)
 *  Option show_local_short_form don't display the country code in the read mode
 *  Option default_country_code adds a default value to allow a number without the country code
 */
class ullMetaWidgetPhoneNumber extends ullMetaWidgetString
{

  protected function configureReadMode()
  {
    if ($this->columnConfig->getOption('show_local_short_form') == true)
    {
      $this->columnConfig->setWidgetOption('show_local_short_form', true);
    }
    
    $this->addWidget(new ullWidgetPhoneNumberRead(
      $this->columnConfig->getWidgetOptions(), 
      $this->columnConfig->getWidgetAttributes()
    ));
    $this->addValidator(new sfValidatorPass());
  }
  
  protected function configureWriteMode()
    {
      if ($this->columnConfig->getOption('default_country_code'))
      {
        $this->columnConfig->setValidatorOption(
          'default_country_code', 
          $this->columnConfig->getOption('default_country_code')
        );
      }
      $this->addWidget(new sfWidgetFormInput(
        $this->columnConfig->getWidgetOptions(), 
        $this->columnConfig->getWidgetAttributes()
      ));
      $this->addValidator(new ullValidatorPhoneNumber($this->columnConfig->getValidatorOptions()));
  
      if (!$this->columnConfig->getHelp())
      {
        $this->columnConfig->setHelp(__("Format: +43 1 234567-100<br />(Country code, area code, base number, extension)", null, 'ullCoreMessages'));
      }      
    }
    
  }