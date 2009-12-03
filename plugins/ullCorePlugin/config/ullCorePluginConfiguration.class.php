<?php 

/**
 * ullCorePlugin configuration.
 * 
 * @package     ullCore
 * @subpackage  config
 * @author      Your name here
 * @version     SVN: $Id: PluginConfiguration.class.php 12628 2008-11-04 14:43:36Z Kris.Wallsmith $
 * @version     $Rev$
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
    
    // KU: 2009-11-19: disabled apc cache because of typical caching problems.
    //   let's see if we really need it.
//    if (extension_loaded('apc'))
//    {
//      $cacheDriver = new Doctrine_Cache_Apc();    
//    }
//    else
//    {
      $cacheDriver = new Doctrine_Cache_Array();
//    }
    
    $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE, $cacheDriver);
    $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE_LIFESPAN, 60 * 5);
    
    // disabled because ist has sideeffects which have to be investigated (i18n, ...)
    //$manager->setAttribute(Doctrine::ATTR_QUERY_CACHE, $cacheDriver);
    
    
    // disabled because ist has sideeffects which have to be investigated
    // $manager->setAttribute('use_dql_callbacks', true);
  }

}
