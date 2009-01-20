<?php

/**
 * ullCorePlugin configuration.
 * 
 * @package     ullCore
 * @subpackage  config
 * @author      Your name here
 * @version     SVN: $Id: PluginConfiguration.class.php 12628 2008-11-04 14:43:36Z Kris.Wallsmith $
 */
class ullCorePluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    //enable Doctrine cache
    $manager = Doctrine_Manager::getInstance();
    $cacheDriver = new Doctrine_Cache_Apc();
//    $manager->setAttribute(Doctrine::ATTR_QUERY_CACHE, $cacheDriver);
    $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE, $cacheDriver);
    
//    $manager->setAttribute('use_dql_callbacks', true);
  }

}
