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
   * Create a valid html id
   * 
   * @param string $string
   * @return string
   */
  public static function htmlId($string)
  {
    return str_replace('-', '_', self::sluggify($string));
  }
  
  /**
   * Orders the top level of an associative array by a given array
   * Keys which are not defined by $order remain unchanged at the end of return array
   * See ullCoreToolsTest.php for examples
   *  
   * @param $array array to order
   * @param $order array defining the expected order
   * @param $strict boolean throw an exception if an invalid key is given
   * @return array
   */
  public static function orderArrayByArray(array $array, array $order, $strict = true)
  {
    $ordered = array();
    
    foreach ($order as $key)
    {
      if (key_exists($key, $array))
      {
        $ordered[$key] = $array[$key];
        unset($array[$key]);
      }
      else
      {
        if ($strict)
        {
          throw new InvalidArgumentException('Invalid key given: ' . $key);
        }        
      }     
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
    if (substr($hour, 0, 1) === '0' && strlen($hour) > 1)
    {
      $hour = substr($hour, 1, 1);
    }
    
    return $hour . ':' . $minute;
  }
  
  /**
   * Enumerates all files in a given directory and passes
   * them to a custom callback function.
   * If the callback returns true, the file gets added to an
   * array, which this function returns.
   * 
   * @param $path the directory to walk
   * @param function $callback the callback function to call for each file
   * @param $callbackParam one callback parameter if needed
   * @return array of file paths for which the callback returned true
   */
  public static function walkDirectory($path, $callback, $callbackParam = array())
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
      {
        if (!in_array($file, $ignoreList))
        {
          //should we check beforehand with is_callable?
          if (call_user_func_array($callback, array($path, $file, &$callbackParam)))
          {
            $cleared[] = $file; 
          }
        }
      }
    }
    
    return $cleared;
  }
  
  /**
   * Deletes a file in the given directory if its extension is
   * not in the extensionsToKeep-array.
   *
   * @param $path The directory
   * @param $file The file name
   * @param $extensionsToKeep array of extensions
   * @return true if the file was deleted, false otherwise
   */
  public static function deleteFileIfNotExtension($path, $file, $extensionsToKeep = array())
  {
    $extension = pathinfo($file, PATHINFO_EXTENSION);
    
    if (!in_array(strtolower($extension), array_map('strtolower', $extensionsToKeep)))
    {
      unlink($path . DIRECTORY_SEPARATOR . $file);
      return true;
    }

    return false;
  }
  
  /**
   * Deletes a file in the given directory if it is not a
   * valid image file or if its type is not contained
   * in the typesToKeep-array.
   *
   * @param $path The directory
   * @param $file The file name
   * @param $typesToKeep array of types
   * @return true if the file was deleted, false otherwise
   */
  public static function deleteFileIfNotFromType($path, $file, $typesToKeep = array())
  {
    try 
    {
      $image = new Imagick($path . DIRECTORY_SEPARATOR . $file);

      if (!in_array($image->getImageFormat(), $typesToKeep))
      {
        throw new UnexpectedValueException();
      }
    }
    catch (Exception $e)
    {
      unlink($path . DIRECTORY_SEPARATOR . $file);
      return true;
    }
    
    return false;
  }
  
  /**
   * Checks if a file is an image
   * 
   * @param string $path					
   * @param optional string $file	filename. If empty $path is expected to be a full path including the filename
   * @param optional array $validTypes			List of valid mimetypes. Default = web images
   * @return boolean
   */
  
  public static function isValidImage($path, $file = null, $validTypes = null)
  {
    if ($file) 
    {
      $path = $path . DIRECTORY_SEPARATOR . $file;
    }
    
    if ($validTypes === null)
    {
      $validTypes = ullValidatorFile::getWebImageMimeTypes();
    }
    
    // PHP 5.2 does not support constant FILEINFO_MIME_TYPE
//    $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type aka mimetype extension
    $finfo = finfo_open(FILEINFO_MIME); // return mime type aka mimetype extension
    
    $mimeType = (finfo_file($finfo, $path));
    $mimeType = explode(';', $mimeType);
    $mimeType = $mimeType[0];
    
    finfo_close($finfo);
    
    if (!in_array($mimeType, $validTypes))
    {
      return false;
    }
    
    return true;
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
   * Debug an invalid sfForm and dump the errors
   * 
   * @param sfForm $form
   * @param boolean $return
   * 
   * @return string
   */
  public static function debugFormError(sfForm $form, $return = false)
  {
    $output = '';
    
    $output .= 'Form class: ' . get_class($form) . "\n";
      
    foreach ($form->getErrorSchema()->getErrors() as $key => $error)
    {
      $output .= $key . ': ' . $error->getMessage() . "\n";
    }    
    
    if ($return)
    {
      return $output;
    }
    else
    {
      var_dump($output);
    }
  }
  
  /**
   * Prepares a value for export to csv format
   * 
   * @param string $string
   * @return string
   */
  public static function prepareCsvColumn($string)
  {
    if(strpos($string, 'type="checkbox"'))
    {
      if(strpos($string, 'checked="checked"'))
      {
        $string = 'ok';
      }
      else
      {
        $string = '';
      }
    }
    
    // We want latin here, not UTF-8
    $string = html_entity_decode($string, ENT_QUOTES);
    $string = strip_tags($string);
    
    //delete &nbsp; character
    $string = str_replace("\xA0", '', $string);
    $string = trim($string);
    
    if ($string && !is_numeric($string))
    {
      $string = str_replace('"', '""', $string);
      $string = '"' . $string . '"';
    }
   
    return $string;
  }
  
  
  /**
   * Shortcut method. Provides the  current culture mainly for addWhere statements of i18n tables
   * 
   * @return string
   */
  public static function getSfCulture()
  {
    
    return substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2);    
  }  

  /**
   * Check if a module is enabled
   * 
   * @param string $name
   * @return boolean
   */
  public static function isModuleEnabled($name)
  {
    if (in_array($name, sfConfig::get('sf_enabled_modules')))
    {
      return true;
    }
  }
  
  
  /**
   * Returns a list of hour:minute strings; the starting
   * hour, ending hour and interval size parameters can
   * be specified.
   * 
   * e.g. fromHour = 4, endHour = 6 and interval = 30
   * would return this array: [4:00, 4:30, 5:00, 5:30, 6:00]
   * 
   * If endHour is 24, the last element of the array
   * gets fixed to '0:00' instead of '24:00'
   * 
   * @param int $fromHour the starting hour
   * @param int $endHour the ending hour
   * @param int $interval the interval in minutes, must evenly divide 60
   * @return array a list of time stamps
   */
  public static function getTimeIntervalList($fromHour, $endHour, $interval)
  {
    $divider = 60 / $interval;
    if (!is_int($divider))
    {
      throw new InvalidArgumentException("Interval '" . $interval . "' does not evenly divide 60");
    }
    
    if ($fromHour < 0)
    {
      throw new InvalidArgumentException('fromHour must not be negative');
    }
    
    if ($endHour <= $fromHour || $endHour > 24)
    {
      throw new InvalidArgumentException('endHour is invalid (<= fromHour or > 24)');
    }
    
    $intervals = array();
    
    for ($i = $fromHour * $divider; $i <= $endHour * $divider; $i++)
    {
      $hours = (int)($i / $divider);
      $minutes = $i % $divider * $interval;
      $intervals[] = (($hours) ? $hours : '00') . ':' . (($minutes) ? $minutes : '00');
    }
    
    $intervals[] = (($lastElement = array_pop($intervals)) == '24:00') ? '0:00' : $lastElement;
    
    return $intervals;
  }
  
  
  /**
   * Counterpart to symfony's esc_entities() from the escaping helper
   * 
   * @param string $value
   * @return string
   */
  public static function esc_decode($value)
  {
    return html_entity_decode($value, ENT_QUOTES, sfConfig::get('sf_charset'));
  }
  
  /**
   * Returns the server name this code is executing on.
   * If $_SERVER['SERVER_NAME'] is not available, 'server_name'
   * from app.yml is read. If it is not set, an exception is thrown
   * 
   * @return string the current server name
   */
  public static function getServerName()
  {
    //if $_SERVER['SERVER_NAME'] is not available, try to read app.yml
    $serverName = isset($_SERVER['SERVER_NAME']) ?
      $_SERVER['SERVER_NAME'] : sfConfig::get('app_server_name');
    
    if ($serverName === null)
    {
      throw new UnexpectedValueException(
      	'Could not determine server name - please set \'server_name\' in app.yml');
    }
    
    return $serverName;
  }
  
  /**
   * Patches the routing system so that it generates valid absolute urls.
   * This results in fixed url helpers, which are needed e.g. when
   * using url_for in mails sent from cli tasks.
   * 
   * Before: http:///symfony/ullFlow/edit/doc/1
   * After:  http://www.example.com/ullFlow/edit/doc/1
   * 
   * See also ullCoreTools::getServerName().
   * 
   * @param sfContext $context a valid initialized sfContext instance or null
   */
  public static function fixRoutingForCliAbsoluteUrls(sfContext $context = null)
  {
    //if no context was given, retrieve the default one
    if ($context === null)
    {
      $context = sfContext::getInstance();
    }
    
    // Only do this in the cli environment
    if (!isset($_SERVER['SERVER_NAME']))
    {
      $routing = $context->getRouting(); 
      $routingOptions = $routing->getOptions();
      $routingOptions['logging'] = false;
      $routingOptions['context']['prefix'] = null;
      $routingOptions['context']['host'] = ullCoreTools::getServerName();
      $routing->initialize(new sfEventDispatcher(), $routing->getCache(), $routingOptions);
    }
  }
  
  /**
   * Returns a regular expression which covers most
   * 'western' first and last names.
   */
  public static function getRegexForNames()
  {
    //the 'u' modifier at the end is very important!
    //unicode characters from 00c0 to 01ff are allowed,
    //they cover most european/american names
    //in addition, ' - . , are allowed
    
    return '/^([ \x{00c0}-\x{01ff}a-zA-Z\'\-\.\,])+$/u';
  }
  
  /**
   * Copied from sfMail (it's private there)
   * 
   * Parses 'Name <email>' strings into parts
   * @param string $address a 'Name <email>' string
   */
  public static function splitMailAddressWithName($address)
  {
    if (preg_match('/^(.+)\s<(.+?)>$/', $address, $matches))
    {
      return array($matches[2], $matches[1]);
    }
    else
    {
      return array($address, '');
    }
  }
  
  public static function printQuery($query) {
    // formats a query
    $out = "$query";
    $out = str_ireplace("select ", "<br /> &nbsp; &nbsp; &nbsp; <span style='color:red; font-weight:bold;'>select</span> ", $out);
    $out = str_ireplace(" from ", "<br /> &nbsp; &nbsp; &nbsp; <span style='color:blue; font-weight:bold;'>from</span> ", $out);
    $out = str_ireplace(" where ", "<br /> &nbsp; &nbsp; &nbsp; <span style='color:green; font-weight:bold;'>where</span> ", $out);
    $out = str_ireplace(" order ", "<br /> &nbsp; &nbsp; &nbsp; <span style='color:purple; font-weight:bold;'>order</span> ", $out);
    $out = str_ireplace(" group by ", "<br /> &nbsp; &nbsp; &nbsp; <span style='color:orange; font-weight:bold;'>group by</span> ", $out);
    $out = str_ireplace(",", ",<br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ", $out);
    //$out = str_ireplace(" and ", "<br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; and ", $out);
    //$out = str_ireplace(" or ", "<br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; or ", $out);
    $out = str_ireplace(" and ", " <span style='color:orange; font-weight:bold;'>and</span> ", $out);
    $out = str_ireplace(" or ", " <span style='color:orange; font-weight:bold;'>or</span> ", $out);
    $out = str_ireplace(") ", ")<br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ", $out);
    $out = str_ireplace(" left join ", "<br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <span style='color:navy; font-weight:bold;'>left join</span> ", $out);
    $out = str_ireplace(" right join ", "<br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <span style='color:navy; font-weight:bold;'>right join</span> ", $out);
    $out = str_ireplace(" join ", "<br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <span style='color:navy; font-weight:bold;'>join</span> ", $out);
    $out = str_ireplace(" on ", " <span style='color:navy; font-weight:bold;'>on</span> ", $out);
    $out = str_ireplace("<br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ","<br /> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ",$out);
    echo "$out<br /><br />";
  }
  
  
  /**
   * URL-compatible Base64 encoding, replaces + and / and removes =
   * Found here/thanks to: http://php.net/manual/en/function.base64-encode.php
   * @param mixed $string
   */
  public static function base64_encode_urlsafe($string) {
    $data = base64_encode($string);
    $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
    
    return $data;
  }

  /**
   * URL-compatible Base64 decoding, fixes + / and padding.
   * Found here/thanks to: http://php.net/manual/en/function.base64-encode.php
   * @param mixed $string
   */
  public static function base64_decode_urlsafe($string) {
    $data = str_replace(array('-', '_'), array('+', '/'), $string);
    $mod4 = strlen($data) % 4;
    if ($mod4)
    {
      $data .= substr('====', $mod4);
    }
    
    return base64_decode($data);
  }
  
  /** 
   * Encodes dot char in URLs
   * Used because '.' breaks the symfony rewrite rules
   * Example symfony url that does not work: 'myModule/myAction/search/file.txt'
   * 
   * @see ullCoreTools::urlDotDecode
   * 
   * @param string string   
   * @return string fixed string
   */
  public static function urlDotEncode($string) 
  {
    // replace '.' by the corresponding html entity
    return rawurlencode(str_replace('.', '&#x2E;', $string)); 
  }
  
  /** 
   * Decodes dot char in URLs
   * 
   * @see ullCoreTools::urlDotEncode
   * 
   * @param string string  
   * @return string fixed string
   */
  public static function urlDotDecode($string) 
  {
    return rawurldecode(str_replace('&#x2E;', '.', $string));
  }
  
  /** 
   * Decodes dot char in URLs, but does not return the
   * fixed value, instead it directly modifies the given
   * string
   * 
   * @see ullCoreTools::urlDotDecode
   * 
   * @param string string by reference  
   * @return nothing
   */
  public static function urlDotDecodeByReference(&$string) 
  {
    $string = self::urlDotDecode($string);
  }
  
  public static function convertCsvToDoctrineArray(
    $csv, 
    $mapping, 
    $hasLabels = true,
    $csvDelimiter = ',',
    $csvEnclosure = '"',
    $csvEscape = '\\'
  )
  {
    
    // remove carriage returns
    $csv = str_replace("\r", '', $csv);

    //handle multiline data
    $csv = str_replace($csvEnclosure . "\n", $csvEnclosure . '@@@RealLineEnd@@@', $csv);
    $csvLines = explode('@@@RealLineEnd@@@', $csv);

    $doctrineArray = array();
    
    if ($hasLabels)
    {
      $labelLine = array_shift($csvLines);
      
      $labels = str_getcsv($labelLine, $csvDelimiter, $csvEnclosure, $csvEscape);
    }
    
    foreach ($csvLines as $line)
    {
      // skip empty lines
      if (!$line)
      {
        continue;
      }
      
      $lineArray = array();
      
      $columns = str_getcsv($line, $csvDelimiter, $csvEnclosure, $csvEscape);
      
      foreach ($columns as $key => $column)
      {
        //skip empty values
        if (!$column)
        {
          continue;
        }
        
        // Skip columns with no label
        if (!isset($labels[$key]))
        {
          continue;
        }
        
        // Skip columns with no mapping
        if (!isset($mapping[$labels[$key]]))
        {
          continue;
        }
        
        $label = $mapping[$labels[$key]];
        
        // Handle tags
        if ($label == 'duplicate_tags_for_search')
        {
          $lineArray[$label] = (($lineArray[$label]) ? ($lineArray[$label] . ', ') : '') . $column;
          continue;
        }
        
        $lineArray[$label] = $column;
      }
      
      // create mandatory username if none set
      if (!isset($lineArray['username']))
      {
        $username = '';
        
        
        if (isset($lineArray['email']))
        {
          $username = $lineArray['email'];
        }
        else
        {
          if (isset($lineArray['last_name']))
          {
            $username = ullCoreTools::sluggify($lineArray['last_name']);
          }
          
          $username .= rand(1000, 9999);
        }
        $lineArray['username'] = $username;
      }
      
      $doctrineArray[] = $lineArray;
      
    }

    return $doctrineArray;
  }
  
  
  /**
   * Calculate thumbnail part in the same directory
   * as a given image
   * 
   * @param $file
   * 
   * @return string
   */
  public static function calculateThumbnailPath($file)
  {
    $parts = pathinfo($file);

    $imageTypes = array(
      'jpg',
      'png',
      'gif',
    );
    
    if (!in_array($parts['extension'], $imageTypes))
    {
      $parts['extension'] = 'png';
    }
    
    $thumbnailPath =  $parts['dirname'] . DIRECTORY_SEPARATOR .
      $parts['filename'] . '_thumbnail' . '.' .
      $parts['extension']
    ;
    
    return $thumbnailPath;
  }
  
  /**
   * Converts an absolute path to a web path
   * E.g. /var/www/my_ullright/web/uploads/xyz.png => /uploads/xyz.png
   * 
   * @param string $path
   */
  public static function absoluteToWebPath($path)
  {
    $path = str_replace(sfConfig::get('sf_web_dir'), '', $path);
    
    return $path;
  }
  
  /**
   * Converts a web path to an absolute path
   * E.g. /uploads/xyz.png => /var/www/my_ullright/web/uploads/xyz.png
   * 
   * @param string $path
   */  
  public static function webToAbsolutePath($path)
  {
    $path = sfConfig::get('sf_web_dir') . $path;
    
    return $path;
  }

  /**
   * Escape single quotes
   * 
   * This method is used for functional tests to get the expected output string
   * e.g. for widgets
   */
  public static function escapeSingleQuotes($string)
  {
    return str_replace("'", "\'", $string);
  }

  /**
   * Get the max. upload file size
   * 
   * @autor Kavoir
   */
  public static function getMaxPhpUploadSize()
  {
    $max_upload = (int)(ini_get('upload_max_filesize'));
    $max_post = (int)(ini_get('post_max_size'));
    $memory_limit = (int)(ini_get('memory_limit'));
    $upload_mb = min($max_upload, $max_post, $memory_limit);

    return $upload_mb;
  }
  
  /**
   * Create a random hex string
   * 
   * @param integer $length
   */
  public static function randomString($length = 16)
  {
    $bytes = openssl_random_pseudo_bytes($length);
    $string = substr(ullCoreTools::base64_encode_urlsafe($bytes), 0, $length);
      
    return $string;
  }
}