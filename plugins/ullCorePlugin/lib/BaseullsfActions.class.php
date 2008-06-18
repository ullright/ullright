<?php

/*
 * Generic Base ull sfAction class
 * 
 * Enhances sfAction.class.php with features used for ALL modules and plugins
 *
 *  
 */

class BaseullsfActions extends sfActions
{

  // default preExecute, can be overwritten by modules actions class
  public function preExecute() {
    
    sfLoader::loadHelpers('ull');

//    echo "<h1>BaseUll</h1>";
    $this->ullpreExecute();
    
  }
  
  public function ullpreExecute() {   
    
//    $sf_root_dir = sfConfig::get('sf_root_dir');
    
    // load I18N helper to process __() function in actions
//    sfLoader::loadHelpers('I18N');
    
    // load theme
//    $this->theme = sfConfig::get('app_theme', 'ullThemeDefault');
//    $this->setLayout($sf_root_dir.'/plugins/'.$this->theme.'/templates/layout');
//    weflowTools::printR($this);
  }
  
/**
 * access check
 * checks if the currently logged in user is member of the given group
 * if not logged in -> redirect to login page
 * if the current user is not member of the group -> display access denied
 * 
 * @param group integer   id of the group
 * @return none
 */   
  public function checkAccess($group) {

    // check access
    $access_refererHandler = new refererHandler();
    $access_refererHandler->initialize('access','ullUser');
    $this->redirectUnless(UllUserPeer::userHasGroup($group), 'ullUser/noaccess');
  }
  
  
/**
 * check if logged in
 * if not logged in -> redirect to login page
 * 
 * @param none
 * @return none
 */   
  public function checkLoggedIn() {

    $access_refererHandler = new refererHandler();
    $access_refererHandler->initialize('access','ullUser');
    $this->redirectUnless($this->getUser()->getAttribute('user_id'), 'ullUser/noaccess');
  }  
  
  
/**
 * counterpart for ullHelper ull_reqpass_form_tag
 * 
 * in case of a POST request it handles the request params, 
 * deserializes the request params passed from the previous page,
 * and redirects to build a valid GET url
 * @param none
 * @return none
 */   
  public function ull_reqpass_redirect() {
  
    if ($this->getRequest()->getMethod() == sfRequest::POST) {
          
      $ull_reqpass = $this->getRequestParameter('ull_reqpass');
      if ($ull_reqpass) {
        $ull_reqpass = unserialize($ull_reqpass);
      }
      
      // remove the reqpass hidden field
      $ull_reqpass['ull_reqpass'] = '';
    
      $params = _ull_reqpass_initialize($ull_reqpass, true);
      
      foreach ($params as $key => $value) {
        
        // encode '.' in url params
        $params[$key] = ull_sf_url_encode($value);
        
        
      }

//      ullCoreTools::printR($params);
//      exit();

      $url = _ull_reqpass_build_url($params);

      return $this->redirect($url);

    } else {
      
      // decode params encoded by ull_sf_url_encode()
    
      $params = $this->getRequest()->getParameterHolder()->getAll();
//      ullCoreTools::printR($params);
      
      foreach ($params as $key => $value) {
        $this->getRequest()->setParameter($key, ull_sf_url_decode($value));  
      }
      
//      $params = $this->getRequest()->getParameterHolder()->getAll();
//      ullCoreTools::printR($params);
      
    }
  
  } 
  
}


?>