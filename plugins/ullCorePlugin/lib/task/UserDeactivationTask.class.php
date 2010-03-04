<?php

class UserDeactivationTask extends ullBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'user-deactivation';
    $this->briefDescription = 'Deactivates users with a reached deactivation or separation date';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] deactivats users with a reached deactivation or separation date
    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
    
    This task usually is invoked by a (daily) cronjob.
EOF;

    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
  }


  protected function execute($arguments = array(), $options = array())
  {
    $this->initializeDatabaseConnection($arguments, $options);
    
    $q = new ullDoctrineQuery;
    
    $q
      ->update('UllUser u')
      ->set('u.ull_user_status_id', '?', Doctrine::getTable('UllUserStatus')->findOneBySlug('separated')->id)
      ->where('u.deactivation_date <= ?', date('Y-m-d'))
      ->orWhere('u.separation_date <= ?', date('Y-m-d'))
      ->wrapExistingWhereInParantheses()
    ;
    
    $num = $q->execute();
    
    $this->log("Deactivated {$num} users");
  }
  
}