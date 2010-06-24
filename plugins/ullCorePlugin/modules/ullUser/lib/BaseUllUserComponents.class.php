<?php

class BaseUllUserComponents extends sfComponents
{

  public function executeHeaderSyslinkMyAccount() 
  {
    $this->username = UllUserTable::findLoggedInUsername();
  }
  
  
  public function executeHeaderSyslinkLanguageSelectionGermanEnglish()
  {
    $this->language = null;
    
    if (count(sfConfig::get('app_i18n_supported_languages')) > 1)
    {
      $this->language = substr($this->getUser()->getCulture(), 0, 2);
    }
  }
  
  
  public function executeHeaderLogin() 
  {
    $this->username = UllUserTable::findLoggedInUsername();
  }
  
}
