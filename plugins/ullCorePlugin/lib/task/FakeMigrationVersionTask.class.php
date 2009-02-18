<?php

class FakeMigrationVersionTask extends sfBaseTask
{

	protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'fake-migration-version';
    $this->briefDescription = 'Manually set the doctrine migration version in the database';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] sets the current doctrine migration version in the database.

    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
    
    Faking the migration version is used to test manually written migrations.
    
    Use only for the ullright development environment!    
    
EOF;

    $this->addArgument('version', sfCommandArgument::REQUIRED,
      'The doctrine migration version in the database');
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