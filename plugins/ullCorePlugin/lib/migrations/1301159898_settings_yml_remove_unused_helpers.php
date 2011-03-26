<?php

class SettingsYmlRemoveUnusedHelpers extends Doctrine_Migration_Base
{
  public function up()
  {
    // open the settings.yml file in read mode and read it
    $filename = sfConfig::get('sf_app_config_dir') . '/settings.yml';
    
    $content = file_get_contents($filename);
    
    $content = str_replace(', Object, ', ', ', $content);
    $content = str_replace(', Validation]', ']', $content);
    $content = str_replace(', Javascript, ', ', JavascriptBase, ', $content);
    
    file_put_contents($filename, $content);
    
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
