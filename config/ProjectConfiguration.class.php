<?php

$sf_symfony_lib_dir = realpath(dirname(__FILE__) . '/../lib/vendor/symfony/lib');

require_once $sf_symfony_lib_dir .'/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enableAllPluginsExcept('sfPropelPlugin');
  }
  
  public function configureDoctrine(Doctrine_Manager $manager)
  {
    $cacheDriver = new Doctrine_Cache_Array();
    $manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE, $cacheDriver);
    $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE_LIFESPAN, 60 * 5);
  }
}
