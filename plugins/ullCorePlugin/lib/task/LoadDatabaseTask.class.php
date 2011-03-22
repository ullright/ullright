<?php

class LoadDatabaseTask extends DumpDatabaseTask
{

  protected
    $dbName = '',
    $dbUsername = '',
    $dbPassword = '',
    $dumpExtension = '.mysql.dump.bz2'
  ;

  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'load-database';
    $this->briefDescription = 'Loads database dump from lib/sql/ullright_XXX.mysql.dump.bz2 (for MySQL)';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] Loads database dump from lib/sql/ullright_XXX.mysql.dump.bz2

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
    
    $this->logSection($this->name, 'Loading dump from lib/sql/');
    
    $this->runTask('doctrine:drop-db', array(), array('no-confirmation' => true));
    $this->runTask('doctrine:build-db');
    
    $command = 'bzcat < ' . 
      sfConfig::get('sf_data_dir') . '/sql/' . $this->dbName . $this->dumpExtension . 
      ' | mysql -u ' . $this->dbUsername . ' --password=\'' . $this->dbPassword . '\'' . 
      ' ' . $this->dbName
    ;
    
    $this->log($this->getFilesystem()->execute($command));
  }
 
}

