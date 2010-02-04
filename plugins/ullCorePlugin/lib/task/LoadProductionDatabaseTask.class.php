<?php

class LoadProductionDatabaseTask extends LoadDatabaseTask
{

  protected
    $dumpExtension = '.production.mysql.dump.bz2'
  ;

  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'load-production-database';
    $this->briefDescription = 'Loads production database dump from lib/sql/ullright_XXX.production.mysql.dump.bz2 (for MySQL)';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] Loads database dump from lib/sql/ullright_XXX..production.mysql.dump.bz2

    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
    
EOF;

    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
    
  }
    
}

