<?php

class RenameKeyFile extends Doctrine_Migration_Base
{
  public function up()
  {
    $keyFilePath = sfConfig::get('sf_app_config_dir') . '/security.key';

    if (file_exists($keyFilePath))
    {
      echo "security.key exists, moving to ullVault.key ...\n";
      rename($keyFilePath, sfConfig::get('sf_app_config_dir') . '/ullVault.key');
    }
    else
    {
      echo "security.key not found, doing nothing.\n";
    }
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
