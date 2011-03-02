<?php

/**
 * This task drops and recreates all foreign keys.
 * It uses information_schema, so it should work
 * at least with MySQL, MSSQL and PostgreSQL, although
 * it is only tested with MySQL.
 */
class RecreateForeignKeysTask extends ullBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'recreate-foreign-keys';
    $this->briefDescription = 'Drops all foreign keys and recreates them';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] drops all foreign keys by modifying the
    information_schema directly (available for MySQL, MSSQL, PostgreSQL, possibly others).
    It then recreates all foreign keys based on the current Doctrine schema.
    
    Note: Only tested on MySQL.
    
    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
EOF;

    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
  }

  public static function dropAllForeignKeys()
  {
    //get the current schema name
    $connection = Doctrine_Manager::connection();
    $info = Doctrine_Manager::getInstance()->parsePdoDsn($connection->getOption('dsn'));
    $dbName = $info['dbname'];
    
    //retrieve all foreign key names from the information_schema
    $sql =
          'SELECT table_name, constraint_name ' .
          'FROM information_schema.referential_constraints ' .
          "WHERE unique_constraint_schema = '" . $dbName . "'";

    $constraints = $connection->fetchAll($sql);
    foreach ($constraints as $constraint)
    {
      $connection->export->dropForeignKey($constraint['table_name'], $constraint['constraint_name']);
    }

    return count($constraints);
  }
  
  public static function createAllForeignKeysFromModel()
  {
    Doctrine::loadModels(sfConfig::get('sf_root_dir') . '/lib/model/doctrine');
    $models = Doctrine_Core::getLoadedModels();
    //this call is needed to load behaviors (adds FKs for translation, version, ...) 
    $models = Doctrine_Core::initializeModels($models);
    
    //create a list of foreign key definitions ...
    $foreignKeys = array();
    foreach ($models as $modelName)
    {
      $table = Doctrine::getTable($modelName);
      $export = $table->getExportableFormat(true);

      $foreignKeys[$export['tableName']] = $export['options']['foreignKeys'];
    }

    //retrieve Doctrine connection object
    $connection = Doctrine_Manager::connection();
      
    //... and iterate through it
    $counters = array('ok' => 0, 'error' => array());
    foreach ($foreignKeys as $tableName => $foreignKeyDefinitions)
    {
      foreach ($foreignKeyDefinitions as $foreignKeyDefinition)
      {
        try
        {
          $connection->export->createForeignKey($tableName, $foreignKeyDefinition);
          $counters['ok'] = $counters['ok'] + 1;
        }
        catch (Exception $e)
        {
          $counters['error'][] = var_export($foreignKeyDefinition, true);
        }
      }
    }
    
    return $counters;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->initializeDatabaseConnection($arguments, $options);
   
    $answer = $this->askConfirmation(array('This command will drop all foreign keys from the schema:',
      '', $dbName, '', 'Are you sure you want to proceed? (y/N)'), 'QUESTION_LARGE', false);

    if ($answer)
    {
      //drop all foreign keys
      $this->logSection($this->name, 'Dropping all foreign keys ...');
      $droppedCount = self::dropAllForeignKeys();
      $this->logSection($this->name, 'Dropped ' . $droppedCount . ' foreign keys');
      
      
      //recreate all foreign keys
      $this->logSection($this->name, 'Recreating foreign keys from model ...');
      $counters = self::createAllForeignKeysFromModel($models);       
      $this->logSection($this->name, 'Recreated ' . $counters['ok'] . ' foreign keys from model');
      
      if (count($counters['error']) > 0)
      {
        $this->logSection($this->name, 'Failed to create ' .
          count($counters['error']) . ' foreign key(s)', null, ERROR);
        
        foreach ($counters['error'] as $error)
        {
          $this->logBlock(array('Error while creating foreign key,' .
            ' tried to create the following definition:', $error), ERROR);
        }
      }
      
      $this->logSection($this->name, 'Finished');
      return;
    }

    $this->logSection($this->name, 'Canceled');
  }
}