<?php

class UpgradeSf10ToSf11Task extends sfBaseTask
{
  
  protected
    $ullright_repo_url = "https://ssl.ull.at/svn/ullright/trunk"
  ;
  
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
	$this->svnDelete('config/i18n.yml');
	$this->svnDelete('apps/myApp/config/i18n.yml');
	$this->svnDelete('apps/myApp/config/logging.yml');
	
	$this->logSection($this->name, 'Getting new config files from ullright repository');
	$this->svnExport('config/ProjectConfiguration.class.php');
	
//		sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
//
//		$configuration = ProjectConfiguration::getApplicationConfiguration(
//		$arguments['application'], $arguments['env'], true);
//
//		$databaseManager = new sfDatabaseManager($configuration);
//		$context = sfContext::createInstance($configuration);
//
//		$fields             = sfConfig::get('app_myConfig');

	}
	
	
    /**
     * returns an absolute path for a given path relative to the symfony base_dir
     * 
     * example: "config/settings.yml" => "/var/www/mySymfonyProject/config/settings.yml" 
     *
     * @param  string $path path relative to symfony base dir
     * @return string
     */	
	protected function absolutePath($path)
	{
	  return realpath(dirname(__FILE__) . '/../../../../' . $path);
	}
	
    /**
     * deletes a file and prints the result
     * 
     * @param  string $path path relative to symfony base dir
     */ 	
	protected function delete($path) 
	{
	  $path = $this->absolutePath($path);
	  
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
	
    /**
     * deletes a file using svn and prints the result
     * 
     * @param  string $path path relative to symfony base dir
     */ 	
    protected function svnDelete($path) 
    {
      $path = $this->absolutePath($path);
      
      if (file_exists($path))
      {
        $this->log('svn delete: ' . shell_exec("svn delete $path"));
      }
      else
      {
        $this->log("File $path not found.");
      }
    }	

    /**
     * exports a file form the ullright repository and saves it to local working copy
     * 
     * @param  string $path path relative to symfony base dir
     */     
    protected function svnExport($path) 
    {
      $absolute_path = $this->absolutePath($path);

      $this->log('svn export: ' 
            . shell_exec("svn export {$this->ullright_repo_url}/{$path} {$absolute_path}"));
    }
    

}