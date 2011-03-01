<?php

class ProcessBouncedEmailsTask extends ullBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'ullright';
    $this->name             = 'process-bounced-emails';
    $this->briefDescription = 'Deletes unreachable e-mail addresses from ullUsers';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] checks the bouncer mailbox and deletes the 
    e-mail addresses from unreachable ullUsers. An ullUser is unreachable if the
    task recognises a configured number of unexpected replies with errors. 
    
    This task usually is invoked by a (daily) cronjob which should run 
EOF;

    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
  }


  protected function execute($arguments = array(), $options = array())
  {
    $this->initializeDatabaseConnection($arguments, $options);
    
    try
    {
      $bouncedEmailAddresses = $this->findBouncedEmailAddresses();
    }
    catch(RuntimeException $e)
    {
      $this->logSection(
        $this->name, 
        $e->getMessage(),
        null,
        'ERROR'
      );
      exit();
    }
    
    $this->logSection($this->name, 'Number of bounced emails: ' . count($bouncedEmailAddresses));

    $this->increaseBounceCounter($bouncedEmailAddresses);
    $this->resetBounceCounter();
    
    $numDeleteUsers = $this->deleteMailAddressesOnBounceMax();
    $this->logSection($this->name, 'Number of deleted email address: ' . $numDeleteUsers);
    
    imap_expunge($this->mbox);
    imap_close($this->mbox);
    $this->logSection($this->name, 'Disconnected from mailbox');
  }
  
  
 /** 
   *  Connects to the bounce mailserver and checks for new undelivered mails
   * 
   * @return list with undelivered mail addresses or false if the mailserver is not reachable:
   */
  public function findBouncedEmailAddresses()
  {
    //connect to imap mailbox
    if(! $this->mbox = imap_open(
        sfConfig::get('app_ull_mail_bounce_mailserver') . sfConfig::get('app_ull_mail_bounce_mailbox'), 
        sfConfig::get('app_ull_mail_bounce_username'),
        sfConfig::get('app_ull_mail_bounce_password')
      ))
    {
      throw new RuntimeException(
        'Could not connect to IMAP mailbox: ' . 
        sfConfig::get('app_ull_mail_bounce_mailserver') . 
        sfConfig::get('app_ull_mail_bounce_mailbox')
      );
    }
     
    $this->logSection(
      $this->name, 
      'Connected to mailbox: ' . 
      sfConfig::get('app_ull_mail_bounce_mailserver') . 
      sfConfig::get('app_ull_mail_bounce_mailbox')
    );
    
    //creates a new folder for processed mails
    imap_createmailbox(
      $this->mbox, 
      sfConfig::get('app_ull_mail_bounce_mailserver') . 
        sfConfig::get('app_ull_mail_bounce_handled_mailbox')
    );
    
    //array with mail numbers (sorted by date)
    $mailNumbers = imap_sort($this->mbox, SORTDATE, 0);
    
    //to decrypt the ullMailLoggedMessage id
    $ullCrypt = ullCrypt::getInstance();
    
    foreach ($mailNumbers as $mailNumber)
    {
      // get the id of the ullMailLogged entry for this message (it is saved in the email header)
      if (preg_match("/ull-mail-logged-id:\s(.*)\s/i", imap_body($this->mbox, $mailNumber), $matches))
      {
        try
        {
          //decrypt the ullMailLoggedMessage id 
          $ullMailLoggedMessageId = $ullCrypt->decrypt(base64_decode($matches[1]));
        }
        catch(RuntimeException $e)
        {
          $this->logSection(
            $this->name, 
            $e->getMessage(),
            null,
            'ERROR'
          );
        }
        if (isset($ullMailLoggedMessageId) && $ullMailLoggedMessage = Doctrine::getTable('UllMailLoggedMessage')->findOneById($ullMailLoggedMessageId))
        {
          //saves the date of receiving the "undeliverable message"
          $header = imap_headerinfo($this->mbox, $mailNumber);
          $ullMailLoggedMessage->failed_at = date('Y-m-d H:i:s', $header->udate);
          
          $ullMailLoggedMessage->save();
          
          // exctract email address
          preg_match("/<(.*)>/i", $ullMailLoggedMessage->to_list, $matches);
          $bouncedEmailAddresses[] = $matches[1];
          
          imap_mail_move($this->mbox, $mailNumber, sfConfig::get('app_ull_mail_bounce_handled_mailbox'));
        }
      }
    }
    
    return $bouncedEmailAddresses;
  }
  
  /**
   * Increases the bounce counter for every ullUser with the given mail address
   * 
   * @param array $bouncedEmailAddresses
   */
  public function increaseBounceCounter($bouncedEmailAddresses)
  {
    //uniquify the array
    $bouncedEmailAddresses = array_values(array_unique($bouncedEmailAddresses));
    
    foreach ($bouncedEmailAddresses as $mailAddress)
    {
      $users = Doctrine::getTable('UllUser')->findByEmail($mailAddress);
      
      foreach ($users as $user)
      {
        if($user->num_email_bounces)
        {
          $user->num_email_bounces = $user->num_email_bounces + 1;
        }
        else
        {
          $user->num_email_bounces = 1;
        }
        $user->save();
      }
    }
  }
  
  /**
   * Reset bounce counter in case user successfully received an email since the last error
   */
  public function resetBounceCounter()
  {
    $users = UllUserTable::findWithBounces();
    
    foreach ($users as $user)
    {
      $userEmails[] = $user->email;
    }
    
    if (isset($userEmails))
    {
      //uniquify the arra
      $userEmails = array_values(array_unique($userEmails));
      
      foreach ($userEmails as $userEmail)
      {
        $ullMailLoggedMessage = UllMailLoggedMessageTable::findLatestLogByEmail($userEmail);
        
        if ($ullMailLoggedMessage && (! $ullMailLoggedMessage->failed_at))
        {
          //get email address from ullMailLoggedMessage
          preg_match("/<(.*)>/i", $ullMailLoggedMessage->to_list, $matches);
          $toResetUsers = Doctrine::getTable('UllUser')->findByEmail($matches[1]);
          
          foreach ($toResetUsers as $toResetUser)
          {
            $toResetUser->num_email_bounces = 0;
            $toResetUser->save();
          }
        }
      }
    }
  }
  
  
  /**
   * If a bouncer counter reaches the maximum, delete the mail address of this ullUser
   */
  public function deleteMailAddressesOnBounceMax()
  {
    $users = Doctrine::getTable('UllUser')->findWithExceededBounceCounterLimit();
    foreach ($users as $user)
    {
       $user->email = '';
       $user->num_email_bounces = 0;
       $user->save();
    }
    
    return count($users);
  }
}