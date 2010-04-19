<?php

class GetAndLoadProductionDatabaseTask extends ullBaseTask
{
  
  protected
    $dbName = '',
    $targetServerName = '',
    $targetUserName = '',
    $targetDir = ''
  ;

  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'get-and-load-production-database';
    $this->briefDescription = 'Retrieves the current production database dump and loads it';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] retrieves the current production database dump and loads it

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
    
    $this->initializeConfig($arguments, $options);
    
    $this->executeRemoteSymfonyTask('ullright:dump-database');
    
    $this->downloadProductionDump($arguments, $options);
    
    $this->runTask('ullright:load-production-database');
  }
  
  
  protected function initializeDatabaseConnection($arguments = array(), $options = array())
  {
    $configuration = ProjectConfiguration::getApplicationConfiguration(
    $arguments['application'], $arguments['env'], true);
    
    $databaseManager = new sfDatabaseManager($configuration);

    // BE CAREFUL: gets the connection name from databases.yml not the actual db name 
    $this->dbName = Doctrine_Manager::getInstance()->getCurrentConnection()->getName();
    
//    $conn = Doctrine_Manager::connection();
  }
  
  
  protected function initializeConfig($arguments = array(), $options = array())
  {
    $this->targetServerName = $this->getRequiredSfConfigOption('app_publish_server_name');
    $this->targetUserName = $this->getRequiredSfConfigOption('app_publish_user_name');
    $this->targetDir = $this->getRequiredSfConfigOption('app_publish_target_dir');
  }
  
  
  /**
   * Execute a shell command on the remote server in the target directory
   * 
   * @param string $command
   * @return string
   */
  protected function executeRemoteCommand($cmd, $quiet = false)
  {
    $command = 'ssh ' .  $this->targetUserName . '@' . $this->targetServerName . 
      ' "' .
      'cd ' . $this->targetDir . '; ' . $cmd .
      '"'
    ;

    $output = $this->getFilesystem()->execute($command);
    
    if (!$quiet)
    {
      $this->log($output);
    }
  }
  
  
  /**
   * Execute a symfony task on the remote server in the target directory
   * 
   * @param string $task
   * @return string
   */
  protected function executeRemoteSymfonyTask($task, $quiet = false)
  {
    $this->executeRemoteCommand('php symfony ' . $task, $quiet);
  }  
  
  
  protected function downloadProductionDump($arguments = array(), $options = array())
  {
    $command = 'scp ' . $this->targetUserName . '@' . $this->targetServerName .
      ':' . $this->targetDir . '/data/sql/' . $this->dbName . '.mysql.dump.bz2' .
      ' ' . sfConfig::get('sf_data_dir') . '/sql/' . $this->dbName . '.production.mysql.dump.bz2'
    ;
    
    $this->log($this->getFilesystem()->execute($command));
  }
  
  
}

