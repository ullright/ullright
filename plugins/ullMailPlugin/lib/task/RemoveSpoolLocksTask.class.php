<?php

class RemoveSpoolLocksTask extends ullBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'ull_mail';
    $this->name             = 'remove-spool-locks';
    $this->briefDescription = 'Removes any locks on UllNewsletterEdition regardless of age';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] removes all Doctrine locks on UllNewsletterEdition,
    regardless of their age. Only used in case the ull_mail:spool-emails task crashed.
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
    
    $answer = $this->askConfirmation(array('Are you sure that you want to remove all ' .
    	'UllNewsletterEdition locks? (y/N)'), 'QUESTION_LARGE', false);
    
    if ($answer)
    {
      $lockingManager = new Doctrine_Locking_Manager_Pessimistic(Doctrine_Manager::connection());
      
      //remove all newsletter edition locks (age = 0)
      $table = Doctrine_Core::getTable('UllNewsletterEdition');
      $lockingManager->releaseAgedLocks(0, $table->getComponentName());
      
      $this->logSection($this->name, 'Removed all UllNewsletterEdition locks');
    }
    else
    {
      $this->logSection($this->name, 'Aborted');
    }
  }
}