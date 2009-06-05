<?php

/**
 * This class provides helpers for handling search form entries.
 * The central function, transformFieldsToCritieraGroups, is
 * responsible for converting posted form fields to criteria
 * groups which are usable with the ull search framework.
 */
class ullSearchFormEntryHelper
{
  /**
   * Transforms an array of form fields and search form entries
   * into search criteria groups. The array returned can be
   * directly fed to the ull search classes.
   * 
   * @param $fields An already validated and sanitized array of
   *                form fields
   * @param $searchFormEntries An array of search form entries
   * @return array containing search criteria groups
   */
  public static function transformFieldsToCriteriaGroups(array $fields, array $searchFormEntries)
  {
    $criterionGroups = array();
    $notFields = array();

    $fieldKeys = array_keys($fields);
    for ($i = 0; $i < count($fieldKeys); $i++)
    {
      $fieldKey = $fieldKeys[$i];
      $fieldValue = $fields[$fieldKeys[$i]];
      $typeString = substr($fieldKey, 0, strpos($fieldKey, '_'));
      $searchKeyString = substr($fieldKey, strpos($fieldKey, '_') + 1);
      $sfeUUID = substr($searchKeyString, strpos($searchKeyString, '_') + 1);

      if ($typeString == 'not')
      {
        $notFields[$sfeUUID] = true;
        continue;
      }

      $currentSfe = null;
      foreach($searchFormEntries as $searchFormEntry)
      {
        if ($searchFormEntry->uuid == $sfeUUID)
        {
          $currentSfe = $searchFormEntry;
          break;
        }
      }
      if ($currentSfe === null)
      {
        throw new RuntimeException('A field without SearchFormEntry is invalid.');
      }

      $searchFieldColumn = $currentSfe->__toString();

      //let's choose the search class we need
      switch ($typeString)
      {
        case 'rangeFrom':
          if (($fieldValue == NULL || $fieldValue == '') &&
          ($fields[$fieldKeys[$i + 1]] == NULL || $fields[$fieldKeys[$i + 1]] == ''))
          {
            unset($fields[$fieldKey]);
            continue 2;
          }

          $tempCriterion = new ullSearchRangeCriterion();
          $tempCriterion->columnName = $searchFieldColumn;
          $tempCriterion->fromValue = $fieldValue;
          $tempCriterion->toValue = $fields[$fieldKeys[$i + 1]];
          //skip next one (the 'to')
          $i++;

          break;

        case 'boolean':
          $tempCriterion = new ullSearchBooleanCriterion();
          $tempCriterion->columnName = $searchFieldColumn;
          $tempCriterion->compareValue = ($fieldValue == '1') ? true : false;
          break;

        case 'foreign':
          if ($fieldValue == NULL)
          {
            unset($fields[$fieldKey]);
            continue 2;
          }

          $tempCriterion = new ullSearchForeignKeyCriterion();
          $tempCriterion->columnName = $searchFieldColumn;
          $tempCriterion->compareValue = $fieldValue;
          break;

        default:
          if ($fieldValue == NULL || $fieldValue == '')
          {
            unset($fields[$fieldKey]);
            continue 2;
          }

          $trimmedFieldValue = trim($fieldValue);
          if (substr($trimmedFieldValue, 0, 1) == '"' && substr($trimmedFieldValue, -1) == '"')
          {
            $tempCriterion = new ullSearchCompareExactCriterion();
            $trimmedFieldValue = substr($trimmedFieldValue, 1, strlen($trimmedFieldValue) - 2);
            $tempCriterion->compareValue = $trimmedFieldValue;
          }
          else
          {
            //since this is a non-exact search we split the
            //search terms
            $tempCriterion = new ullSearchCompareCriterion();
            $tempCriterion->compareValue = preg_split ("/\s+/", $trimmedFieldValue);
          }

          $tempCriterion->columnName = $searchFieldColumn;

      }

      if (isset($notFields[$searchKeyString]) && $notFields[$searchKeyString] === true)
      {
        $tempCriterion->isNot = true;
      }

      if (!(isset($criterionGroups[$sfeUUID])))
      {
        $criterionGroups[$sfeUUID] = new ullSearchCritierionGroup();
      }
      $criterionGroups[$sfeUUID]->subCriteria[] = $tempCriterion;
    }

    return $criterionGroups;
  }
  
  /**
   * Returns the next valid uuid for an array of search field entries.
   * 
   * @param $searchFormEntries The search form entries
   * @return integer The next id
   */
  public static function findNextSearchFormEntryId(array $searchFormEntries)
  {
    $id = 0;
    foreach($searchFormEntries as $sfe)
    {
      if ($sfe->uuid > $id)
      {
        $id = $sfe->uuid;
      }
    }
    
    return ($id + 1);
  }
}