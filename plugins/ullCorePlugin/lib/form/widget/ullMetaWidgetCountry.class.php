<?php

class ullMetaWidgetCountry extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      $culture = sfContext::getInstance()->getUser()->getCulture();
      
      $countries = array('AT', 'DE', 'LI', 'CH');
            
      $this->addWidget(new sfWidgetFormI18nSelectCountry(array('culture' => $culture, 'countries' => $countries)));
      //sfValidatorI18nChoiceCountry doesn't support max_length
      $this->columnConfig->removeValidatorOption('max_length');
      $this->addValidator(new sfValidatorI18nChoiceCountry($this->columnConfig->getValidatorOptions()));
    }
    else
    {
      $this->addWidget(new ullWidgetCountryRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
      $this->addValidator(new sfValidatorPass());
    }
  }
  
  public function getSearchType()
  {
    return 'foreign';
  }
}
