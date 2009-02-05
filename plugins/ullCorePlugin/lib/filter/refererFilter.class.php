<?php

/**
 * referer filter.
 *
 * @package    ullright
 * @author     Klemens Ullmann
 */
class refererFilter extends sfFilter
{

/**
 * Filter to save the URI of the current page in the user session for the next request
 * @param none
 * @return none
 */ 
  public function execute($filterChain) 
  {
    // Execute this filter only once (on the first call):
    if ($this->isFirstCall()) 
    {
      $context  = $this->getContext();
      $request  = $context->getRequest();
      $user     = $context->getUser();
      
      // === generic referer (simulates $_REQUEST('HTTP_REFERER');
      
      // don't overwrite the referer on page reload
      if ($user->getAttribute('referer_transfer') <> $request->getUri()) 
      {
        
        // the url of the previous page was saved into referer_transfer. 
        //  now we save this url to the actual referer attribute
        if ($user->getAttribute('referer_transfer')) 
        {
          $user->setAttribute('referer', $user->getAttribute('referer_transfer'));
        }

        // referer_transfer is overwritten with the url of the current page
        //  to remember it for the next page
        $user->setAttribute('referer_transfer', $request->getUri());
      }
      
//      if ($request->getReferer()) {
//        
//        $user->setAttribute('referer', $request->getReferer());
//      }
      
//      // module/action specific referer
//      $module_action = $context->getModuleName().'_'.$context->getActionName();
//
////      if ($user->getAttribute('referer_'.$module_action)) {
////        $user->setAttribute(
////          'referer_old_'.$module_action,
////          $user->getAttribute('referer_'.$module_action)
////         );
////      }
////      $user->setAttribute(
////        'referer_'.$module_action,
////        $user->getAttribute('referer')
////      );
//
//      if ($request->getReferer()) {
//        $user->setAttribute(
//          'referer_'.$module_action,
//          $user->getAttribute('referer')
//        );
//      }
    }
          
    // Execute next filter
    $filterChain->execute();
    
  }
}

?>