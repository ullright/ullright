<?php 

/**
 * ullQuery is a wrapper for ullDoctrineQuery (which in turn extends from Doctrine_Query).
 * It allows giving related columns in the ull-relation syntax relative to a given base model.
 * 
 * Example for the base class ullVentoryItem: 'UllUser->username' selects the username of an item's owner
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
   * @param string $baseModel   Class name of the base model
   * @param string $indexBy     Column name for Doctrine's indexBy feature
   *                            Currently only baseModel columns are supported
   * @return none
   */
  public function __construct($baseModel, $indexBy = null)
  {
    $this->baseModel = $baseModel;
    
    $this->q = new ullDoctrineQuery();
    
    $doctrineFromString = $this->baseModel . ' x';
    $doctrineFromString .= ($indexBy) ? (' INDEXBY ' . $indexBy) : '';
    $this->q->from($doctrineFromString);
    
    // add discriminator column for base model
    $inheritanceKeyFields = $this->getInheritanceKeyField($this->baseModel);
    if ($inheritanceKeyFields != null)
    {
      $this->addSelect($inheritanceKeyFields);
    }
    
    // add indexby column
    if ($indexBy)
    {
      $this->addSelect($indexBy);
    }
  }
  
  
  /******************************************
   * Doctrine method equivalents
   ******************************************/
  
  /**
   * SELECT columns
   * 
   * @param mixed $columns    string or array
   * @return self
   */
  public function select($columns)
  {
    $this->handleSelect($columns, 'addSelect');
    
    return $this;
  }  
  
  /**
   * Add SELECT columns
   * 
   * @param mixed $columns    string or array
   * @return self
   */
  public function addSelect($columns)
  {
    $this->handleSelect($columns, 'addSelect');
    
    return $this;
  }
  
  /**
   * Handle select
   * 
   * @param mixed $columns    string or array
   * @param string method
   * @return self
   */
  protected function handleSelect($columns, $method)
  {
    if (!is_array($columns))
    {
      $columns = array($columns);
    }

    foreach ($columns as $column)
    {
      if ($selectColumn = $this->relationStringToDoctrineQueryColumn($column))
      {
        $this->q->$method($selectColumn);
      }
    }
    
    $this->addRelationsToQuery();
  }
  
  /**
   * WHERE clauses
   * 
   * Attention: it currently supports only a single where statement
   * Example: 'UllUser->Username = ?'
   * 
   * @param string $where   A valid doctrine WHERE string
   * @param $params
   * 
   * @return self
   */
  public function where($where, $params = array())
  {
    $this->handleWhere($where, $params, 'where');
    
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
    $this->handleWhere($where, $params, 'addWhere');
    
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
    $this->handleWhere($where, $params, 'orWhere');
    
    return $this;
  }
  
  /**
   * Internal function which handles adding where clauses to
   * the query; supports AND and OR.
   * 
   * @param $where      the where term to add
   * @param $params     params
   * @param $method     method name
   * 
   * @return self
   */
  protected function handleWhere($where, $params, $method)
  {
    // retrieve the where column name 
    preg_match('/^([a-z>_-])+/i', $where, $matches);
    $column = $matches[0];

    // get the doctrine name for it
    $doctrineColumn = $this->relationStringToDoctrineQueryColumn($column);
    
    // replace the column name in the where clause
    $where = str_replace($column, $doctrineColumn, $where);
    
    $this->q->$method($where, $params);
    
    $this->addRelationsToQuery();
  }   
  
  /**
   * WHERE IN
   * 
   * Alias for addWhereIn()
   * 
   * @param string $expr
   * @param array $params
   */
  public function whereIn($expr, $params = array())
  {
    return $this->andWhereIn($expr, $params);
  }  
  
  /**
   * Add WHERE IN
   * 
   * @param string $expr
   * @param array $params
   */
  public function andWhereIn($expr, $params = array())
  {
    $column = $this->relationStringToDoctrineQueryColumn($expr);
    $this->q->andWhereIn($column, $params);
    
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
   * ORDER BY
   * 
   * @param string $orderBy the ORDER BY query part to add, can also be an array already transformed
   *    by ullGeneratorTools::arrayizeOrderBy
   *    
   * @return self
   */
  public function orderBy($orderBy)
  {
    $this->handleOrderBy($orderBy, 'orderBy');
    
    return $this;
  }  
  
  /**
   * Add ORDER BY
   * 
   * @param string $orderBy the ORDER BY query part to add, can also be an array already transformed
   *    by ullGeneratorTools::arrayizeOrderBy
   *    
   * @return self
   */
  public function addOrderBy($orderBy)
  {
    $this->handleOrderBy($orderBy, 'addOrderBy');
    
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
    $this->handleOrderBy($orderPrefix, 'addOrderBy', true);
    
    return $this;
  }  
  
  /**
   * Handle ORDER BY
   * 
   * @param string $orderBy the ORDER BY query part to add, can also be an array already transformed
   *    by ullGeneratorTools::arrayizeOrderBy
   * @param boolean $addAsPrefix if true, adds $orderBy in front of the existing query part
   * @return self
   */
  protected function handleOrderBy($orderBy, $method, $addAsPrefix = false)
  {
    //if $orderBy is already an array, nothing will change
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
          $this->q->$method($newOrderString);
        }
      }
    }
    
    $this->addRelationsToQuery();
  }
  
  
  /**
   * Add a group by part
   * 
   * @param string $groupby
   * @return self
   */
  public function addGroupBy($groupby)
  {
    $col = $this->relationStringToDoctrineQueryColumn($groupby);
    
    $this->q->addGroupBy($col);
    
    return $this;
  }  
  
  
  /**
   * Simple Doctrine Query Aliases
   */
  
  /**
   * SQL limit funtion
   * 
   * @param int $limit
   * @return self
   */
  public function limit($limit)
  {
    $this->q->limit($limit);
    
    return $this;
  }
  
  
  /**
   * Set the hydration mode
   * 
   * @param unknown_type $mode
   */
  public function setHydrationMode($mode)
  {
    $this->q->setHydrationMode($mode);
    
    return $this;
  }
  
  /**
   * Return the root alias
   */
  public function getRootAlias()
  {
    return $this->q->getRootAlias();
  }
  
  /**
   * Return query sql
   * 
   * @return string
   */
  public function getSqlQuery()
  {
    return $this->q->getSqlQuery();
  }
  
  /**
   * Return query params
   * 
   * @return array
   */
  public function getParams()
  {
    return $this->q->getParams();
  }  
  
  
  /**
   * Execute query
   * 
   * @return Doctrine_Collection
   */
  public function execute($params = array(), $hydrationMode = null)
  {
    return $this->q->execute($params, $hydrationMode);
  }  

  
  /**
   * Return number of results
   * 
   * @return integer
   */
  public function count($params = array())
  {
    return $this->q->count($params);
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
   * @return ullDoctrineQuery
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
   * Example for base model TestTable: 'UllUser->username'
   * Returns 'x.ulluser.username' and adds the relation to UllUser
   * 
   * @param string $column
   * @return string
   */
  protected function relationStringToDoctrineQueryColumn($column)
  {
    $finalModel = $this->baseModel;
    $finalColumn = $column;
    $relations = array();
    
    //handle many to many relations:
    //these relations do not have a -> in their name, but their names
    //do not equal regular columns, so atm, we just throw them out
    //TODO: this results in a separate query executed by Doctrine later on
    //Is there a way we can modify the query at this point to prevent this?
    if (Doctrine::getTable($finalModel)->hasRelation($column))
    {
      $relation = Doctrine::getTable($finalModel)->getRelation($column);
      if ($relation instanceof Doctrine_Relation_Association)
      {
        return null;
      }
    }
    
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
        
        // Leave invalid columns untouched, it could be an artificial column
        if (!$finalModelTable->hasColumn($finalColumn))
        {
          
          return $column;
        }
      }
      else
      {
        
        // Leave invalid columns untouched, it could be an artificial column
        return $column;
      }
    }  

    $this->addRelations($relations);
    
    $alias = $this->relationStringToAlias(ullGeneratorTools::relationArrayToString($relations));
    
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
   * Adds the internally collected relations to the query
   * 
   * @param string $alias     Doctrine base alias
   * @param array $relations  Tree of relations relative to the base model 
   * Example:
   * 
   * array(2) {
   *  ["UllLocation"]=>
   *  array(1) {
   *    ["Translation"]=>
   *    array(0) {
   *    }
   *  }
   * ["UllJobTitle"]=>
   *  array(0) {
   *  }
   *}
   * @param $baseModel        Class name of the base model
   * 
   * @return none
   */
  public function addRelationsToQuery($alias = 'x', $relations = null, $baseModel = null)
  {
    if ($relations === null)
    {
      $relations = $this->relations;
    }
    
    if (!is_array($relations))
    {
      throw new InvalidArgumentException('parameter "relations" must be an array');
    }
    
    if (!$baseModel)
    {
      $baseModel = $this->getBaseModel();
    }

    foreach ($relations as $relation => $subRelations)
    {
      $newAlias = $alias . '_' . $this->relationStringToAlias($relation, false);
      
      $doctrineFromString = $alias . '.' . $relation . ' ' . $newAlias;
      
      //Add culture as conditional-join instead of where-clause to prevent missing
      // rows in the following scenario:
      // Basemodel with a optional relation to a second model which has translated columns
      // Using "WHERE" instead of a conditional join resulted in missing rows in case
      // a Basemodel row had no relation to the second model.
      // Reason: the 'WHERE SecondModel.Translation = "en"' part kicked out the row 
      if ($relation == 'Translation')
      {
        $doctrineFromString .= 
          ' WITH ' . 
          $newAlias . 
          '.lang = "' . 
          substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2) .
          '"'
        ;
      }
      
      $fromParts = $this->q->getDqlPart('from');
      
      $doctrineRelation = Doctrine::getTable($baseModel)->getRelation($relation);
      
      if (!in_array($doctrineFromString, $fromParts))
      { 
        $this->q->addFrom($doctrineFromString);
        
        // add discriminator column
        $inheritanceKeyFields = $this->getInheritanceKeyField($doctrineRelation->getClass());
        
        if ($inheritanceKeyFields != null)
        {
          foreach($inheritanceKeyFields as $keyField)
          {
            $this->q->addSelect($newAlias . '.' . $keyField);
          }
        }
        
        // Hardcoded workaround for UllUser because we can't detect the discriminator column for the parent entity
        if ($doctrineRelation->getClass() == 'UllEntity')
        {
          $this->q->addSelect($newAlias . '.type');
        }
      }
      
      // Select the local identifiers. This is necessary for Doctrine joins:
      $identifiers = Doctrine::getTable($baseModel)->getIdentifier();

      if (!is_array($identifiers))
      {
        $identifiers = array($identifiers);
      }
      
      foreach ($identifiers as $identifier)
      {
        $selectId = $alias . '.' .  $identifier;
        
        $selectParts = $this->q->getDqlPart('select');
        if (!in_array($selectId, $selectParts))
        {
          $this->q->addSelect($selectId);
        }
      }
    
      // Call the method recursivly for subrelations
      if (count($subRelations))
      {
        $this->addRelationsToQuery($newAlias, $subRelations, $doctrineRelation->getClass());
      }
    }
  }
  
  
  /**
   * Inspect the inheritance map of a model and
   * retrieve keyFields, if any.
   * 
   * @param unknown_type $modelName
   * @return array keyFields, if there are none, null
   */
  protected function getInheritanceKeyField($modelName)
  {
    $inheritanceMap = Doctrine::getTable($modelName)->getOption('inheritanceMap');
    $inheritanceFieldKeys = array_keys($inheritanceMap);
    if (count($inheritanceFieldKeys) > 0)
    {
      return $inheritanceFieldKeys;
    }
    else
    {
      return null;
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