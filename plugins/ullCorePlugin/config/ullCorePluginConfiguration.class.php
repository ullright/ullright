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
    
    $this->createHTMLPurifierCache();
    
//    $this->detectMobileDevice();
  }

  /**
   * Create cache dir tree for HTML Purifier if not already available
   */
  protected function createHTMLPurifierCache()
  {
    $purifierCachePath = sfConfig::get('sf_cache_dir') . DIRECTORY_SEPARATOR . 'htmlpurifier';
    
    if (!file_exists($purifierCachePath))
    {
      mkdir($purifierCachePath, 0755);
      
      foreach(array('HTML', 'CSS', 'URI') as $type)
      {
        mkdir($purifierCachePath . DIRECTORY_SEPARATOR . $type, 0777, true);
        //set perms again because of interfering umask
        chmod($purifierCachePath . DIRECTORY_SEPARATOR . $type, 0777);
      }
    }
    
    sfConfig::add(array('htmlpurifier_cache_dir' => $purifierCachePath));
  }
  
  protected function detectMobileDevice()
  {
    $this->dispatcher->connect('request.filter_parameters', array($this, 'filterRequestParameters'));
  }
  
  public function filterRequestParameters(sfEvent $event, $parameters)
  {
    $request = $event->getSubject();
 
    if (preg_match('#Mobile/.+Safari#i', $request->getHttpHeader('User-Agent')))
    {
      $request->setRequestFormat('mobile');
    }
 
    return $parameters;
  }
  
}
