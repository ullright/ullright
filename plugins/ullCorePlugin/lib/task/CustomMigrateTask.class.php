<?php

class CustomMigrateTask extends sfDoctrineBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('version', sfCommandArgument::OPTIONAL, 'The custom version to migrate to'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('up', null, sfCommandOption::PARAMETER_NONE, 'Migrate up one custom version'),
      new sfCommandOption('down', null, sfCommandOption::PARAMETER_NONE, 'Migrate down one custom version'),
      new sfCommandOption('dry-run', null, sfCommandOption::PARAMETER_NONE, 'Do not persist custom migrations'),
    ));

    $this->aliases = array('custom-migrate');
    $this->namespace = 'ullright';
    $this->name = 'custom-migrate';
    $this->briefDescription = 'Migrates database to current/specified custom version';

    $this->detailedDescription = <<<EOF
The [ullright:custom-migrate|INFO] task migrates the database using custom versions:

  [./symfony ullright:custom-migrate|INFO]

Provide a version argument to migrate to a specific custom version:

  [./symfony ullright:custom-migrate 10|INFO]

To migration up or down one custom migration, use the [--up|COMMENT] or [--down|COMMENT] options:

  [./symfony ullright:custom-migrate --down|INFO]

If your database supports rolling back DDL statements, you can run custom migrations
in dry-run mode using the [--dry-run|COMMENT] option:

  [./symfony ullright:custom-migrate --dry-run|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    $config = $this->getCliConfig();
    
    //remove doctrine, add custom; find a more reliable way to do this
    $customMigrationsPath = substr($config['migrations_path'], 0, -8);
    $customMigrationsPath .= 'custom';
    
    $migration = new Custom_Doctrine_Migration($customMigrationsPath);
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
      $this->logSection('doctrine', sprintf('Already at migration version %s', $version));
      return;
    }

    $this->logSection('doctrine', sprintf('Migrating from version %s to %s%s', $from, $version, $options['dry-run'] ? ' (dry run)' : ''));
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
        $this->logSection('doctrine', 'The following errors occurred:');
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

    $this->logSection('doctrine', 'Migration complete');
  }
}

