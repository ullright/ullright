<?php

class r1554Task extends ullBaseTask
{
  
  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = str_replace('Task', '', get_class($this));
    $this->briefDescription = 'Automates upgrade tasks for a certain revision';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] automates upgrade tasks for a certain revision.

    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
    
    This tasks requires the usage of a customer subversion repository.
    
EOF;

    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
    
    $this->addOption('no-confirmation', null, sfCommandOption::PARAMETER_NONE, 
      'Skip confirmation question');
    
  }


  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection($this->name, 'Initializing');
    
    $this->printDeleteWarning($arguments, $options);
    
    $this->initializeDatabaseConnection($arguments, $options);
    
    $list = array(
      'batch/publish',
      'batch/ullright_dump_production',
      'batch/ullright_load_production',
      'config/ull_db_password.txt',
      'config/ull_project_name.txt',
      'config/ull_source_dir.txt', 
      'config/ull_ssh_user_host.txt', 
      'config/ull_target_dir.txt',
    );
    
    foreach ($list as $file)
    {
      $this->svnDelete($file);
    }
    
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
  
 
  
}

