<?php

class EnableTaggableCompleteModul extends Doctrine_Migration_Base
{
  public function up()
  {
    // open the settings.yml file in read mode and read it
    $filename = sfConfig::get('sf_app_config_dir') . '/settings.yml';
    $handle = fopen($filename, 'r') or die('can\'t open file' . $filename);
    $settingsYml = fread($handle, filesize($filename));
    
    // search the line with enabled_modules and save the string in $matches[0],
    // e.g. "   enabled_modules    ["
    preg_match("/\n\s*enabled_modules:\s*\[/i", $settingsYml, $matches);
    // add taggableCoplete module to enabled modules
    $settingsYml = str_replace($matches[0], $matches[0] . 'taggableComplete, ', $settingsYml);
    
    // close and open the file in write mode
    fclose($handle);
    $handle = fopen($filename, 'w') or die('can\'t open file' . $filename);
    
    // write the new content
    fwrite($handle, $settingsYml);
    fclose($handle);
  }

  public function down()
  {
    // open the settings.yml file in read mode and read it
    $filename = sfConfig::get('sf_app_config_dir') . '/settings.yml';
    $handle = fopen($filename, 'r') or die('can\'t open file' . $filename);
    $settingsYml = fread($handle, filesize($filename));
    
    // remove taggableCoplete module to enabled modules
    $settingsYml = str_replace('taggableComplete, ', '', $settingsYml);
    
    // close and open the file in write mode
    fclose($handle);
    $handle = fopen($filename, 'w') or die('can\'t open file' . $filename);
    
    // write the new content
    fwrite($handle, $settingsYml);
    fclose($handle);
  }
}
