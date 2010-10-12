<?php

class CleanYamlDumpTask extends ullBaseTask
{

  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'clean-yaml-dump';
    $this->briefDescription = 'Cleans a yaml dump located in data/fixtures';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] cleans a yaml dump

    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
    
EOF;
    $this->addArgument('source', sfCommandArgument::REQUIRED, 'Source path');
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
    
    $targetDir = sfConfig::get('sf_root_dir');
    
    $path = $targetDir . DIRECTORY_SEPARATOR . $arguments['source'];
    
    $data = sfYaml::load($path);
    
    foreach ($data as &$model)
    {
      foreach ($model as &$row)
      {
        unset($row['created_at']);
        unset($row['updated_at']);
        unset($row['Creator']);
        unset($row['Updator']);
        if (isset($row['version']))
        {
          unset($row['version']);
        }
        if (isset($row['UllEmploymentType']))
        {
          unset($row['UllEmploymentType']);
        }
        if (isset($row['UllUserStatus']))
        {
          unset($row['UllUserStatus']);
        }
        
        $row['namespace'] = 'testUsers';
      }
    }    
    
    
    $yaml = sfYaml::dump($data, 999);
    
    $fh = fopen($path . '.cleaned.yml', 'w');
    fwrite($fh, $yaml);
    fclose($fh);
    
  }
 
}


