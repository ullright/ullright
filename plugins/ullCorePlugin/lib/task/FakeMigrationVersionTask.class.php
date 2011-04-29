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

    $this->addArgument('version', sfCommandArgument::OPTIONAL,
      'The doctrine migration version in the database');
    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
    $this->addOption('custom', null, sfCommandOption::PARAMETER_NONE,
      'Set the custom migration version instead of the regular one');
  }


  protected function execute($arguments = array(), $options = array())
  {
    $configuration = ProjectConfiguration::getApplicationConfiguration(
    $arguments['application'], $arguments['env'], true);

    $databaseManager = new sfDatabaseManager($configuration);
    
    if ($options['custom'])
    {
      $dm = new Custom_Doctrine_Migration();
    }
    else
    {
      $dm = new Doctrine_Migration();
    }
    
    $conn = Doctrine_Manager::connection();
    
    try {
      $dm->getCurrentVersion();
    }
    catch (Exception $e) {
      //this should not happen anymore
      $this->logSection('Migration table not existing, creating...');
      $conn->export->createTable($dm->getTableName() , array('version' => array('type' => 'integer', 'size' => 11)));
      $conn->exec("INSERT INTO " . $dm->getTableName() . " VALUES(0)");
    }
    
    $this->log('Current migration version is: ' . $dm->getCurrentVersion());
    
    if ($arguments['version'] !== null)
    {
      $this->log('Setting to: ' . $arguments['version']);
      $conn->exec("UPDATE " . $dm->getTableName() . " SET version = ?", array($arguments['version']));
    }
    else
    {
      $this->log('Call it with argument "version" to manually set a version:');
      $this->log(sprintf($this->getSynopsis(), 'php symfony'));
    }
  }
}