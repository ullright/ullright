<?php

/**
 *  ullMetaWidgetPhoneNumber
 * 
 *  This widget is user for phone numbers (incl. mobile numbers)
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
    }
  }