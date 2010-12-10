<?php

class SvnUpdateStepsTask extends ullBaseTask
{
  protected 
    $numOfMigrations = 0
  ;  
  
  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'svn-update-steps';
    $this->briefDescription = 'Svn updates and runs migrations step by step';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] svn updates and runs migrations step by step
    to avoid a de-corelation between model files and database structure (migrations)
    
    Example:
    r1234: new Migration: Create an ullUser 
    r1235: new Migration: add an ullUserColumn "foobar"
    
    Now if we svn update both revisions at once, the first migration will fail:
    "Unknown column foobar". Why?
    
    Because first the model classes are rebuilt. Already with the "foobar" column
    Then the migrations are run. But the "foobar" column does not exist in the
    database yet at this moment! 

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
    
    $currentRevision = $this->getUllrightCurrentRevision();
    
    // Set initial number of migrations
    $this->detectNewMigration($currentRevision);
    
    $this->log('Current revision: ' . $currentRevision);
    
    $headRevision = $this->getUllrightHeadRevision();
    
    $this->log('Head revision: ' . $headRevision);
    
    
    
    for ($rev = $currentRevision + 1; $rev <= $headRevision; $rev++)
    {
      if ($this->detectNewMigration($rev))
      {
        $this->runTask('ullright:svn-pin-ullright', array('revision' => $rev));
        
        $command = 'svn update';
        $this->log(reset($this->getFilesystem()->execute($command)));
        
        $this->runTask('doctrine:build', array(), array('model' => true, 'forms' => true, 'filters' => true));
        $this->runTask('doctrine:migrate');
      }  
    }    
    
    // Reset ullright svn:externals to HEAD revision
    $this->runTask('ullright:svn-pin-ullright');
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
  
  
  /**
   * Detect an increase in number of ullright migrations relative to the stored number
   * 
   * @param $revision
   */
  protected function detectNewMigration($revision)
  {
    $command = 'svn list -r'. $revision .' http://bigfish.ull.at/svn/ullright/trunk/plugins/ullCorePlugin/lib/migrations';
    $output = $this->getFilesystem()->execute($command);
    $numOfLines = count(preg_split('/[' . PHP_EOL . ']+/', $output[0])); //0 is stdout
    
    if ($numOfLines > $this->numOfMigrations)
    {
      $this->numOfMigrations = $numOfLines;
      
      return true;
    } 
    
  }
  
  
}