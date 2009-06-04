<?php

/**
 * This abstract base class is inherited from by all ullSearch
 * configurations.
 * It forces implementation classes to provide enumerations
 * of default and all search fields.
 */
abstract class ullSearchConfig {
  protected $blacklist = array();
  
  public abstract function getDefaultSearchColumns();
  public abstract function getAllSearchableColumns();
  
  /**
   * Returns a blacklist of columns we do not want the user to search for
   * @return array the blacklist
   */
  public function getBlacklist()
  {
    return $this->blacklist;
  }
}
