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
    $this->loadLocalAppYml();
    
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
    
    $this->detectMobileDevice();
  }

  
  /**
   * Loads a local apps/frontend/config/app.local.yml file if it exists.
   * Values set there override the ones in config/app.yml.
   * 
   * @see apps/frontend/config/app.local.yml.dist for further details
   */
  protected function loadLocalAppYml()
  {
    $config = sfApplicationConfiguration::getActive();
    
    //initially, $config is actually a project config therefore we check the class type
    if ($config instanceof sfApplicationConfiguration)
    {
      $localConfigPath = $config->getConfigCache()->checkConfig('config/app.local.yml', true);
      
      if ($localConfigPath !== null)
      {
        require($localConfigPath);
      }
    }
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
        
  /**
   * Connect to request.filter_parameters event and set request format in case
   * of surfing with a mobile device (smartphone)
   */
  protected function detectMobileDevice()
  {
    if (sfConfig::get('app_enable_mobile_version', true))
    {
      $this->dispatcher->connect('request.filter_parameters', array($this, 'filterRequestParameters'));
    }
  }
  
  
  /**
   * Set request format in case
   * of surfing with a mobile device (smartphone)
   * 
   * @param sfEvent $event
   * @param unknown_type $parameters
   */
  public function filterRequestParameters(sfEvent $event, $parameters)
  {
    $request = $event->getSubject();
    
    /*
     * Serve mobile version for user agents containing:
     * 
     * Mobile: Android, Apple iPhone
     * Jasmine: Samsung
     * Symbian: Nokia
     * NetFront: Sony Ericsson
     * BlackBerry: BlackBerry
     * Opera Mini: Opera for mobile devices
     * 
     * 
     * Exclude from matching:
     * 
     * iPad: Apple iPad
     * 
     */
    if (preg_match(
      '#^(?!.*iPad).*(Mobile|Jasmine|Symbian|NetFront|BlackBerry|Opera Mini).*$#i',
      $request->getHttpHeader('User-Agent')))
    {
      $request->setRequestFormat('mobile');
    }
 
    return $parameters;
  }
  
}
