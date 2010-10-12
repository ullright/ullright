<?php

class DumpModelToYamlTask extends ullBaseTask
{

  protected
    $dbName = '',
    $dbUsername = '',
    $dbPassword = ''
  ;

  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'dump-model-to-yaml';
    $this->briefDescription = 'Dumps a model to a yaml fixture';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] dumps a model to a yaml fixture file

    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
    
EOF;

    $this->addArgument('model', sfCommandArgument::REQUIRED, 'The model name');
    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
    
  }


  protected function execute($arguments = array(), $options = array())
  {
    $configuration = ProjectConfiguration::getApplicationConfiguration(
    $arguments['application'], $arguments['env'], true);

    $databaseManager = new sfDatabaseManager($configuration);    
    
    $data = new Doctrine_Data();
    $targetDir = sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'fixtures';
    $data->exportData($targetDir, 'yml', $models = array($arguments['model']), true);
    
  }
 
}

