<?php

class DataMigrateTask extends sfDoctrineBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('version', sfCommandArgument::OPTIONAL, 'The "data" version to migrate to'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('up', null, sfCommandOption::PARAMETER_NONE, 'Migrate up one "data" version'),
      new sfCommandOption('down', null, sfCommandOption::PARAMETER_NONE, 'Migrate down one "data" version'),
      new sfCommandOption('dry-run', null, sfCommandOption::PARAMETER_NONE, 'Do not persist "data" migrations'),
    ));

    $this->namespace = 'ullright';
    $this->name = 'migrate-data';
    $this->briefDescription = 'Performs data manipulations after all schema migrations have been completed';

    $this->detailedDescription = <<<EOF
The [ullright:data-migrate|INFO] performs data manipulations after all schema migrations have been completed

  [./symfony ullright:data-migrate|INFO]

Provide a version argument to migrate to a specific "data" version:

  [./symfony ullright:data-migrate 10|INFO]

To migration up or down one "data" migration, use the [--up|COMMENT] or [--down|COMMENT] options:

  [./symfony ullright:data-migrate --down|INFO]

If your database supports rolling back DDL statements, you can run "data" migrations
in dry-run mode using the [--dry-run|COMMENT] option:

  [./symfony ullright:data-migrate --dry-run|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    $config = $this->getCliConfig();
    
    //remove doctrine, add pre_build_model; find a more reliable way to do this
    $preBuildModelMigrationsPath = substr($config['migrations_path'], 0, -8);
    $preBuildModelMigrationsPath .= 'data';
    
    if (!file_exists($preBuildModelMigrationsPath))
    {
       $this->logBlock(array(
        'The "data" migration directory does not exist:', '', $preBuildModelMigrationsPath),
        'ERROR_LARGE');
       
       return;
    }
    
    $migration = new Data_Doctrine_Migration($preBuildModelMigrationsPath);
    $from = $migration->getCurrentVersion();

    if (is_numeric($arguments['version']))
    {
      $version = $arguments['version'];
    }
    else if ($options['up'])
    {
      $version = $from + 1;
    }
    else if ($options['down'])
    {
      $version = $from - 1;
    }
    else
    {
      $version = $migration->getLatestVersion();
    }

    if ($from == $version)
    {
      $this->logSection('data migrate', sprintf('Already at migration version %s', $version));
      return;
    }

    $this->logSection('data migrate', sprintf('Migrating from version %s to %s%s', $from, $version, $options['dry-run'] ? ' (dry run)' : ''));
    try
    {
      $migration->migrate($version, $options['dry-run']);
    }
    catch (Exception $e)
    {
    }

    // render errors
    if ($migration->hasErrors())
    {
      if ($this->commandApplication && $this->commandApplication->withTrace())
      {
        $this->logSection('data migrate', 'The following errors occurred:');
        foreach ($migration->getErrors() as $error)
        {
          $this->commandApplication->renderException($error);
        }
      }
      else
      {
        $this->logBlock(array_merge(
          array('The following errors occurred:', ''),
          array_map(create_function('$e', 'return \' - \'.$e->getMessage();'), $migration->getErrors())
        ), 'ERROR_LARGE');
      }

      return 1;
    }

    $this->logSection('data migrate', 'Migration complete');
  }
}

