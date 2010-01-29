<?php 

/**
 * ullCorePlugin configuration.
 * 
 * @package     ullCore
 * @subpackage  config
 * @author      Your name here
 * @version     SVN: $Id$
 * @version     $Rev$ 
 */
class ullCorePluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    $manager = Doctrine_Manager::getInstance();
    
    //enable Doctrine cache
    
    // KU: 2009-11-19: disabled apc cache because of typical caching problems.
    //   let's see if we really need it.
//    if (extension_loaded('apc'))
//    {
//      $cacheDriver = new Doctrine_Cache_Apc();    
//    }
//    else
//    {
      // Array cache driver only caches during a single request
      $cacheDriver = new Doctrine_Cache_Array();
//    }

//    $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE_LIFESPAN, 60 * 5);

    $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE, $cacheDriver); 
     
    
    // disabled because ist has sideeffects which have to be investigated (i18n, ...)
    //$manager->setAttribute(Doctrine::ATTR_QUERY_CACHE, $cacheDriver);
    
    // disabled because ist has sideeffects which have to be investigated
    // $manager->setAttribute('use_dql_callbacks', true);
  }

}
