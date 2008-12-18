<?php

class UllDbNamespaceDeleteTask extends sfBaseTask
{
  
  protected function configure()
  {
  	$this->namespace        = 'ullright';
    $this->name             = 'db-namespace-delete';
  	$this->briefDescription = 'Deletes all records with the given namespace';
  	$this->detailedDescription = <<<EOF
The [{$this->name} task|INFO] deletes all records with the given namespace

Call it with:

  [php symfony {$this->namespace}:{$this->name}|INFO]
EOF;

    $this->addArgument('namespace', sfCommandArgument::REQUIRED, 
      'The namespace to delete');
  	$this->addArgument('application', sfCommandArgument::OPTIONAL,
  	  'The application name', 'frontend');
  	$this->addArgument('env', sfCommandArgument::OPTIONAL,
  	  'The environment', 'cli');
  }


  protected function execute($arguments = array(), $options = array())
  {
	$this->logSection($this->name, 'Deleting records...');

      $configuration = ProjectConfiguration::getApplicationConfiguration(
  	  $arguments['application'], $arguments['env'], true);
  
  	  $databaseManager = new sfDatabaseManager($configuration);
  	  
  	  Doctrine::loadModels(sfConfig::get('sf_root_dir') . '/lib/model/doctrine');
  	  
  	  $modelNames = Doctrine::getLoadedModels();
  	  
      $this->deleteRecords($modelNames, $arguments['namespace']);
  }
  
  protected function deleteRecords($modelNames, $namespace)
  {
    $constraintFailures = array();
    
    foreach ($modelNames as $modelName)
    {
      if (Doctrine::getTable($modelName)->hasColumn('namespace'))
      {
        $q = new Doctrine_Query;
        $q
          ->delete()
          ->from($modelName . ' x')
          ->where('x.namespace = ?', $namespace)
        ;
        
        try
        {
          $num = $q->execute();
          if ($num)
          {
            $this->log("Deleted $num records from $modelName");
          }
        }
        catch (Exception $e)
        {
          $constraintFailures[] = $modelName;
        }
        
      }
    }  

    // recursivly call delete for constraint failures
    if ($constraintFailures)
    {
      $this->deleteRecords($constraintFailures, $namespace);
    }
  }

}