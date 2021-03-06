<?php

/**
 * This class is a major component of the ullSearch framework.
 * It modifies given select queries using custom criteria groups.
 */
class ullSearch
{
  protected $criterionGroups;

  /**
   * Initializes a new ullSearch.
   *
   * @return a new ullSearch object
   */
  public function __construct()
  {
    $this->criterionGroups = array();
  }

  /**
   * Adds an array of ullSearchCritierionGroup to the
   * current search.
   *
   * @param $searchCriterionGroups an array of ullSearchCritierionGroup
   */
  public function addCriterionGroups(array $searchCriterionGroups)
  {
    foreach($searchCriterionGroups as $criterionGroup)
    {
      $this->addCriterionGroup($criterionGroup);
    }
  }

  /**
   * Internal function which adds a single criterion group to
   * the current search.
   * 
   * @param $newCriteriaGroup a ullSearchCritierionGroup
   */
  protected function addCriterionGroup(ullSearchCritierionGroup $newCriteriaGroup)
  {
    $this->criterionGroups[] = $newCriteriaGroup;
  }
  
  /**
   * Returns the ullSearchCritierionGroup of the current
   * search.
   * 
   * @return an array of ullSearchCritierionGroup
   */
  public function getCriterionGroups()
  {
    return $this->criterionGroups;
  }

  /**
   * Modifies a given select query to include the criteria of
   * this search.
   * 
   * @param $q The query to which our criteria should be added
   * @param $alias The table alias which refers to the searched class, e.g. 'x'
   * @return Doctrine_Query The modified query
   */
  public function modifyQuery($q, $alias)
  {
    if (count($this->getCriterionGroups()) == 0)
    {
      return $q;
    }
    
    $originalAlias = $alias;

    foreach ($this->getCriterionGroups() as $criterionGroup)
    {
      $queryParameter = array();
      $queryString = '';

      for($i = 0; $i < count($criterionGroup->subCriteria); $i++)
      {
        $alias = $originalAlias;
        $subCriterion = $criterionGroup->subCriteria[$i];

        if (!($subCriterion instanceof ullSearchCriterion))
        {
          throw new RuntimeException('Unsupported query class.');
        }
        
        $alias = $this->modifyAlias($q, $alias, $subCriterion);
        $newColumnName = $this->modifyColumnName($subCriterion->columnName);

        if ($subCriterion->isNot === true)
        {
          $queryString .= ' not (';
        }

        $queryClass = get_class($subCriterion);
        switch ($queryClass)
        {
          case 'ullSearchCompareCriterion':
            //we assume here that this is an array
            $searchWords = $subCriterion->compareValue;
            $queryString .= '(';
            for ($j = 0; $j < count($searchWords); $j++)
            {
              $searchWord = $searchWords[$j];
              $newCompareValue = '%' . $searchWord . '%';
              $queryString .= $alias . '.' . $newColumnName . ' ' . 'LIKE' . ' ?';
              $queryParameter[] = $newCompareValue;
              if ($j < (count($searchWords) - 1))
              {
                $queryString .= ' AND ';
              }
            }
            $queryString .= ')';
            break;

          case 'ullSearchCompareExactCriterion':
            $newCompareValue = '%' . $subCriterion->compareValue . '%';

            //can't we use orWhere here? how to set surrounding ( and ) ?
            $queryString .= $alias . '.' . $newColumnName . ' ' . 'LIKE' . ' ?';
            $queryParameter[] = $newCompareValue;
            break;

          case 'ullSearchBooleanCriterion':
            $newCompareValue = ($subCriterion->compareValue === true) ? 'TRUE' : 'FALSE';
            $queryString .= $alias . '.' . $newColumnName . ' ' . 'IS' . ' ' . $newCompareValue;
            break;

          case 'ullSearchForeignKeyCriterion':
            $queryString .= $alias . '.' . $newColumnName . ' ' . ' = ' . ' ?';
            $queryParameter[] = $subCriterion->compareValue;
            break;

          case 'ullSearchRangeCriterion':
          case 'ullSearchDateRangeCriterion':
          case 'ullSearchDateTimeRangeCriterion':
            if ($subCriterion->fromValue == null || $subCriterion->fromValue == '')
            {
              //from is not set, to is
              $queryOperator = ' <= ?';
              //since we don't have a real date time widget, cut off
              //the faked 00:00:00 and replace it
              $queryParameter[] = ($queryClass == 'ullSearchDateRangeCriterion') ?
                $subCriterion->toValue . ' 23:59:59' : (($queryClass == 'ullSearchDateTimeRangeCriterion') ?
                substr($subCriterion->toValue, 0, -8) . '23:59:59' : $subCriterion->toValue);
            }
            else
            {
              if ($subCriterion->toValue == null || $subCriterion->toValue == '')
              {
                //from is set, to isn't
                $queryOperator = ' >= ?';
                $queryParameter[] = $subCriterion->fromValue;
              }
              else
              {
                //from and to are set
                $queryOperator = ' between ? and ?';
                $queryParameter[] = $subCriterion->fromValue;
                //since we don't have a real date time widget, cut off
                //the faked 00:00:00 and replace it
                $queryParameter[] = ($queryClass == 'ullSearchDateRangeCriterion') ?
                  $subCriterion->toValue . ' 23:59:59' : (($queryClass == 'ullSearchDateTimeRangeCriterion') ?
                  substr($subCriterion->toValue, 0, -8) . '23:59:59' : $subCriterion->toValue);
              }
            }

            $queryString .= $alias . '.' . $newColumnName . $queryOperator;

            break;
          
          default:
            throw new RuntimeException("Invalid search criterion class.");
        }

        if ($subCriterion->isNot === true)
        {
          $queryString .= ')';
        }

        if (($i + 1) < count($criterionGroup->subCriteria))
        {
          $queryString .= ' or ';
        }
      }
      
      $q->addWhere($queryString, $queryParameter);
    }
    
    //printQuery($q->getSqlQuery());
    //var_dump($q->getParams());
    
    return $q;
  }
  
  //If needed, inheriting classes can override the following functions
  
  /**
   * This function modifies the current column name, allowing
   * for virtual column support.
   * See the ullFlowSearch class for a reference implementation.
   * 
   * @param $columnName The current column name
   * @return The modified column name
   */
  protected function modifyColumnName($columnName)
  {
    return $columnName;
  }
  
  /**
   * This function modifies the alias used and allows for
   * special functions like virtual columns.
   * See the ullFlowSearch class for a reference implementation.
   * 
   * @param $q The current doctrine query
   * @param $alias The current alias
   * @param $criterion The current search criterion
   * @return The modified alias
   */
  protected function modifyAlias(Doctrine_Query $q, $alias, ullSearchCriterion $criterion)
  {
    return $alias;
  }
  
  /**
   * This function is called before serialization.
   * It's there because ullFlowSearch needs to call it.
   * (Why is the default magic __sleep function not
   * callable from child classes?)
   * 
   * @return array with the fields to serialize
   */
  public function __sleep() {
    return array('criterionGroups');
  }
}
