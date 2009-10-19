<?php

/*
 * Generic Base ull sfAction class
 *
 * Enhances sfAction.class.php with features used for ALL modules and plugins
 *
 *
 */

abstract class BaseUllsfActions extends sfActions
{
  protected
    $uriMemory
  ;

  /**
   * Default preExecute method
   *
   * @return none
   */
  public function preExecute()
  {
    sfLoader::loadHelpers('ull');

    $this->uriMemory = new UriMemory();
//    if ($this->getModuleName() != 'ulluser' && $this->getActionName() != 'noaccess')
//    {
//      $this->uriMemory->setUri('noaccess', 'ullUser'); 
//    }

    $this->ullpreExecute();
  }

  /**
   * Child actions should overwrite ullpreExecute()
   *
   * @return none
   */
  public function ullpreExecute()
  {
  }

  /**
   * access check (group based)
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
    $this->getUriMemory()->setUri('login', 'ullUser');

    $this->redirectToNoAccessUnless(UllUserTable::hasGroup($group));
  }

  /**
   * access check (permission based)
   * checks if the currently logged in user has the given permission
   * if not logged in -> redirect to login page
   * if the current user doesn't have the given permission -> display access denied
   *
   *
   * @param string $permission    a permission slug. example: ull_wiki_edit
   * @return none
   */
  public function checkPermission($permission)
  {
    $this->getUriMemory()->setUri('login', 'ullUser');
     
    $this->redirectToNoAccessUnless(UllUserTable::hasPermission($permission));
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
    $this->getUriMemory()->setUri('login', 'ullUser');

    $this->redirectToNoAccessUnless($this->getUser()->getAttribute('user_id'));
  }

  /**
   * Redirect to the noaccess action unless the condition is true
   * 
   * Actually forwards now, to preserve the original POST request
   * values when the session timed out
   * 
   * @param $condition
   * @return none
   */
  public function redirectToNoAccessUnless($condition)
  {
    // save current URI
    $this->getUriMemory()->setUri('noaccess', 'ullUser');
    
//    $this->redirectUnless($condition, 'ullUser/noaccess');
    $this->forwardUnless($condition, 'ullUser', 'noaccess');
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

      $params = $this->prioritizeFilterParamsFromPOST($params);
      
      if ($ull_reqpass = $this->getRequestParameter('ull_reqpass'))
      {
        $ull_reqpass = unserialize($ull_reqpass);
        $params = array_merge($params, $ull_reqpass);
      }
      
      $params = _ull_reqpass_initialize($params);
      
      $url = _ull_reqpass_build_url($params);

      /*
       $ullSearch = $this->getUser()->getFlash('ullSearch');
       if ($ullSearch != NULL)
       {
       $this->getUser()->setFlash('ullSearch', $ullSearch);
       }*/

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

  /**
   * Returns the UriMemory class
   *
   *  @return UriMemory
   */
  public function getUriMemory()
  {
    return $this->uriMemory;
  }
  
  /**
   * Removes all existing search criteria from the current search
   * stored in the session, causing the default ones to be inserted.
   *
   * @param $request The current request
   */
  public function executeResetSearchCriteria(sfRequest $request)
  {
    $moduleName = $request->getParameter('module');
    if ($this->getUser()->hasAttribute($moduleName . '_searchFormEntries'))
    {
      $this->getUser()->getAttributeHolder()->remove($moduleName . '_searchFormEntries');
      $this->redirect($this->getUriMemory()->get('search'));
    }
  }
  
  
  /**
   * Get the username of the currently logged in user
   * 
   * @return string
   */
  protected function getLoggedInUsername()
  {
    $userId = $this->getUser()->getAttribute('user_id');
    if ($userId)
    {
      return UllUserTable::findUsernameById($userId);
    } 
  }
  
  
  /**
   * We have to prioritize POST request values for the (search) filters
   * 
   * Usecase: 
   *  * list action -> search for "foo"
   *  * with reqpass_redirect we get to the page "myModule/list/filter[search]/foo"
   *  * now we search for "bar"
   *  * for the param filter[search] we now have two request values:
   *    * POST: "bar"
   *    * GET (from routing) "foo"
   *  * the routing GET param overrides the POST param, but we neet the POST param
   *    because it holds the actual value. 
   * 
   * @param $params
   * @return array
   */
  protected function prioritizeFilterParamsFromPOST($params)
  {
    $POST = get_magic_quotes_gpc() ? sfToolkit::stripslashesDeep($_POST) : $_POST;

    if (isset($params['filter']))
    {
      foreach ($params['filter'] as $key => $value)
      {
        if (isset($POST['filter'][$key]))
        {
          $params['filter'][$key] = $POST['filter'][$key];
        }
      }
    }
    
    return $params;
  }
  
  
  /**
   * Forwards to the default error page.
   * 
   * Use this for non-critical errors, that can be corrected by the user/helpdesk
   * 
   * @param $message
   * @return none
   */
  protected function showError($message)
  {
    $this->getUser()->setFlash('error', $message);
    $this->forward('default', 'error');
  }
  
  
  /**
   * Set empty layout (without sidebar, navigation, etc)
   * 
   * @return none
   */
  protected function setEmptyLayout()
  {
    $layout = sfConfig::get('sf_root_dir') . '/plugins/ullCoreTheme' .
      sfConfig::get('app_theme_package', 'NG') .
      'Plugin/templates/emptyLayout';
    $this->setLayout($layout);
  }
  
  
 
}