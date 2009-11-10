<?php 

/**
 * ullQuery is a wrapper for Doctrine_Query which allows giving related columns
 * in the ull-relation syntax relative to a given base model
 * 
 * Example for ullVentoryItem: 'UllUser->username' selects the username of an item's owner
 * 
 * It automatically adds the necessary "from" clauses (=joins) and it administrates
 * a dictionary for relation aliases to re-use already created relations.
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullQuery
{
  protected
    $baseModel,
    $q,
    $relations = array(),
    $finished = false
  ;
  
  
  /**
   * Constructor
   * 
   * @param string $baseModel
   * @return none
   */
  public function __construct($baseModel)
  {
    $this->baseModel = $baseModel;
    
    $this->q = new Doctrine_Query;
    $this->q->from($this->baseModel . ' x');
  }
  
  
  /******************************************
   * Doctrine method equivalents
   ******************************************/
  
  
  /**
   * Add SELECT columns
   * 
   * @param mixed $columns    string or array
   * @return self
   */
  public function addSelect($columns)
  {
    if (!is_array($columns))
    {
      $columns = array($columns);
    }

    foreach ($columns as $column)
    {
      if ($selectColumn = $this->relationStringToDoctrineQueryColumn($column))
      {
        $this->q->addSelect($selectColumn);
      }
    }
    
    $this->addRelationsToQuery();
    
    return $this;
  }
  
  
  /**
   * Add WHERE clauses
   * 
   * Attention: it currently supports only a single where statement
   * Example: 'UllUser->Username = ?'
   * 
   * @param string $where   A valid doctrine WHERE string
   * @param $params
   * @return self
   */
  public function addWhere($where, $params = array())
  {
    $this->handleWhere($where, $params, false);
    
    return $this;
  }
  
  /**
   * Add OR WHERE clauses
   * 
   * Attention: it currently supports only a single where statement
   * Example: 'UllUser->Username = ?'
   * 
   * @param string $where   A valid doctrine WHERE string
   * @param $params
   * @return self
   */
  public function orWhere($where, $params = array())
  {
    $this->handleWhere($where, $params, true);
    
    return $this;
  }
  
  protected function handleWhere($where, $params = array(), $coordinatorIsOr)
  {
    preg_match('/^([a-z>_-])+/i', $where, $matches);
    //var_dump($where);
    //var_dump($matches);
    $search = $matches[0];
    $replace = $this->relationStringToDoctrineQueryColumn($search);

    $where = str_replace($search, $replace, $where);
    
    if ($coordinatorIsOr)
    {
      $this->q->orWhere($where, $params);
    }
    else
    {
      $this->q->addWhere($where, $params);
    }
    
    $this->addRelationsToQuery();
    
    return $this;
  }
  
 /**
   * Search for a string in multiple columns
   * 
   * @param string $searchString
   * @param array $columnsToSearch
   * @return ullQuery
   */
  public function addSearch($searchString, array $columnsToSearch)
  {
    $searchParts = explode(' ', $searchString);
    foreach ($searchParts as $key => $part)
    {
      $searchParams[] = '%' . $part . '%';
    }
    
    $whereTopLevel = array();
    $params = array();
    
    foreach($columnsToSearch as $col)
    {
      //let's replace aliases
      $col = $this->relationStringToDoctrineQueryColumn($col);
      
      $where = array();
      for ($i = 0; $i < count($searchParts); $i++)
      {
        $where[] = $col . ' LIKE ?'; 
      }
      $whereTopLevel[] = implode(' AND ', $where);

      $params = array_merge($params, $searchParams);
    }    

    $where = '(' . implode(' OR ', $whereTopLevel) . ')';
    
    $this->q->addWhere($where, $params);
    
    $this->addRelationsToQuery();
    
    return $this;
  }
  
  /**
   * Adds an ORDER BY query in front of the existing query part
   * 
   * e.g.
   * ORDER BY last_name, first_name
   * gets transformed to
   * ORDER BY location_name, last_name, first_name
   * 
   * @param $orderPrefix the ORDER BY query part to add
   * @return unknown_type
   */
  public function addOrderByPrefix($orderPrefix)
  {
    return $this->addOrderBy($orderPrefix, true);
  }
  
  /**
   * Add ORDER BY
   * 
   * @param string $orderBy the ORDER BY query part to add
   * @param boolean $addAsPrefix if true, adds $orderBy in front of the existing query part
   * @return self
   */
  public function addOrderBy($orderBy, $addAsPrefix = false)
  {
    $orderByArray = ullGeneratorTools::arrayizeOrderBy($orderBy);
    
    //if we are adding prefixes, we need to invert
    //the new ORDER BY parts because we insert not
    //at the end but in front
    if($addAsPrefix && count($orderByArray) >= 2)
    {
      $orderByArray = array_reverse($orderByArray);
    }
    
    foreach ($orderByArray as $orderBy)
    {
      if ($orderByColumn = $this->relationStringToDoctrineQueryColumn($orderBy['column']))
      {
        $newOrderString = $orderByColumn . ' ' . $orderBy['direction'];
        
        if ($addAsPrefix)
        {
            //retrieve the existing ORDER BY query part,
            //and add the new token in front
            $existingOrderString = implode(', ', $this->q->getDqlPart('orderby'));
            $this->q->orderBy($newOrderString);
            $this->q->addOrderBy($existingOrderString);
        }
        else
        {
          $this->q->addOrderBy($newOrderString);
        }
      }
    }
    
    $this->addRelationsToQuery();
    
    return $this;
  }
  
  
  /**
   * Return query sql
   * 
   * @return string
   */
  public function getSql()
  {
    return $this->q->getSql();
  }
  
  
  /**
   * Return query sql
   * 
   * @return Doctrine_Collection
   */
  public function execute($params = array(), $hydrationMode = null)
  {
    return $this->q->execute($params, $hydrationMode);
  }  
    
  
  
  /******************************************
   * Getter / Setter
   ******************************************/  
  
  
  /**
   * Returns the base model name
   * 
   * @return string
   */
  public function getBaseModel()
  {
    return $this->baseModel;
  }
  
  
  /**
   * Returns the Doctrine_Query object
   * 
   * @return Doctrine_Query
   */
  public function getDoctrineQuery()
  {
    return $this->q;
  }
  
  
  /**
   * Add relations to the internal list of relations in use
   * 
   * @param array $relations
   * @return none
   */
  public function addRelations(array $relations)
  {
    $nestedRelations = $this->nestPlainArray($relations);
    
    $this->relations = sfToolKit::arrayDeepMerge($this->relations, $nestedRelations);
  }
    
  
  /**
   * Returns the internal list of relations in use 
   * @return array
   */
  public function getRelations()
  {
    return $this->relations;
  }  
  
  
  /******************************************
   * Everything else
   ******************************************/  
  
  
  /**
   * Uses a relation string in the current query 
   * and returns the Doctrine query column.
   * 
   * Also registeres the necessary relations
   * 
   * Example for TestTable: 'UllUser->username'
   * Returns 'x.ulluser.username' and adds the relation to UllUser
   * 
   * @param string $column
   * @return string
   */
  protected function relationStringToDoctrineQueryColumn($column)
  {
    $translated = false;
    $finalModel = $this->baseModel;
    $finalColumn = $column;
    $relations = array();
    
    if (ullGeneratorTools::hasRelations($column))
    {
      $relations = ullGeneratorTools::relationStringToArray($column);
      $finalColumn = array_pop($relations);
      
      $finalModel = ullGeneratorTools::getFinalModelFromRelations($this->baseModel, $relations);
    }
    
    $finalModelTable = Doctrine::getTable($finalModel);
    
    if (!$finalModelTable->hasColumn($finalColumn))
    {
      // check for translated column      
      if (Doctrine::getTable($finalModel)->hasRelation('Translation'))
      {
        $relations[] = 'Translation';
        $finalModel = ullGeneratorTools::getFinalModelFromRelations($this->baseModel, $relations);
        $finalModelTable = Doctrine::getTable($finalModel);
        
        // ignore invalid columns, it could be an artificial column
        if (!$finalModelTable->hasColumn($finalColumn))
        {

          return false;
        }
        
        $translated = true;
      }
      else
      {

        // ignore invalid columns, it could be an artificial column
        return false;
      }
    }  

    $this->addRelations($relations);
    
    $alias = $this->relationStringToAlias(ullGeneratorTools::relationArrayToString($relations));
    
    if ($translated)
    {
      $this->q->addWhere($alias . '.lang = ?', substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2));
    }      
        
    return $alias . '.' . $finalColumn;
  }
  
  
  /**
   * Creates a doctrine query alias for a given relation string
   * 
   * @param $string
   * @return string
   */
  public function relationStringToAlias($string, $prependBaseAlias = true)
  {
    $alias = str_replace('>', '', $string);
    $alias = ullCoreTools::sluggify($alias);
    $alias = str_replace('-', '_', $alias);

    if ($alias == '')
    {
      $alias = 'x';
    }
    else
    {
      if ($prependBaseAlias)
      {
        $alias = 'x_' . $alias;   
      }
    }
    
    return $alias;
  }

  
  /**
   * Add the internally collected relations to the query
   * 
   * @param string $alias
   * @param array $relations
   * @return none
   */
  public function addRelationsToQuery($alias = 'x', $relations = array())
  {
    if (!count($relations))
    {
      $relations = $this->relations;
    }
    
    foreach ($relations as $relation => $subRelations)
    {
      $newAlias = $alias . '_' . $this->relationStringToAlias($relation, false);
      
      $from = $alias . '.' . $relation . ' '. $newAlias;
      
      $fromParts = $this->q->getDqlPart('from');
      if (!in_array($from, $fromParts))
      {
        $this->q->addFrom($from);
      }
      
      // This is necessary for Doctrine joins:
      $selectId = $alias . '.' . 'id';
      $selectParts = $this->q->getDqlPart('select');
      if (!in_array($selectId, $selectParts))
      {
        $this->q->addSelect($selectId);
      }
    
      if (count($subRelations))
      {
        $this->addRelationsToQuery($newAlias, $subRelations);
      }
    }
  }
  
  
  /**
   * Convert a plain array to a nested associative array
   * 
   * @param $plainArray
   * @return array
   */
  public function nestPlainArray(array $plainArray)
  {
    $nestedArray = array();
    
    while($relation = array_pop($plainArray))
    {
      if (!is_string($relation))
      {
        throw new InvalidArgumentException('Non-string element detected - Only plain arrays are allowed');
      }
      $nestedArray = array($relation => $nestedArray);
    }
    
    return $nestedArray;
  }
  
}