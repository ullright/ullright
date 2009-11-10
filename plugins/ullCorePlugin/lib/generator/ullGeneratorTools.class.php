<?php

/**
 * ullGenerator tools
 *
 * @package    ullCore
 * @author     Klemens Ullmann-Marx
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class ullGeneratorTools
{

  /**
   * Search for a string in multiple columns
   * 
   * Note: This should be deprecated.
   * Use ullQuery class if possible!
   * 
   * @param Doctrine_Query $q
   * @param string $search
   * @param array $columns
   * @deprecated
   * @return Doctrine_Query
   */
  public static function doctrineSearch($q, $search, $columns, $addToRootAlias = true)
  {
    $searchParts = explode(' ', $search);
    foreach ($searchParts as $key => $part)
    {
      $searchParams[] = '%' . $part . '%';
    }
    
    $whereTopLevel = array();
    $params = array();
    
    foreach($columns as $col)
    {
      $where = array();
      for ($i = 0; $i < count($searchParts); $i++)
      {
        $rootAlias = $addToRootAlias ? 'x.' : '';
        $where[] = $rootAlias . $col . ' LIKE ?'; 
      }
      $whereTopLevel[] = implode(' AND ', $where);

      $params = array_merge($params, $searchParams);
    }    

    $where = '(' . implode(' OR ', $whereTopLevel) . ')';
    
    $q->addWhere($where, $params);
    
    return $q;
  }
  
  
  /**
   * Create a i18n column name
   * @param $columnName
   * @return unknown_type
   */
//TODO: remove, isn't use anywhere  
  
//  public static function makeI18nColumnName($columnName)
//  {
//    $lang = substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2);
//    
//    return $columnName . '_translation_' . $lang;
//  }
  
  
  /**
   * Transform an array of relations to string representation
   * 
   * @param array $relations
   * @return unknown_type
   */
  public static function relationArrayToString(array $relations)
  {
    return implode('->', $relations);  
  }
  
  
  /**
   * Transform a relations string to array
   * 
   * @param $string
   * @return array
   */
  public static function relationStringToArray($string)
  {
    return explode('->', $string);  
  }  
  
  
  /**
   * Arrayize relations
   * 
   * @param mixed $relations    A relation string or an array of relations
   * @return array
   */
  public static function arrayizeRelations($relations)
  {
    if (!is_array($relations))
    {
      return ullGeneratorTools::relationStringToArray($relations);
    }  
    
    return $relations;
  }  
  
  
  /**
   * Check if a relation string has relations
   * 
   * @param $string
   * @return boolean
   */
  public static function hasRelations($string)
  {
    if (strpos($string, '->'))
    {
      return true;
    }
    
    return false;
  }
  
  
  /**
   * Get the model class of the final relation
   *  
   * @param string $model        The name of the originating model
   * @param mixed $relations    array or string of relations
   * @return string       Name of the model of the final relation
   */
  public static function getFinalModelFromRelations($baseModel, $relations)
  {
    $relations = UllGeneratorTools::arrayizeRelations($relations);
    
    $finalModel = Doctrine::getTable($baseModel)->getRelation(array_shift($relations))->getClass();
    
    if (count($relations))
    {
      $finalModel = self::getFinalModelFromRelations($finalModel, $relations);
    }
    
    return $finalModel;
  }  
  
  
  /**
   * Check if the column exists.
   * 
   * Also accepts relation columns in format 'Relation->column'
   * 
   * @param string $model     Model name
   * @param string $column    Relation column name
   * @return boolean
   */
  public static function isValidColumn($model, $column)
  {
    // check if the model exists
    try
    {
      $table = Doctrine::getTable($model);
    }
    catch (Exception $e)
    {
      return false;
    }
    
    // Do we have relations in the column name?
    if (ullGeneratorTools::hasRelations($column))
    {
      $relations = UllGeneratorTools::relationStringToArray($column);
      $nativeColumn = array_pop($relations);
      $finalModel = UllGeneratorTools::getFinalModelFromRelations($model, $relations);
      
      return self::isValidColumn($finalModel, $nativeColumn);
    }
    
    // For native columns (without relations)
    else
    {
      if ($table->hasColumn($column))
      {
        return true;
      }
      
      if ($table->hasRelation('Translation'))
      {
        $finalModel = UllGeneratorTools::getFinalModelFromRelations($model, 'Translation');
        return self::isValidColumn($finalModel, $column);
      }
    }
    
    return false;
  }
  
  
  /**
   * Check for valid column names
   * 
   * @param string $model     Model name
   * @param array $columns    List of column names (also with relations)
   * @return boolean
   * 
   * @throws InvalidArgumentException
   */
  public static function validateColumnNames($model, array $columns)
  {
    $errors = array();
    
    foreach ($columns as $column)
    {
      if (!ullGeneratorTools::isValidColumn($model, $column))
      {
        $errors[] = $column;
      }  
    }
    
    if (count($errors))
    {
      throw new InvalidArgumentException('Unkown columns: ' . implode(',', $errors));
    }
    
    return true;
  }
  
  
  /**
   * Build a human readable relation label
   * 
   * @param string $baseModel     The base model name (Example: 'UllUser')
   * @param mixed $relations      Array or string with relations
   *                              Example array: array('UllLocation', 'Creator')            
   *                              Example string: 'UllLocation->Creator'
   * @return string               Example: 'Location Created by'
   */
  public static function buildRelationsLabel($baseModel, $relations)
  {
    $relations = ullGeneratorTools::arrayizeRelations($relations);
    
    // Check for custom relation names
    if ($label = ullTableConfiguration::buildFor($baseModel)->getCustomRelationName($relations))
    {
      return $label;
    }
    
    // Build relation label
    $labels = array();
    
    foreach ($relations as $relation)
    {
      $newBaseModel = ullGeneratorTools::getFinalModelFromRelations($baseModel, $relation);
      $label = ullTableConfiguration::buildFor($newBaseModel)->getForeignRelationName($relation);
      
      $labels[] = $label; 
      
      $baseModel = $newBaseModel;
    }

    return implode(' ', $labels);
  }  
  
  /**
   * Converts a orderBy string in the uri format to Doctrine query format
   * 
   * Example input: subject|UllUser->username-desc
   * Example output: subject, UllUser->username desc
   *  
   * @param $orderBy
   * @return string
   */
  public static function convertOrderByFromUriToQuery($orderBy)
  {
    $orderBy = str_replace('|', ', ', $orderBy);
    $orderBy = str_replace(':', ' ', $orderBy);
    
    return $orderBy;
  }

  
  /**
   * Converts a Doctrine query orderBy string to uri format
   * 
   * Reverse function for convertOrderByFromUriToQuery()
   * 
   * @param $orderBy
   * @return string
   */
  public static function convertOrderByFromQueryToUri($orderBy)
  {
    $orderBy = str_replace(', ', '|', $orderBy);
    $orderBy = str_replace(' ', ':', $orderBy);
    
    return $orderBy;
  }
  
  
  /**
   * Convert a Doctrine queryBy string to array
   * 
   * Format:
   * 
   * array(
   *   0 => array(
   *     'column'    => 'my_column',
   *     'direction' => 'desc',
   *   ),
   *   1 => ...
   * )
   *      
   *      
   * @param mixed $orderBy  String or array
   * @return array
   */
  public static function arrayizeOrderBy($orderBy)
  {
    if (is_array($orderBy))
    {
      return $orderBy;
    }
    
    $columnsArray = array();
    
    $columns = explode(',', $orderBy);
    foreach ($columns as $column)
    {
      $columnArray = array();
      
      $column = trim($column);
      $parts = explode(' ', $column);
      
      $columnArray['column'] = $parts[0];
      
      if (isset($parts[1]) && strtolower($parts[1]) == 'desc')
      {
        $columnArray['direction'] = 'desc';
      }
      else
      {
        $columnArray['direction'] = 'asc';
      }
          
      $columnsArray[] = $columnArray;
    }
    
    return $columnsArray;
  }    
    
  
  /**
   * Convert a orderBy array to Doctrine queryBy string
   * 
   * Reverse function for arrayizeOrderBy()
   *      
   * @param array $array
   * @return string
   */  
  public static function stringizeOrderBy($array)
  {
    //normalize single column orderBy statement
    if (is_string(reset($array)))
    {
      $array = array($array);
    }  
    
    $columnsOrderBy = array();
    
    foreach ($array as $orderBy)
    {
//      var_dump($orderBy);
      $columnsOrderBy[] = $orderBy['column'] . ' ' . $orderBy['direction'];
    }
    
    return implode(', ', $columnsOrderBy);
  }
  
  
}

