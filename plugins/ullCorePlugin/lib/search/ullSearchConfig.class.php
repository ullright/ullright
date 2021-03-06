<?php

/**
 * This abstract base class is inherited from by all ullSearch
 * configurations.
 * It forces implementation classes to provide enumerations
 * of default and all search fields.
 */
abstract class ullSearchConfig 
{
  protected $blacklist = array();

  protected abstract function configureDefaultSearchColumns();
  
  public abstract function getAllSearchableColumns();
  
  /**
   * Returns an array of search form entries describing often
   * used columns when searching for a user.
   * 
   * NOTE: Every search form entry added here must also be added
   * to the getAllSearchableColumns below.
   */
  public function getDefaultSearchColumns()
  {
    $sfeArray = $this->configureDefaultSearchColumns();
    
    //set consecutive ids for the entries
    for($i = 0; $i < count($sfeArray); $i++)
    {
      $sfeArray[$i]->uuid = $i;
    }
    
    return $sfeArray;
  }
  
  /**
   * Returns a blacklist of columns we do not want the user to search for
   * @return array the blacklist
   */
  public function getBlacklist()
  {
    return $this->blacklist;
  }
  
  /**
   * Loads the search config class name for a given model
   * from app.yml and returns a new instance, constructed
   * with given parameters in an array, if there are any.
   *
   * @param $modelName a model name
   * @return ullSearchConfiguration the new search configuration
   */
  public static function loadSearchConfig($modelName)
  {
    $paramName = 'app_' . $modelName . '_search_class';
    if (sfConfig::has($paramName))
    {
      $className = sfConfig::get($paramName);
      
      if (func_num_args() > 1)
      {
        $params = func_get_args();
        array_shift($params);
        $searchConfig = new $className($params);
      }
      else
      {
        $searchConfig = new $className();
      }
    
      if (!($searchConfig instanceof ullSearchConfig))
      {
        throw new RuntimeException('The search configuration class defined for ' . $modelName . ' is invalid.');
      }
      
      return $searchConfig;
    }
    
    throw new RuntimeException('The search configuration class for ' . $modelName . ' is not defined.');
  }
}
