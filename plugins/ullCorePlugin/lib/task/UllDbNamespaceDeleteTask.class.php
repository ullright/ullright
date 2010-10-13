<?php

class UllDbNamespaceDeleteTask extends sfBaseTask
{
  protected $wrongNamespaceRecords = array();
  
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
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection($this->name, 'Deleting records...');

    $configuration = ProjectConfiguration::getApplicationConfiguration(
    $options['application'], $options['env'], true);

    sfContext::createInstance($configuration);
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));

    $databaseManager = new sfDatabaseManager($configuration);

    Doctrine::loadModels(sfConfig::get('sf_root_dir') . '/lib/model/doctrine');
    $modelNames = Doctrine::getLoadedModels();
    //very important call (initalizes behaviors)
    $modelNames = Doctrine::initializeModels($modelNames);

    $this->deleteRecords($modelNames, $arguments['namespace']);
  }

  protected function deleteRecords($modelNames, $namespace)
  {
    $constrainedRecords = array();

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
            $this->logSection($this->name, "Deleted $num records from $modelName");
          }
        }
        catch (Exception $e)
        {
          $this->logSection($this->name, "Could not simply delete all records from $modelName");

          //find the objects for which deletion failed
          $q = new Doctrine_Query;
          $q
            ->select()
            ->from($modelName . ' x')
            ->where('x.namespace = ?', $namespace)
          ;

          $results = $q->execute();
           
          foreach ($results as $result)
          {
            try
            {
              $result->delete();
            }
            catch (Exception $e)
            {
              $constrainedRecords[] = $result;
            }
          }
        }
      }
    }

    //recursively delete constrained records
    $this->deleteConstrainedRecords($constrainedRecords, $namespace);

    //display records for which deletion is not possible
    $this->log('');
    foreach($this->wrongNamespaceRecords as $wrongNamespaceRecord)
    {
      $this->logSection($this->name, 'Could not delete ' . get_class($wrongNamespaceRecord) .
        ' record with id: ' . $wrongNamespaceRecord['id']);
      $this->logSection($this->name, 'The following constraining records have the wrong namespace:');
       
      $records = array();
      ullConstraintResolver::findConstrainingRecords($wrongNamespaceRecord, $records, 'records');

      foreach ($records as $record)
      {
        if ($record['namespace'] != $namespace)
        {
          $this->logSection($this->name, 'Record of class ' . get_class($record) . ' with id: ' . $record['id']);
        }
      }
    }
  }

  protected function deleteConstrainedRecords($constrainedRecords, $namespace)
  {
    //$this->logSection($this->name, 'Handling ' . count($constrainedRecords) . ' constraining record(s)');

    $nextArray = array();
    foreach($constrainedRecords as $constrainedRecord)
    {
      try
      {
        $constrainedRecord->delete();
      }
      catch (Exception $e)
      {
        //looking at this records's constraining records
        $records = array();
        ullConstraintResolver::findConstrainingRecords($constrainedRecord, $records, 'records');

        $hasWrongNamespaceRecord = false;
        foreach ($records as $record)
        {
          if ($record['namespace'] != $namespace)
          {
            $hasWrongNamespaceRecord = true;
            break;
          }
        }

        if ($hasWrongNamespaceRecord)
        {
          //not removing record because of wrong namespace constraining records
          $this->wrongNamespaceRecords[] = $constrainedRecord;
        }
        else
        {
          //can this even happen? at least not with our test data
          //has constraining records but namespace is ok for all of them
          //storing for next run
          $nextArray[] = $constrainedRecord;
        }
      }
    }

    if (count($nextArray) > 0)
    {
      //recursive call
      $this->deleteConstrainedRecords($nextArray, $namespace);
    }
  }
}