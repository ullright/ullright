<?php

class ProcessBouncedEmailsTask extends ullBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'ull_mail';
    $this->name             = 'process-bounced-emails';
    $this->briefDescription = 'Processes bounced newsletter emails';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] processes bounces newsletter emails.
    
    1) Get a list of failed email addresses
    2) Increase the bounce counter of the particular UllUser
    3) Reset bounce counter for UllUsers with temporary errors
    4) Delete emailaddress for UllUsers with exceeded bounce counter
    
    This task usually is invoked by a (daily) cronjob. 
EOF;
    
    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
//    $this->addOption('quiet', null, sfCommandOption::PARAMETER_NONE,
//      'Be less noisy. Output interesting stuff only');
    $this->addOption('dry-run', null, sfCommandOption::PARAMETER_NONE,
      'Dry run - Don\'t do anything');
    
  }


  protected function execute($arguments = array(), $options = array())
  {
    $this->initializeDatabaseConnection($arguments, $options);
    
    if ($options['dry-run'])
    {
      $this->setIsDryRun(true);
    }
    
    try
    {
      $bouncedEmailAddresses = $this->findBouncedEmailAddresses($arguments, $options);
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
    
    $this->logSection($this->name, "Bounced email addresses:");
    var_dump($bouncedEmailAddresses);
    $this->logSection($this->name, 'Number of bounced emails: ' . count($bouncedEmailAddresses));

    $userBounces = $this->increaseBounceCounter($bouncedEmailAddresses, $arguments, $options);
    
    $this->logSection($this->name, "User bounce status:");
    var_dump($userBounces);
    
    $resetUsers = $this->resetBounceCounter($arguments, $options);
    
    $this->logSection($this->name, "Users with reset bounce counter:");
    var_dump($resetUsers);
    
    $deletedUsers = $this->deleteMailAddressesOnBounceMax($arguments, $options);
    $this->logSection($this->name, "Users with deleted email address:");
    var_dump($deletedUsers);
    $this->logSection($this->name, 'Number of users with deleted email address: ' . count($deletedUsers));
    
    if (!$this->isDryRun())
    {
      imap_expunge($this->mbox);
    }
    
    imap_close($this->mbox);
    
    $this->logSection($this->name, 'Disconnected from mailbox');
  }
  
  
 /** 
   * Connects to the bounce mailserver and checks for new undelivered mails
   * 
   * @param array optional $arguments
   * @param array optional $options
   * 
   * @return array list with undelivered mail addresses or false if the mailserver is not reachable:
   */
  public function findBouncedEmailAddresses($arguments = array(), $options = array())
  {
    
    
    //connect to imap mailbox
    $mailbox  = sfConfig::get('app_ull_mail_bounce_mailbox_base') . sfConfig::get('app_ull_mail_bounce_inbox_folder');
    $username = sfConfig::get('app_ull_mail_bounce_username');
    $password  = sfConfig::get('app_ull_mail_bounce_password');
    
    if ( ! $this->mbox = imap_open($mailbox, $username, $password, null, 1))
    {
      throw new RuntimeException(
        'Could not connect to IMAP mailbox: ' . $mailbox . ' with user ' . $username
      );
    }
     
    $this->logSection(
      $this->name, 
      'Connected to mailbox: ' . $mailbox. ' with user ' . $username
    );
    
    //creates a new folder for processed mails
    if (!$this->isDryRun())
    {
//      var_dump(imap_getmailboxes($this->mbox, sfConfig::get('app_ull_mail_bounce_mailbox_base'), "*"));
    
      $processedFolder = sfConfig::get('app_ull_mail_bounce_mailbox_base') . 
        sfConfig::get('app_ull_mail_bounce_handled_folder', 'INBOX.processed');
    
      $status = imap_createmailbox($this->mbox, $processedFolder);

//      var_dump($status);die;

      $unprocessableFolder = sfConfig::get('app_ull_mail_bounce_mailbox_base') . 
        sfConfig::get('app_ull_mail_bounce_unprocessable_folder', 'INBOX.unprocessable');
    
      $status = imap_createmailbox($this->mbox, $unprocessableFolder);
    }
    
    //array with mail numbers (sorted by date)
    $mailNumbers = imap_sort($this->mbox, SORTDATE, 0);
    
    //to decrypt the ullMailLoggedMessage id
    $ullCrypt = ullCrypt::getInstance();
    
    foreach ($mailNumbers as $mailNumber)
    {
      // get the id of the ullMailLogged entry for this message (it is saved in the email header)
      if (preg_match("/ull-mail-logged-id:\s(.*)\s/i", imap_body($this->mbox, $mailNumber), $matches))
      {
        // decrypt the ullMailLoggedMessage id
        try
        {
          $ullMailLoggedMessageId = $ullCrypt->decryptBase64($matches[1], true);
        }
        // In case of failure ignore the mail and move it to unprocessable folder        
        catch(RuntimeException $e)
        {
          $this->logSection(
            $this->name, 
            $e->getMessage(),
            null,
            'ERROR'
          );
          
          if (!$this->isDryRun())
          {
            imap_mail_move($this->mbox, $mailNumber, sfConfig::get('app_ull_mail_bounce_unprocessable_folder', 'INBOX.unprocessable'));
          }
          
          // skip the rest of processing
          continue;
        }
        
        // check if the $ullMailLoggedMessage is valid and process it
        if ($ullMailLoggedMessage = Doctrine::getTable('UllMailLoggedMessage')->findOneById($ullMailLoggedMessageId))
        {
          // check if the the log entry is not already marked as failed (should not happen)
          if (! $ullMailLoggedMessage->failed_at)
          {
            if (!$this->isDryRun())
            {
              //saves the date of receiving the "undeliverable message"
              $header = imap_headerinfo($this->mbox, $mailNumber);
              $ullMailLoggedMessage->failed_at = date('Y-m-d H:i:s', $header->udate);
              $ullMailLoggedMessage->save();
              
              imap_mail_move($this->mbox, $mailNumber, sfConfig::get('app_ull_mail_bounce_handled_folder', 'INBOX.processed'));
            }
            
            // extract email address
            preg_match("/<(.*)>/i", $ullMailLoggedMessage->to_list, $matches);
            // and remember it
            $bouncedEmailAddresses[] = $matches[1];
          }
          else
          {
            if (!$this->isDryRun())
            {
              imap_mail_move($this->mbox, $mailNumber, sfConfig::get('app_ull_mail_bounce_unprocessable_folder', 'INBOX.unprocessable'));
            }
          }
        }
        else
        {
          if (!$this->isDryRun())
          {
            imap_mail_move($this->mbox, $mailNumber, sfConfig::get('app_ull_mail_bounce_unprocessable_folder', 'INBOX.unprocessable'));
          }
        }
      }
      else
      {
        if (!$this->isDryRun())
        {
          imap_mail_move($this->mbox, $mailNumber, sfConfig::get('app_ull_mail_bounce_unprocessable_folder', 'INBOX.unprocessable'));
        }
      }
      
    }
    
    return $bouncedEmailAddresses;
  }
  
  
  /**
   * Increases the bounce counter for every ullUser with the given mail address
   * 
   * @param array $bouncedEmailAddresses
   * @param array optional $arguments
   * @param array optiona $options
   * 
   * @return array of users with positive bounce counter 
   */
  public function increaseBounceCounter($bouncedEmailAddresses, $arguments = array(), $options = array())
  {
    //uniquify the array
    $bouncedEmailAddresses = array_values(array_unique($bouncedEmailAddresses));
    
    $userBounces = array();
    
    foreach ($bouncedEmailAddresses as $mailAddress)
    {
      $users = Doctrine::getTable('UllUser')->findByEmail($mailAddress);
      
      foreach ($users as $user)
      {
        if ($user->num_email_bounces)
        {
          $user->num_email_bounces = $user->num_email_bounces + 1;
        }
        else
        {
          $user->num_email_bounces = 1;
        }
        
        if (!$this->isDryRun())
        {
          $user->save();
        }
        
        $userBounces[$user->display_name . ' <' . $user->email . '>'] = $user->num_email_bounces;
      }
    }
    
    return ($userBounces);
  }
  
  /**
   * Reset bounce counter in case user successfully received an email since the last error
   * 
   * @param array optional $arguments
   * @param array optiona $options 
   * 
   * @return array list of users with reset bounce counter
   */
  public function resetBounceCounter($arguments = array(), $options = array())
  {
    $users = UllUserTable::findWithBounces();
    
    foreach ($users as $user)
    {
      $userEmails[] = $user->email;
    }
    
    if (isset($userEmails))
    {
      $resetUsers = array();
      
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
            if (!$this->isDryRun())
            {
              $toResetUser->save();
            }
            
            $resetUsers[] = $toResetUser->display_name . ' <' . $toResetUser->email . '>';
          }
        }
      }
      
      return $resetUsers;
    }
  }
  
  
  /**
   * If a bounce counter reaches the maximum, delete the mail address of this ullUser
   * 
   * @param array optional $arguments
   * @param array optiona $options 
   * 
   * @return array list of users with deleted email address
   */
  public function deleteMailAddressesOnBounceMax($arguments = array(), $options = array())
  {
    $users = Doctrine::getTable('UllUser')->findWithExceededBounceCounterLimit();
    
    $userList = array();
    
    foreach ($users as $user)
    {
      $userList[] = $user->display_name . ' <' . $user->email . '>';
      
      $user->email = '';
      $user->num_email_bounces = 0;
      
      if (!$this->isDryRun())
      {
        $user->save();
      }
      
    }
    
    return $userList;
  }
}