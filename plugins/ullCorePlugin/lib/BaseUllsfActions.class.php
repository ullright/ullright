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

    $this->redirectUnless(UllUserTable::hasGroup($group), 'ullUser/noaccess');
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
     
    $this->redirectUnless(UllUserTable::hasPermission($permission), 'ullUser/noaccess');
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
   * Adds a new search criteria to the current search stored in
   * the session.
   * 
   * @param $request The current request
   */
  public function executeAddSearchCriteria(sfRequest $request)
  {
    if ($request->isMethod('post'))
    {
      $fields = $request->getParameter('fields');
      $newCriteriaString = $fields['columnSelect'];
      $moduleName = $request->getParameter('module');
      $searchFormEntries = $this->getUser()->getAttribute($moduleName . '_searchFormEntries');

      $newSfe = new ullSearchFormEntry($newCriteriaString);
      $newSfe->uuid = ullSearchFormEntryHelper::findNextSearchFormEntryId($searchFormEntries);
      
      $found = false;
      foreach($searchFormEntries as $sfe)
      {
        if ($sfe->equals($newSfe))
        {
          $sfe->multipleCount++;
          $found = true;
          break;
        }
      }

      if (!$found)
      {
        $searchFormEntries[] = $newSfe;
      }

      $this->getUser()->setAttribute($moduleName . '_searchFormEntries', $searchFormEntries);
      $this->redirect($this->getUriMemory()->get('search'));
    }
  }

   /**
   * Removes an existing search criteria from the current search stored in
   * the session.
   * 
   * @param $request The current request
   */
  public function executeRemoveSearchCriteria(sfRequest $request)
  {
    $removeCriteriaString = $request->getParameter('criteriaName');
    $moduleName = $request->getParameter('module');
    $searchFormEntries = $this->getUser()->getAttribute($moduleName . '_searchFormEntries');

    $position = strrpos($removeCriteriaString, '_');

    if ($position === false)
    {
      throw new RuntimeException("Invalid criterion string.");
    }

    $uuid = substr($removeCriteriaString, $position + 1);

    $found = false;
    foreach($searchFormEntries as $sfeKey => $sfe)
    {
      if ($sfe->uuid == $uuid)
      {
        $found = true;
        if ($sfe->multipleCount > 1)
        {
          $sfe->multipleCount--;
        }
        else
        {
          unset($searchFormEntries[$sfeKey]);
        }
        break;
      }
    }

    if ($found === false)
    {
      throw new RuntimeException("SearchFormEntry not found.");;
    }

    $this->getUser()->setAttribute($moduleName . '_searchFormEntries', $searchFormEntries);
    $this->redirect($this->getUriMemory()->get('search'));
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
   * Retrieves the current search form entries from the session.
   * If there aren't any, the default ones are created by the
   * given ullSearchConfig class.
   * 
   * @param $moduleName The current module name
   * @param $searchConfig The search config
   * @return array The current search form entries 
   */
  public function retrieveSearchFormEntries($moduleName, ullSearchConfig $searchConfig)
  {
    $searchFormEntries = $this->getUser()->getAttribute($moduleName . '_searchFormEntries', null);

    if ($searchFormEntries === null)
    {
      $searchFormEntries = $searchConfig->getDefaultSearchColumns();
      $this->getUser()->setAttribute($moduleName . '_searchFormEntries', $searchFormEntries);
    }
    
    return $searchFormEntries;
  }
  
  /**
   * Transforms posted form fields and search field entries into
   * search criteria groups, and adds these groups to the search
   * object.
   * 
   * @param $search The current ullSearch
   * @param $searchFormEntries The search form entries
   */
  public function addTransformedCriteriaToSearch(ullSearch $search, array $searchFormEntries)
  {
    $fields = $this->searchForm->getGenerator()->getForm()->getValues();
    $criterionGroups = ullSearchFormEntryHelper::transformFieldsToCriteriaGroups($fields, $searchFormEntries);
    $search->addCriterionGroups($criterionGroups);
  }
}