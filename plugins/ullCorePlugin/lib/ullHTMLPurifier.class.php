<?php
/**
 * This class provides easy access to the HTML Purifier,
 * a tool which allows us to filter user input of any
 * malicious XSS
 * 
 * See also ProjectConfiguration for cache path configuration
 * and HTMLPurifier.standalone.php for implementation details.
 */
class ullHTMLPurifier
{
  /**
   * Initializes a default configuration with only the cache
   * path set.
   * 
   * @return HTMLPurifier_Config instance
   */
  protected static function getDefaultConfiguration()
  {
    //Initialize configuration; cache is prepared in class ProjectConfiguration
    $config = HTMLPurifier_Config::createDefault();
    $config->set('Cache.SerializerPath', sfConfig::get('htmlpurifier_cache_dir'));
    
    return $config;
  }
  
  /**
   * Provides a HTMLPurifier instance, singleton
   * 
   * @return HTMLPurifier instance
   */
  protected static function getPurifierInstance()  
  {
    //Use of instance() to prevent multiple purifier instances
    $purifier = HTMLPurifier::instance();
    
    //We could set the default configuration right here,
    //but since we specify different configuration options
    //in the various purify-methods it would not have any
    //effect, the HTMLPurifier does not merge the configurations.
    
    return $purifier;
  }
  
  /**
   * Purifiers a given string, disallowing any HTML tag.
   * Also returns well-formed code, i.e. stray angle
   * brackets are removed.
   * 
   * @param String $value
   * @return String purified $value
   */
  public static function purifyForSecurity($value)
  {
    $config = self::getDefaultConfiguration();
    $config->set('HTML.Allowed', '');
        
    return self::getPurifierInstance()->purify($value, $config);
  }
  
  /**
   * Purifies a given string, allowing the use of
   * the id attribute and frame targets.
   * Also returns well-formed code, i.e. stray angle
   * brackets are removed.
   * 
   * @param String $value
   * @return String purified $value
   */
  public static function purifyForWiki($value)
  {
    $config = self::getDefaultConfiguration();
    $config->set('Attr.EnableID', true);
    $config->set('Attr.AllowedFrameTargets', '_blank, _parent, _self, _top');
        
    return self::getPurifierInstance()->purify($value, $config);
  }
  
  
  /**
   * Remove all input tags
   * 
   * @param String $value
   * @return String purified $value
   */
  public static function removeInputTags($value)
  {
    $config = self::getDefaultConfiguration();
    $config->set('Attr.EnableID', true);
    $config->set('HTML.ForbiddenElements', 'input');
        
    return self::getPurifierInstance()->purify($value, $config);
  }  
}