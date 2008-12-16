<?php

/*
 * Generic Base ull sfAction class
 * 
 * Enhances sfAction.class.php with features used for ALL modules and plugins
 *
 *  
 */

class BaseUllsfActions extends sfActions
{

  // default preExecute, can be overwritten by modules actions class
  public function preExecute() 
  {
    sfLoader::loadHelpers('ull');
    $this->ullpreExecute();
  }
  
  public function ullpreExecute() 
  {   
  }
  
/**
 * access check
 * checks if the currently logged in user is member of the given group
 * if not logged in -> redirect to login page
 * if the current user is not member of the group -> display access denied
 * 
 * 
 * @param group mixed   group id, group name or array of group ids/names (not mixed!)
 * @return none
 */   
  public function checkAccess($group) 
  {

    // check access
    $access_refererHandler = new refererHandler();
    $access_refererHandler->initialize('access','ullUser');
    
    $this->redirectUnless(UllUserTable::hasGroup($group), 'ullUser/noaccess');
  }
  
  
/**
 * check if logged in
 * if not logged in -> redirect to login page
 * 
 * @param none
 * @return none
 */   
  public function checkLoggedIn() 
  {
    $access_refererHandler = new refererHandler();
    $access_refererHandler->initialize('access','ullUser');
    
    $this->redirectUnless($this->getUser()->getAttribute('user_id'), 'ullUser/noaccess');
  }  
  
  
/**
 * counterpart for ullHelper's ull_reqpass_form_tag()
 * 
 * in case of a POST request it handles the request params, 
 * deserializes the request params passed from the previous page,
 * and redirects to build a valid GET url ('address bar as command line')
 * @param none
 * @return none
 */   
  public function ull_reqpass_redirect() 
  {
    if ($this->getRequest()->getMethod() == sfRequest::POST) 
    {
      $params = $this->getRequest()->getParameterHolder()->getAll();
      
      if ($ull_reqpass = $this->getRequestParameter('ull_reqpass')) 
      {
        $ull_reqpass = unserialize($ull_reqpass);
        $params = array_merge($params, $ull_reqpass);
      }
      
      $params = _ull_reqpass_initialize($params);
      
      $url = _ull_reqpass_build_url($params);

      return $this->redirect($url);
    }

    // TODO: usecase for this section?
    else 
    {
      // decode params encoded by ull_sf_url_encode()
      $params = $this->getRequest()->getParameterHolder()->getAll();
//      ullCoreTools::printR($params);
      
      foreach ($params as $key => $value) 
      {
        $this->getRequest()->setParameter($key, ull_sf_url_decode($value));  
      }
      
//      $params = $this->getRequest()->getParameterHolder()->getAll();
//      ullCoreTools::printR($params);
    }
  } 
  
}