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
    $search_parts = explode(' ', $search);
    foreach ($search_parts as $key => $part)
    {
      $search_parts[$key] = '%' . $part . '%';
    }
    
    foreach($columns as $col)
    {
      $where = array();
      for ($i = 0; $i < count($search_parts); $i++)
      {
        $where[] = 'x.' . $col . ' LIKE ?'; 
      }
      $where = implode(' AND ', $where);
      
      $q->orWhere($where, $search_parts);
    }    
    
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
  

}

?>