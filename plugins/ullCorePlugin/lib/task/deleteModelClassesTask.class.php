<?php

class DeleteModelClassesTask extends ullBaseTask
{
  
  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'delete-model-classes';
    $this->briefDescription = 'Deletes model, filter and filter form classes for a model';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] {$this->briefDescription}
    
    Using svn delete!

    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
    
EOF;

    $this->addArgument('model_name', sfCommandArgument::REQUIRED,
      'Model name');
    $this->addArgument('plugin_name', sfCommandArgument::OPTIONAL,
      'Plugin name, if it is a plugin\'s model', null);    
    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
    
    $this->addOption('no-confirmation', null, sfCommandOption::PARAMETER_NONE, 
      'Skip confirmation question');
    
    //TODO: add "only-generated" option
    //TODO: handle plugin model files which lay in the plugin itself
    //TODO: for the dev environment: we cannot always assume using svn e.g. for the svn:externaled generated model files
    
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection($this->name, 'Initializing');
    
    $this->printDeleteWarning($arguments, $options);
    
    $this->deleteModelClasses($arguments['model_name'], $arguments['plugin_name']);
  }
  
}

