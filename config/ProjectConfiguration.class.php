<?php

// look for custom path.php (defining the symfony lib directory)
if (file_exists(dirname(__FILE__) . '/path.php'))
{
  require 'path.php';
} 
else
{
  $sf_symfony_lib_dir = '/usr/share/php/symfony';
}

require_once $sf_symfony_lib_dir .'/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    // set sf_symfony_data_dir var for doctrine plugin (task doctrine:generate-crud)
    if (file_exists(dirname(__FILE__) . '/path.php'))
    {
      require 'path.php';
    } 
    else
    {
      $sf_symfony_data_dir = '/usr/share/php/data/symfony';
    }    
    sfConfig::set('sf_symfony_data_dir', $sf_symfony_data_dir);
  }
}
