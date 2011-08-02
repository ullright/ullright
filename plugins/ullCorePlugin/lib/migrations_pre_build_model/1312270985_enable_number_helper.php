<?php

class EnableNumberHelper extends Doctrine_Migration_Base
{
  public function up()
  {
    // open the settings.yml file in read mode and read it
    $filename = sfConfig::get('sf_app_config_dir') . '/settings.yml';
    $settingsYml = file_get_contents($filename);
    
    // search the line with  and save the string in $matches[0],
    // e.g. "   enabled_modules    ["
    preg_match("/\n\s*standard_helpers:\s*\[/i", $settingsYml, $matches);
    // add the entry
    $settingsYml = str_replace($matches[0], $matches[0] . 'Number, ', $settingsYml);
    
    file_put_contents($filename, $settingsYml);
  }

  public function down()
  {
  }
}
