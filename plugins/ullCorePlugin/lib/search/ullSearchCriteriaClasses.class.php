<?php

//This file contains classes related to ullSearch.

/**
 * Represents a group of ullSearchCriterion objects.
 */
class ullSearchCritierionGroup
{
  public $subCriteria; //array of criterions
}

/**
 * The abstract base class of all search criterions.
 * Contains a column name and a logic NOT-boolean.
*/
abstract class ullSearchCriterion
{
  public $columnName;
  public $isNot;
}

/**
 * This search criterion represents a 'natural'
 * comparision, e.g. "user admin" searches for
 * values containing "user" and "admin", disregarding
 * search term order.
 */
class ullSearchCompareCriterion extends ullSearchCriterion
{
  public $compareValue; //array of search terms
}

/**
 * This search criterion represents a simple
 * 'LIKE' comparision. 
 */
class ullSearchCompareExactCriterion extends ullSearchCompareCriterion
{
  //ToDo: add check 
  //$compareValue shall only be a single search term
}

/**
 * This search criterion represents a range search,
 * i.e. a search where lower/from and upper/to
 * bounds are specified.
 */
class ullSearchRangeCriterion extends ullSearchCriterion
{
  public $fromValue;
  public $toValue;
}

/**
 * This search criterion represents a range search
 * between two dates.
 */
class ullSearchDateRangeCriterion extends ullSearchRangeCriterion
{
  
}

/**
 * This search criterion represents a range search
 * between two dates with timestamps.
 */
class ullSearchDateTimeRangeCriterion extends ullSearchRangeCriterion
{
  
}

/**
 * This search criterion represents a simple boolean
 * YES/NO search.
 */
class ullSearchBooleanCriterion extends ullSearchCriterion
{
  //ToDo: add check 
  //$compareValue shall only be true or false (of type boolean).
  public $compareValue;
}

/**
 * This search criterion represents a search where the specified
 * column value is a foreign key id, i.e. the comparision operator
 * is '=' and not 'LIKE'.
 */
class ullSearchForeignKeyCriterion extends ullSearchCriterion
{
  public $compareValue;
}