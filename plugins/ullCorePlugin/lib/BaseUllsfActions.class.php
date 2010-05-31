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
    
    $this->getResponse()->setTitle(
      $this->getModuleName() . 
      ' - ' . 
      __(ucfirst($this->getRequestParameter('action')), null, 'common')
    );

    $this->ullpreExecute();
  }
  
  
  /**
   * Template function for child actions
   *
   * @return none
   * @deprecated
   */
  public function ullpreExecute()
  {
  }
  
  /**
   * Add csv export functionality for list and report views
   * 
   * TODO: move to BaseUllGeneratorActions when ullFlow is refactored to use 
   *   BaseUllGeneratorActions
   * 
   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/action/sfAction#postExecute()
   */
  public function postExecute()
  {
    $this->handleCsvExport();
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
      
      $params = $this->transformDates($params);
      
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

    if (isset($POST['filter']))
    {
      foreach ($POST['filter'] as $key => $value)
      {
        $params['filter'][$key] = $POST['filter'][$key];
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
  
  
  /**
   * Appends a string to default title
   * 
   * @param $string
   * @return none
   */
  protected function appendToTitle($string)
  {
    $title = $this->getResponse()->getTitle();
    $title .= ' - ' . $string;
    $this->getResponse()->setTitle($title);
  }
  
  
  /**
   * Transform i18n dates into iso date
   * 
   * Works only for params with "date" in the name
   * 
   * @param array $params
   * @return array
   */
  protected function transformDates(array $params)
  {
    foreach($params as $name => $value)
    {
      if (is_array($value))
      {
        $value = $this->transformDates($value);  
      }
      else
      {
        if (strstr($name, 'date'))
        {
          $value = html_entity_decode(urldecode($value));
          $validator = new sfValidatorDate();
          $value = $validator->clean($value);
        }   
      }
      $params[$name] = $value;
    }

    return $params;
  }
  
  
  /**
   * Enhancement of redirect() method.
   * Allows giving "reqpass" style array with params to be overwritten
   * 
   * @param string or array $url
   */
  protected function ull_redirect($url)
  {
    if (is_array($url)) 
    {
      $params = _ull_reqpass_initialize($url);
      
      $params['module'] = $this->getModuleName(); 
      $params['action'] = $this->getActionName();
      
      $url = _ull_reqpass_build_url($params);
    }    
    
    $this->redirect($url);
  }
  
  
  /**
   * Prepare output for CSV export
   */
  protected function handleCsvExport()
  {
    if (isset($this->generator) && $this->getRequestParameter('export_csv') == 'true')
    {
      $this->setLayout(false);
      sfConfig::set('sf_web_debug', false);
      $this->setTemplate(sfConfig::get('sf_plugins_dir') . 
        '/ullCorePlugin/modules/ullTableTool/templates/exportAsCsv');
      $this->getResponse()->setContentType('application/csv');
      $this->getResponse()->setHttpHeader('Content-Disposition',
        'attachment;filename=' . ullCoreTools::sluggify($this->getResponse()->getTitle()) . '.csv;');
    }     
  }
 
}