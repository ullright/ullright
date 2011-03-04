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
  }


  protected function execute($arguments = array(), $options = array())
  {
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
      $this->log('No editions found to be spooled!');
      return;
    }
    
    $failedRecipients = array();
    
    foreach ($editions as $edition)
    {
      //request exclusive lock for the newsletter object
      if (!$this->requestLock($lockingManager, $edition, $uniqueId))
      {
        $this->log('Could not retrieve lock for newsletter edition with id: ' .
          $edition['id']);
        continue;
      }
      
      $this->logBlock('Now spooling ' . $edition['subject'], 'INFO');
      
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
      
      $this->log('Beginning to spool');
      
      try
      {
        foreach ($recipients as $recipient)
        {
          $this->log('...for ' . $recipient['first_name'] . ' ' . $recipient['last_name']);
          
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
            $this->log('Invalid address: ' . $recipient['email']);
            $failedRecipients[] = $recipient['email'];
          }
        }  
        
        $edition['queued_at'] = date('Y-m-d H:i:s');
        $edition->save();
        
        $this->log('Comitting all spooled messages');
        $connection->commit();
      }
      catch (Exception $e)
      {
        $this->log('Exception during spooling, executing rollback');
        $connection->rollback();
        
        $this->releaseLock($lockingManager, $edition, $uniqueId);
        throw $e;
      }
      
      $this->releaseLock($lockingManager, $edition, $uniqueId);
      
      $this->logBlock("Queued {$numSent} of " . count($recipients) . ' messages', 'INFO');
      $this->log('Failed Recipients: ' . count($failedRecipients));
      $this->log(print_r($failedRecipients, true));
    }        
  }
  
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
}