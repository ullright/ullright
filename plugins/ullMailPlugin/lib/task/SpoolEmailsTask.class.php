<?php

class SpoolEmailsTask extends ullBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'ull_mail';
    $this->name             = 'spool-emails';
    $this->briefDescription = 'Looks for composed newsletters and spools them';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] looks for composed newsletters and spools them
    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
    
    This task usually is invoked by a frequently run cronjob.
EOF;

    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'dev');
    $this->addOption('less-noisy', null, sfCommandOption::PARAMETER_NONE,
      'Be less noisy. Output interesting stuff only. Used for cron jobs');        
  }


  protected function execute($arguments = array(), $options = array())
  {
    if ($options['less-noisy'])
    {
      $this->setIsLessNoisy(true);
    }    
    
    $this->initializeDatabaseConnection($arguments, $options);
    $context = sfContext::createInstance($this->configuration);
    
    //important note concerning Doctrine locking
    //the locking manager has a serious bug: row-level locking is not possible,
    //every time an object is locked the whole table (= model) gets locked.
    //the upside: for our purposes this behavior is 'good enough'
    
    //create locking manager and unique id for locking
    //pid + random string should be unique enough
    $lockingManager = new Doctrine_Locking_Manager_Pessimistic(Doctrine_Manager::connection());
    $uniqueId = uniqid(getmypid(), true);
    
    //remove old newsletter edition locks (> 15 min)
    $table = Doctrine_Core::getTable('UllNewsletterEdition');
    $lockingManager->releaseAgedLocks(900, $table->getComponentName());
    
    $editions = UllNewsletterEditionTable::findEditionsToBeSpooled();
    
    if (!count($editions))
    {
      $this->logSection($this->name, 'No editions found to be spooled!');
      return;
    }
    
    $failedRecipients = array();
    
    foreach ($editions as $edition)
    {
      //request exclusive lock for the newsletter object
      if (!$this->requestLock($lockingManager, $edition, $uniqueId))
      {
        $this->logNoisySection($this->name, 'Could not retrieve lock for newsletter edition with id: ' .
          $edition['id']);
        continue;
      }
      
      $this->logNoisySection($this->name, 'Now spooling ' . $edition['subject']);
      
      $context->getUser()->setCulture($edition['sender_culture']);
      
      $user = $edition->Sender;
      $mail = $edition->createMailMessage($user);
      
      $recipients = $edition->findRecipients();
      
      //recipient count might have changed in the time period
      //between submission and spooling
      $edition['num_total_recipients'] = count($recipients);
      
      //TODO: allow to give an array of UllUsers
      //TODO: add handling for multiple UllUsers for batchSend
      $numSent = 0;
      
      $connection = Doctrine_Manager::connection();
      $transaction = $connection->beginTransaction();
      $transaction = $connection->transaction;
      $transaction->setIsolation('SERIALIZABLE');
      
      $this->logNoisySection($this->name, 'Beginning to spool');
      
      try
      {
        foreach ($recipients as $recipient)
        {
          $this->logNoisySection($this->name, '...for ' . $recipient['first_name'] . ' ' . $recipient['last_name']);
          
          $currentMail = clone $mail;
          $currentMail->clearRecipients(); // TODO: why is this necessary despite cloning?
          
          try 
          {
            $currentMail->addAddress($recipient);
            $this->getMailer()->sendQueue($currentMail);
            $numSent++;
          }
          catch (Exception $e)
          {
            $this->logNoisySection($this->name, 'Invalid address: ' . $recipient['email']);
            $failedRecipients[] = $recipient['email'];
            
            $this->createFailedLoggedMessage($currentMail, $recipient);
          }
        }  
        
        $edition['queued_at'] = date('Y-m-d H:i:s');
        $edition->save();
        
        $this->logNoisySection($this->name, 'Comitting all spooled messages');
        $connection->commit();
      }
      catch (Exception $e)
      {
        $this->logNoisySection($this->name, 'Exception during spooling, executing rollback');
        $connection->rollback();
        
        $this->releaseLock($lockingManager, $edition, $uniqueId);
        throw $e;
      }
      
      $this->releaseLock($lockingManager, $edition, $uniqueId);
      
      $this->logNoisySection($this->name, "Queued {$numSent} of " . count($recipients) . ' messages');
      
      if (count($failedRecipients) > 0)
      {
        $this->logNoisySection($this->name, 'Invalid recipients: ' . count($failedRecipients));
        $this->logNoisySection($this->name, print_r($failedRecipients, true));
      }
    } // end of foreach edition        
  }
  
  /**
   * Helper function to get a doctrine lock
   * 
   * @param unknown_type $lockingManager
   * @param unknown_type $edition
   * @param unknown_type $uniqueId
   */
  protected function requestLock($lockingManager, $edition, $uniqueId)
  {
    try
    {
      return $lockingManager->getLock($edition, $uniqueId);
    }
    catch (Doctrine_Locking_Exception $dle)
    {
      $this->log($dle->getMessage());
      
      return false;
    }
  }
  
  /**
   * Helper function to release a lock
   * 
   * @param unknown_type $lockingManager
   * @param unknown_type $edition
   * @param unknown_type $uniqueId
   */
  protected function releaseLock($lockingManager, $edition, $uniqueId)
  {
    try
    {
      $lockingManager->releaseLock($edition, $uniqueId);
    }
    catch(Doctrine_Locking_Exception $dle)
    {
      $this->log($dle->getMessage());
    }
  }
  
  /**
   * Create a UllMailLoggedMessage entry for an invalid email address for logging

   * @param UllNewsletteredition $edition
   * @param UllUser $user
   */
  protected function createFailedLoggedMessage(ullsfmail $mail, UllUser $recipient)
  {
    $edition = Doctrine::getTable('UllNewsletterEdition')->findOneById($mail->getNewsletterEditionId());
    
    $log = new UllMailLoggedMessage();
    $log->UllNewsletterEdition = $edition;
    $log->sender = $edition->Sender->getEmailTo();
    $log->MainRecipient = $recipient;
    $log->to_list = $recipient->getEmailTo();
    $log->subject = $edition->subject;
    $log->html_body = $edition->getBody();
    $log->failed_at = new Doctrine_Expression('NOW()');
    $log->last_error_message = 'spoolEmailsTask: invalid address: ' . $recipient->email;
    $log->UllMailError = Doctrine::getTable('UllMailError')->findOneBySlug('invalid-email-address');
    $log->save();
     
  }
}
