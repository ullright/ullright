<?php

class UpgradeSf10ToSf11Task extends sfBaseTask
{
	protected function configure()
	{
		$this->namespace        = 'ullright';
		$this->name             = 'upgrade-sf10-to-sf11';
		$this->briefDescription = 'Upgrades ullright from symfony 1.0 to symfony 1.1';
		$this->detailedDescription = <<<EOF
The [{$this->name} task|INFO] upgrades ullright from symfony 1.0 to 
symfony 1.1.

Call it with:

  [php symfony {$this->namespace}:{$this->name}|INFO]
EOF;
		// add arguments here, like the following:
		$this->addArgument('application', sfCommandArgument::OPTIONAL
		, 'The application name', 'myApp');
		$this->addArgument('env', sfCommandArgument::OPTIONAL
		, 'The environment', 'cli');
		// add options here, like the following:
		//$this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev');
	}


	protected function execute($arguments = array(), $options = array())
	{
		$this->logSection($this->name, 'Removing old config files...');
		$this->svn_delete('config/i18n.yml');
		$this->svn_delete('apps/myApp/config/i18n.yml');
		$this->svn_delete('apps/myApp/config/logging.yml');
		
		$this->logSection($this->name, '...');

//		sfLoader::loadHelpers('Partial');
//
//		$configuration = ProjectConfiguration::getApplicationConfiguration(
//		$arguments['application'], $arguments['env'], true);
//
//		$databaseManager = new sfDatabaseManager($configuration);
//		$context = sfContext::createInstance($configuration);
//
//		$fields             = sfConfig::get('app_myConfig');

	}
	
	protected function delete($path) {
	  
	  $path = realpath(dirname(__FILE__) . '/../../../../' . $path);
	  
	  if (file_exists($path))
	  {
	    unlink($path);
	    $this->log("File $path deleted.");
	  }
	  else
	  {
	    $this->log("File $path not found.");
	  }
	}
	
    protected function svn_delete($path) {
      
      $path = realpath(dirname(__FILE__) . '/../../../../' . $path);
      
      if (file_exists($path))
      {
        $this->log('Deleted: ' . shell_exec("svn delete $path"));
      }
      else
      {
        $this->log("File $path not found.");
      }
    }	

}