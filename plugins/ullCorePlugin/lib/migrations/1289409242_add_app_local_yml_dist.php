<?php

class AddAppLocalYmlDist extends Doctrine_Migration_Base
{
  public function up()
  {
    $repoUrl = 'http://bigfish.ull.at/svn/ullright/trunk/apps/frontend/config/app.local.yml.dist';
    $appConfigDir = sfConfig::get('sf_app_config_dir');
    $localPath = $appConfigDir . '/app.local.yml.dist';
    
    $fs = new sfFilesystem();
  
    $command = "svn export $repoUrl $localPath"; 
    try
    {
      $output = $fs->execute($command);
    }
    catch (RuntimeException $e)
    {
      //command not found? just return so that the migration won't throw the error
      return;
    }
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException();
  }
}
