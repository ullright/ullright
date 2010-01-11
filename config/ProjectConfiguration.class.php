<?php

$sf_symfony_lib_dir = realpath(dirname(__FILE__) . '/../plugins/ullCorePlugin/lib/vendor/symfony/lib');

require_once $sf_symfony_lib_dir .'/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    // introduced by upgrade1.3 task. Necessary? 
//    sfYaml::setSpecVersion('1.1');

//    // set doctrine 1.1 lib path
//    $doctrinePath = realpath(dirname(__FILE__) . '/../plugins/ullCorePlugin/lib/vendor/doctrine/lib') . '/Doctrine.php';
//    sfConfig::set('sfDoctrinePlugin_doctrine_lib_path', $doctrinePath);
    
    $this->enableAllPluginsExcept('sfPropelPlugin');
  }
}
