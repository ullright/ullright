<?php

class EnableUllCmsContentBlockModul extends Doctrine_Migration_Base
{
  public function up()
  {
    // open the settings.yml file in read mode and read it
    $filename = sfConfig::get('sf_app_config_dir') . '/settings.yml';
    $settingsYml = file_get_contents($filename);
    
    // search the line with enabled_modules and save the string in $matches[0],
    // e.g. "   enabled_modules    ["
    preg_match("/\n\s*enabled_modules:\s*\[/i", $settingsYml, $matches);
    // add taggableCoplete module to enabled modules
    $settingsYml = str_replace($matches[0], $matches[0] . 'ullCmsContentBlock, ', $settingsYml);    
    
    file_put_contents($filename, $settingsYml);
  }

  public function down()
  {
  }
}
