<?php

class TestTask extends sfBaseTask
{
  
  protected function configure()
  {
  	$this->namespace        = 'ullright';
    $this->name             = 'test';
  	$this->briefDescription = '';
  	$this->detailedDescription = <<<EOF
The [{$this->name} task|INFO] tests

Call it with:

  [php symfony {$this->namespace}:{$this->name}|INFO]
EOF;

  	$this->addArgument('application', sfCommandArgument::OPTIONAL,
  	  'The application name', 'frontend');
  	$this->addArgument('env', sfCommandArgument::OPTIONAL,
  	  'The environment', 'cli');
  }


  protected function execute($arguments = array(), $options = array())
  {
	$this->logSection($this->name, 'Initializing...');

    $configuration = ProjectConfiguration::getApplicationConfiguration(
    $arguments['application'], $arguments['env'], true);

    $databaseManager = new sfDatabaseManager($configuration);
	  
    $doc = Doctrine::getTable('UllFlowDoc')->findOneById(1);

    var_dump($doc->toArray());
  	  
  }
  

}