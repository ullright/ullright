<?php

/**
 * ullCore tools
 *
 * @package    ullCore
 * @author     Klemens Ullmann
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class ullCoreTools
{

  /**
   * Search for a string in multiple columns
   * 
   * @param Doctrine_Query $q
   * @param string $search
   * @param array $columns
   * @return Doctrine_Query
   */
  public static function doctrineSearch($q, $search, $columns)
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
        $where[] = 'x.' . $col . ' LIKE ?'; 
      }
      $whereTopLevel[] = implode(' AND ', $where);

      $params = array_merge($params, $searchParams);
    }    

    $where = '(' . implode(' OR ', $whereTopLevel) . ')';
    
    $q->addWhere($where, $params);
    
    return $q;
  }
  
  /**
   * This filters a string into a "friendly" string for use in URL's. 
   *   It converts the string to lower case and replaces any non-alphanumeric 
   *   (and accented) characters with underscores.
   *
   * @param string $string
   * @return string
   */
  public static function sluggify($string)
  {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '_', $string);
    $string = preg_replace('/-+/', "-", $string);
    
    return $string;
  }
  
  /**
   * Orders the top level of an associative array by a given array
   * Keys which are not defined by $order remain unchanged at the end of return array
   * See ullCoreToolsTest.php for examples
   *  
   * @param $array array to order
   * @param $order array defining the expected order
   * @return array
   */
  public static function orderArrayByArray(array $array, array $order)
  {
    $ordered = array();
    
    foreach ($order as $key)
    {
      if (!key_exists($key, $array))
      {
        throw new InvalidArgumentException('Invalid key given: ' . $key);
      }
      $ordered[$key] = $array[$key];
      unset($array[$key]);     
    }
  
    return array_merge($ordered, $array);
  }
  

}

