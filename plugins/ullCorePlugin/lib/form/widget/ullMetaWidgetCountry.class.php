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
      unset($this->columnConfig['validatorOptions']['max_length']);
      $this->addValidator(new sfValidatorI18nChoiceCountry($this->columnConfig['validatorOptions']));
    }
    else
    {
      $this->addWidget(new ullWidgetCountryRead($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }
  }
  
  public function getSearchPrefix()
  {
    return 'foreign';
  }
}
