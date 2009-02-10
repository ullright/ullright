<?php

class FakeMigrationVersionTask extends sfBaseTask
{

	protected function configure()
	{
		$this->namespace        = 'ullright';
		$this->name             = 'fake-migration-version';
		$this->briefDescription = 'Sets the current migration version';
		$this->detailedDescription = <<<EOF
		The [{$this->name} task|INFO] sets the current migration version.
		Only use this for debugging!

		Call it with:

		[php symfony {$this->namespace}:{$this->name}|INFO]
EOF;

		$this->addArgument('version', sfCommandArgument::REQUIRED,
      'The version');
		$this->addArgument('application', sfCommandArgument::OPTIONAL,
  	  'The application name', 'frontend');
		$this->addArgument('env', sfCommandArgument::OPTIONAL,
  	  'The environment', 'cli');
	}


	protected function execute($arguments = array(), $options = array())
	{
		$configuration = ProjectConfiguration::getApplicationConfiguration(
		$arguments['application'], $arguments['env'], true);

		$databaseManager = new sfDatabaseManager($configuration);
		
		$dm = new Doctrine_Migration();
		$conn = Doctrine_Manager::connection();
		
		try {
		  $dm->getCurrentVersion();
		}
		catch (Exception $e) {
		  $this->logSection('Migration table not existing, creating...');
		  $conn->export->createTable($dm->getTableName() , array('version' => array('type' => 'integer', 'size' => 11)));
		  $conn->exec("INSERT INTO " . $dm->getTableName() . " VALUES(0)");
		}
		
		$this->logSection($this->name, 'Current migration version is: ' . $dm->getCurrentVersion() .
		          ', setting to: ' . $arguments['version']);
		
		$conn->exec("UPDATE " . $dm->getTableName() . " SET version = ?", array($arguments['version']));
	}
}