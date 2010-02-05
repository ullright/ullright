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
  
  /**
   * Formats given seconds to HH:mm
   * 
   * @param integer $time
   * @return string
   */
  public static function timeToString($time)
  {
    $return = '';
    if($time < 0)
    {
      $return .= '- ';
      $time = -$time;
    }
    if ($time >= 3600)
    {
      $hours = floor($time / 3600);
      $minutes = round(bcmod($time, 3600) / 60);
    }
    else
    {
      $hours = 0;
      $minutes = round($time / 60);
    }
      
    if (strlen($minutes) == 1)
    {
      $minutes = '0' . $minutes;
    }
    
    $return .= $hours . ':' . $minutes;
    
    return $return;
  }
  
  
  /**
   * Convert a human readable time to iso time
   * 
   * Example: 1:2 -> 01:02:00
   * 
   * @param $time
   * @return string
   */
  public static function humanTimeToIsoTime($time)
  {
    $parts = explode(':', $time);
    $hour = $parts[0];
    $minute = $parts[1];
    $second = '00';
    
    if (strlen($hour) == 1)
    {
      $hour = '0' . $hour;
    }
    
    if (strlen($minute) == 1)
    {
      $minute = '0' . $minute;
    }
    
    return $hour . ':' . $minute . ':' . $second;
  }
  
  
  /**
   * Convert iso time to human readable time
   * 
   * Example: 01:20:00 -> 1:20
   * 
   * @param $time
   * @return unknown_type
   */
  public static function isoTimeToHumanTime($time)
  {
    $parts = explode(':', $time);
    $hour = $parts[0];
    $minute = $parts[1];    
    if (substr($hour,0,1) == 0)
    {
      $hour = substr($hour,1,1);
    }
    
    return $hour . ':' . $minute;
  }
  
  
  /**
   * Deletes all files in the given directory which don't have the given extenstions
   * 
   * @param $path
   * @param $extensionsToKeep
   * @return array or null
   */
  public static function clearDirectoryByFileExtension($path, $extensionsToKeep = array())
  {    
    if (!is_dir($path))
    {
      throw new InvalidArgumentException('Directory not found: ' . $path);
    }
    
    
    $ignoreList = array('.', '..');

    $cleared = array();
    
    $files = scandir($path);

    foreach ($files as $file)
    {
      $parts = explode('.', $file);
      {
        if (
          !in_array($file, $ignoreList) 
          && !in_array($parts[count($parts) - 1], $extensionsToKeep)
        )
        {
          unlink($path . '/' . $file);
          $cleared[] = $file; 
        }
      }
    }
    
    return count($cleared) ? $cleared : false;
  }
  
  
  /**
   * Check whether a directory is empty
   * 
   * @param $path
   * @return boolean
   */
  public static function isEmptyDir($path)
  {
    if (!is_dir($path))
    {
      throw new InvalidArgumentException('Directory not found: ' . $path);
    }
    
    if (($files = scandir($path)) && count($files) <= 2) 
    {
      return true;
    }
    
    return false;
  }
  
  
  /**
   * Returns the filename of the first file in the given directory
   * 
   * @param string $path
   * @param string $excludePattern    A regular expression to exclude
   * @return unknown_type
   */
  public static function getFirstFileInDirectory($path, $excludePattern = null)
  {
    if (!is_dir($path))
    {
      throw new InvalidArgumentException('Directory not found: ' . $path);
    }
    
    $ignoreList = array('.', '..');
    
    $files = scandir($path);
    
    foreach ($files as $file)
    {
      if (in_array($file, $ignoreList))
      {
        continue;
      }
      
      if ($excludePattern)
      {
        if (preg_match($excludePattern, $file))
        {
          continue;    
        }
      }
      
      return $file;
    }
  }
  

  /**
   * Add params to an uri
   * 
   * Automatically detects if we need ? or & as separator
   * 
   * @param string $uri
   * @param string $params  in the format param1=value1&param2=value2
   * @return string
   */
  public static function appendParamsToUri($uri, $params)
  {
    $separator = (strpos($uri, '?')) ? '&' : '?';

    return $uri . $separator . $params;
  }

  
  /**
   * Function to debug arrays which contain Doctrine_Record objects
   *  
   * @param $array
   * @param boolean $verbose
   * @return array
   */
  public static function debugArrayWithDoctrineRecords($array, $verbose = false)
  {
    $return = array();
    
    foreach ($array as $key => $value)
    {
      if (is_object($value))
      {
        if ($verbose == true)
        {
          if ($value instanceof Doctrine_Record)
          {
            $value = $value->toArray();
          }
        }
        else
        {
          $toString = null;
          
          if ($value instanceof Doctrine_Record && method_exists($value, '__toString'))
          {
            $toString = $value->__toString();
          }
          
          $value = 'Object "' . get_class($value) . '"'  . (($toString) ? ' with __toString() value: "' . $toString . '"' : '');
        }
      }
      
      if (is_array($value))
      {
        $value = self::debugArrayWithDoctrineRecords($value);
      }
      
      $return[$key] = $value;
    }
    
    return $return;
  }
  
  /**
   * Prepares a value for export to csv format
   * 
   * @param string $string
   * @return string
   */
  public static function prepareCsvColumn($string)
  {
    $string = html_entity_decode($string, ENT_QUOTES);
    $string = strip_tags($string);
    
    if ($string && !is_numeric($string))
    {
      $string = str_replace('"', '\\"', $string);
      $string = '"' . $string . '"';
    }
    
    return $string;
  }

}