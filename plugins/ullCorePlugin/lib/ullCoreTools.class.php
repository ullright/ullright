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
  
//  public static function ucwordsUmlaute($string) {
//    
//    /*
//    $string = strtolower($string);
//    $string = ucwords($string);
//    */
//
//    $string = preg_replace('/([^a-z]|^)([a-z])/e', '"$1".mb_strtoupper("$2")',
//                       mb_strtolower($string));
//    
//    return $string;
//   
//  }
//  
////  public static $ASCII_SPC_MIN = 'àáâãäåæçèéêëìíîïðñòóôõöùúûüýÿžš';
////  public static $ASCII_SPC_MAX = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖÙÚÛÜÝŸŽŠ';
//  public static $ASCII_SPC_MIN = 'äöü';
//  public static $ASCII_SPC_MAX = 'ÄÖÜ';
//  
//  public static function TwoStringsToArray($string1, $string2) {
//    
//    $array1 = str_split($string1);
//    $array2 = str_split($string2);
//    
//    foreach ($array1 as $key => $value) {
//      $array[$value] = $array2[$key]; 
//    }
//    
//    echo '<pre>',print_r($array),'</pre>';
//    
//  }
//  
//  public static function str2upper($text) {
//    
//    $text = strtoupper($text);
//    $text = str_replace('ä','Ä',$text);
//    $text = str_replace('ö','Ö',$text);
//    $text = str_replace('ü','Ü',$text);
//    return $text;
//    
//  }
//  
//  public static function str2lower($text) {
//    
//    $text = strtolower($text);
//    $text = str_replace('Ä','ä',$text);
//    $text = str_replace('Ö','ö',$text);
//    $text = str_replace('Ü','ü',$text);
//    return $text;
//    
//  }
//  
//  public static function ucwordsSmart($text) {
//    
//    return preg_replace(
//      '/([^a-z'.self::$ASCII_SPC_MIN.']|^)([a-z'.self::$ASCII_SPC_MIN.'])/e',
//      '"$1".self::str2upper("$2")',
//      self::str2lower($text)
//    );
//  }  
//
////  public static function ucwordsSmart($text) {
////    $text = strtolower($text);
////    $text = ucwords($text);
////    $text = str_replace('ä','Ä',$text);
////    $text = str_replace('ö','Ö',$text);
////    $text = str_replace('ü','Ü',$text);
////    
////    return $text;
////  }  
  
  public static function stripText($text)
  {
    $text = strtolower($text);
 
    // strip all non word chars
    $text = preg_replace('/\W/', ' ', $text);
 
    // replace all white space sections with a dash
    $text = preg_replace('/\ +/', '-', $text);
 
    // trim dashes
    $text = preg_replace('/\-$/', '', $text);
    $text = preg_replace('/^\-/', '', $text);
 
    return $text;
  }
  
  
/**
 * replacement for array_merge() that preserves numerical keys
 * keys from the second array, overwrite keys from the first one 
 * @param array1 array
 * @param array2 array
 * @return array        the merged array
 */ 
  
  public static function ull_array_merge($array1, $array2) {
    
    foreach ($array2 as $key => $value) {
      $array1[$key] = $value;
    }
    
    return $array1;
    
  }
  

/**
 * Wrapper for print_r() for easier usage"
 * @param array         array to print out
 * @return none         prints directly
 */ 
  
  public static function printR($array) {
    echo '<pre style="text-align: left; font-size: 1.2em;">';
    print_r($array);
//    var_dump($array);
    echo '</pre>';
  }
  
  
  
  /*
   * i18n Fallback for i18n tables
   * using the default table layout for ullConn applications
   * @param object              propel object
   * @param field_name string   name of the column
   */
  
  public static function getI18nField($object, $field_name) {
    
//    weflowTools::printR($object);
    
    $class_name           = get_class($object);
    $field_name_camelized = sfInflector::camelize($field_name);
    
    // check if current culture is the base default culture (usually english)
    //  and if so get the _default language values 
    if (
      sfConfig::get('base_default_language', 'en') == 
        substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2)
      ) {
        
        $method_name = 'get' . $field_name_camelized . 'I18nDefault';
        $value = $object->$method_name();
        return $value;
        
    } else {
      
        $object->setCulture(substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2));
      
        $method_name = 'get' . $field_name_camelized . 'I18n';
        $value = $object->$method_name();
        
//        echo "######### $field_name - value: $value";
        
        // fallback to default base language
        if (!$value) {
          $method_name = 'get' . $field_name_camelized . 'I18nDefault';
          $value = $object->$method_name();
        }
        
        return $value;
      
    }
       
  }  


  public static function makelinks($text) {
    // this function converts URLs in the form http://... or https:// into links
    // What actually does the trick happens in the replacement argument.
    // The logic is: If the complete match equals one of the unwanted matches,
    // replace it with itself (i.e. do nothing), else add the <a href=...> </a> tags around it.
   /* 
    for preg debugging:
    $text = preg_replace(
        // ok for a: "#(<a[^>]+>[^<]+</a>)#ie",
        // ok for url in any tag: "#(<[^>]+http[s]?://[^>]+>)#ie",
        "#(<[^>]+http[s]?://[^>]+>)#ie",
        '"xxx<input type=text value=\'$0\' />xxx"',
        $text);
    */

    $text = preg_replace(
        "#(<a[^>]+>[^<]+</a>)|(<[^>]+http[s]?://[^>]+>)|http[s]?://[^<> ]+#ie",
        '"$0"=="$1" || "$0"=="$2" ? "$0" : "<a href=\"$0\" class=\"link_new_window\" target=\"_blank\" title=\"'.__('Link opens in a new window', null, 'common').'\">$0</a>"',
        $text);

    return $text;
  }  
  
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

}

?>