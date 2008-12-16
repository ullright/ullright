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
    
  }

  // doesn't work yet with symfony 1.1
  public function configureDoctrine(Doctrine_Manager $manager)
  {
    $manager->setAttribute('use_dql_callbacks', true);
  }
  
}
