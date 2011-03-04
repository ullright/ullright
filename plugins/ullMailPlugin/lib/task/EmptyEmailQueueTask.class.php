<?php

class EmptyEmailQueueTask extends ullBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'ull_mail';
    $this->name             = 'empty-email-queue';
    $this->briefDescription = 'Empties the email queue for testing';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] empties the email queue for testing.
    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
    
EOF;

    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'dev');
  }


  protected function execute($arguments = array(), $options = array())
  {
    $this->initializeDatabaseConnection($arguments, $options);
    
    $this->askConfirmation('Are you sure that you want to empty the email queue?');
    
    $records = Doctrine::getTable('UllMailQueuedMessage')->findAll();
    
    $num = count($records);
    
    $records->delete();
    
    $this->log('Deleted ' . $num . ' queued mail messages (table UllMailQueuedMessage');
  }
  
}