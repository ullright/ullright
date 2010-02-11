<?php

class DumpDatabaseTask extends ullBaseTask
{

  protected
    $dbName = '',
    $dbUsername = '',
    $dbPassword = ''
  ;

  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'dump-database';
    $this->briefDescription = 'Dumps the database (for MySQL)';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] dumps the database into data/sql/

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
    $this->logSection($this->name, 'Initializing');
    
    $this->initializeDatabaseConnection($arguments, $options);
    
    $this->logSection($this->name, 'Dumping database into data/sql/');
    
    $command = 'mysqldump -u ' . $this->dbUsername . 
      ' --password=\'' . $this->dbPassword . '\'' . 
      ' ' . $this->dbName . ' | bzip2 > ' .  
      sfConfig::get('sf_data_dir') . '/sql/' . $this->dbName . '.mysql.dump.bz2'
    ;
    
    $this->log($this->getFilesystem()->execute($command));
  }
 
  
  protected function initializeDatabaseConnection($arguments = array(), $options = array())
  {
    $configuration = ProjectConfiguration::getApplicationConfiguration(
    $arguments['application'], $arguments['env'], true);
    
    $databaseManager = new sfDatabaseManager($configuration);

    // BE CAREFUL: gets the connection name from databases.yml not the actual db name 
    $this->dbName = Doctrine_Manager::getInstance()->getCurrentConnection()->getName();
    
    $connectionOptions = Doctrine_Manager::getInstance()->getCurrentConnection()->getOptions();
    
    $this->dbUsername = $connectionOptions['username'];
    $this->dbPassword = $connectionOptions['password'];
    
//    $conn = Doctrine_Manager::connection();
  }
    
}

