<?php
/**
 * i18n filter.
 *
 * @package    ull_at
 * @author     Klemens Ullmann
 */
class i18nFilter extends sfFilter
{
  
/**
 * Filter to handle default setting of the culture
 * @param none
 * @return none
 */   
  public function execute($filterChain) {
    
    // Execute this filter only once (on the first call):
    if ($this->isFirstCall()) {
      
      // get context objects:
      $request  = $this->getContext()->getRequest();      
      $user     = $this->getContext()->getUser();
      $culture  = $user->getCulture();
      $this->getContext()->getLogger()->info('current user-culture: '.$culture);
      
      // if no culture yet-> set HTTP Accept-Language as default: 
      if (!$culture or $culture == 'xx_XX') {
        $culture = $request->getLanguages();
        if ($culture) {
          $user->setCulture($culture[0]);
          $this->getContext()->getLogger()->info(
          "no user-culture set: using HTTP Accept-Language ({$culture[0]})");
        } else {
          // use default language (en)
          $user->setCulture(sfConfig::get('base_default_language', 'en'));
        }
        
        
      }
      
      $this->getContext()->getLogger()->info(
          'final user-culture: ('. $user->getCulture() . ')'
        );
      
      // load I18N helper to allow usage of __('') in any action
      sfLoader::loadHelpers('I18N');
    }
    
    // Execute next filter
    $filterChain->execute();
    
  }
}

?>