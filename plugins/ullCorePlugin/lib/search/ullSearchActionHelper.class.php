<?php
class ullSearchActionHelper
{
  /**
   * Adds a new search criteria to the current search stored in
   * the session.
   *
   * @param $request The current request
   */
  public static function addSearchCriteria(sfRequest $request, sfUser $user)
  {
    $fields = $request->getParameter('fields');
    $newCriteriaString = $fields['columnSelect'];
    $moduleName = $request->getParameter('module');
    $searchFormEntries = $user->getAttribute($moduleName . '_searchFormEntries');

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

    $user->setAttribute($moduleName . '_searchFormEntries', $searchFormEntries);
  }

  /**
   * Removes an existing search criteria from the current search stored in
   * the session.
   *
   * @param $request The current request
   */
  public static function removeSearchCriteria(sfRequest $request, $removeCriteriaString, sfUser $user)
  {
    $moduleName = $request->getParameter('module');
    $searchFormEntries = $user->getAttribute($moduleName . '_searchFormEntries');

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
      //throw new RuntimeException("SearchFormEntry not found.");;
      //let's ignore this, most likely a 'double post'
    }

    $user->setAttribute($moduleName . '_searchFormEntries', $searchFormEntries);
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
  public static function retrieveSearchFormEntries($moduleName, ullSearchConfig $searchConfig, sfUser $user)
  {
    $searchFormEntries = $user->getAttribute($moduleName . '_searchFormEntries', null);

    if ($searchFormEntries === null)
    {
      $searchFormEntries = $searchConfig->getDefaultSearchColumns();
      $user->setAttribute($moduleName . '_searchFormEntries', $searchFormEntries);
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
  public static function addTransformedCriteriaToSearch(ullSearch $search, array $searchFormEntries, array $fields)
  {
    //$fields = $this->searchForm->getGenerator()->getForm()->getValues();
    unset($fields['columnSelect']);
    $criterionGroups = ullSearchFormEntryHelper::transformFieldsToCriteriaGroups($fields, $searchFormEntries);
    $search->addCriterionGroups($criterionGroups);
  }

  /**
   * Examines the request for different submit buttons originating
   * from the search form, calls add/remove criterion accordingly.
   *
   * @param $request the request
   * @return boolean true if a criterion was added or removed, false otherwise
   */
  public static function handleAddOrRemoveCriterionButtons(sfRequest $request, sfUser $user)
  {
    if ($request->isMethod('post'))
    {
      if ($request->getParameter('addSubmit'))
      {
        self::addSearchCriteria($request, $user);
        return true;
      }
      else
      {
        foreach ($request->getParameterHolder()->getNames() as $paramName)
        {
          if (strpos($paramName, 'removeSubmit_') === 0)
          {
            $criteriaString = substr($paramName, 13, -2);
            self::removeSearchCriteria($request, $criteriaString, $user);
            return true;
          }
        }
      }
    }

    return false;
  }

}
