<?php

class TestTask extends sfBaseTask
{
  
  
  protected function configure()
  {
	$this->namespace        = 'ullright';
    $this->name             = 'test-task';
	$this->briefDescription = 'test';
	$this->detailedDescription = <<<EOF
The [{$this->name} task|INFO] tests

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
	$this->logSection($this->name, 'Test...');

      $configuration = ProjectConfiguration::getApplicationConfiguration(
  	  $arguments['application'], $arguments['env'], true);
  
  	  $databaseManager = new sfDatabaseManager($configuration);
//  	  $context = sfContext::createInstance($configuration);
  	  
  	  $user = new User();
  	  $user->first_name = "Klemens";
  	  $user->save();
  }

}