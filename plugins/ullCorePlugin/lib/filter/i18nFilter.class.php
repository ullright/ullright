<?php
/**
 * i18n filter.
 *
 * @package    ullright
 * @author     Klemens Ullmann
 */
class i18nFilter extends sfFilter
{
  
  /**
   * Filter to handle default setting of the culture
   * @param none
   * @return none
   */   
  public function execute($filterChain) 
  {
    // Execute this filter only once (on the first call):
    if ($this->isFirstCall()) 
    {
      // get context objects:
      $request  = $this->getContext()->getRequest();      
      $user     = $this->getContext()->getUser();
      
      // use browser culture if the user didn't manually select a language
      if (!$user->getAttribute('is_culture_set_by_user'))
      {
        $browserCulture = $request->getLanguages();
        if (isset($browserCulture[0])) 
        {
          $browserLanguage = substr($browserCulture[0] ,0 ,2);
          $supportedLanguages = explode(',', sfConfig::get('app_supported_languages'));
          if (in_array($browserLanguage, $supportedLanguages))
          {
            $user->setCulture($browserLanguage);
          }
        }
      }
//      var_dump($user->getCulture());die;
      
      // load I18N helper to allow usage of __('') in any action
      sfLoader::loadHelpers('I18N');
    }
    
    // Execute next filter
    $filterChain->execute();
    
  }
}

?>