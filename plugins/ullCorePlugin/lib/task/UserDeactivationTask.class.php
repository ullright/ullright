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
    // Delegated to a public method to allow testing
    $this->deactivateUsers($arguments, $options);
  }
  
  
  public function deactivateUsers($arguments = array(), $options = array())
  {
    $this->initializeDatabaseConnection($arguments, $options);
    
    $inactiveId = Doctrine::getTable('UllUserStatus')->findOneBySlug('inactive')->id;
    
    $q = new ullDoctrineQuery;
    
    $q
      ->from('UllUser u')
      ->where('u.deactivation_date <= ?', date('Y-m-d'))
      ->orWhere('u.separation_date <= ?', date('Y-m-d'))
      ->wrapExistingWhereInParantheses()
      ->addWhere('u.ull_user_status_id <> ?', $inactiveId)
    ;
    
    $users = $q->execute();
    
    $this->logSection($this->name, 'Deactivating ' . count($users) . ' users');
    
    foreach ($users as $user)
    {
      $this->log('Deactivating '. $user->display_name);
      $user['ull_user_status_id'] = $inactiveId;
      $user->save();
    }    
  }
  
}