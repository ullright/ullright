<?php

class UpdateFormExtraExternal extends Doctrine_Migration_Base
{
  public function up()
  {
    $pluginPath = sfConfig::get('sf_plugins_dir');
    
    $fs = new sfFilesystem();
    
    $command = "svn propget svn:externals $pluginPath";
    $output = $fs->execute($command);
    $lines = preg_split('/[' . PHP_EOL . ']+/', $output[0]); //0 is stdout

    foreach ($lines as &$line)
    {
      if (strpos($line, 'sfFormExtraPlugin') === 0)
      {
        $line = 'sfFormExtraPlugin http://svn.symfony-project.com/plugins/sfFormExtraPlugin/branches/1.3';
      }
    }

    $newProperties = implode($lines, PHP_EOL);
  
    $command = "svn propset svn:externals \"$newProperties\" $pluginPath";
    $output = $fs->execute($command);
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException();
  }
}
