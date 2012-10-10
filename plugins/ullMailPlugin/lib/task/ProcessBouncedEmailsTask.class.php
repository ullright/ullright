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
    2) Increase the bounce counter of the particular UllUser(s)
    3) Reset bounce counter for UllUsers with temporary errors
    4) Delete emailaddress for UllUsers with exceeded bounce counter
    
    This task usually is invoked by a (daily) cronjob. 
EOF;
    
    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'cli');
    $this->addOption('dry-run', null, sfCommandOption::PARAMETER_NONE,
      'Dry run - Don\'t do anything');
    $this->addOption('less-noisy', null, sfCommandOption::PARAMETER_NONE,
      'Be less noisy. Output interesting stuff only. Used for cron jobs');
    $this->addOption('skip-failed-check', null, sfCommandOption::PARAMETER_NONE,
      'Ignore the check if a message is already marked as failed (Normaly prevents double processing)');         
    
  }


  /**
   * Execute task
   * @see lib/vendor/symfony/lib/task/sfTask::execute()
   */
  protected function execute($arguments = array(), $options = array())
  {
    if ($options['dry-run'])
    {
      $this->setIsDryRun(true);
    }
    
    if ($options['less-noisy'])
    {
      $this->setIsLessNoisy(true);
    }
    
    $this->initializeDatabaseConnection($arguments, $options);

    try
    {
      $bouncedEmailAddresses = $this->findBouncedEmailAddresses($arguments, $options);
    }
    catch (RuntimeException $e)
    {
      $this->logSection(
        $this->name, 
        $e->getMessage(),
        null,
        'ERROR'
      );
      exit();
    }
    
    $this->logNoisySectionIf(
      $bouncedEmailAddresses, 
      $this->name, 
      "Bounced email addresses: \n " . implode("\n", $bouncedEmailAddresses),
      999999
    );
    $this->logNoisySectionIf(
      $bouncedEmailAddresses, 
      $this->name, 
      'Number of bounced emails: ' . count($bouncedEmailAddresses)
    );

    
    
    $userBounces = $this->increaseBounceCounter($bouncedEmailAddresses, $arguments, $options);
    $this->logNoisySectionIf(
      $userBounces, 
      $this->name, 
      "User bounce status: \n " . print_r($userBounces, true),
      999999
    );
    $this->logNoisySectionIf(
      $userBounces, 
      $this->name, 
      'Number of users with positive bounce counter: ' . count($userBounces)
    );       

    
    /*
     * Deactivated at the moment 
     * @see http://www.ullright.org/ullFlow/edit/app/bug_tracking/order/priority/order_dir/asc/doc/1548
     * 
    $resetUsers = $this->resetBounceCounter($arguments, $options);
    $this->logNoisySectionIf(
      $resetUsers, 
      $this->name, 
      "Users with reset bounce counter: \n " . implode("\n", $resetUsers),
      999999
    );
    $this->logNoisySectionIf(
      $resetUsers, 
      $this->name, 
      'Number of users with reset bounce counter: ' . count($resetUsers)
    );
    */
    
    
    $deletedUsers = $this->deleteMailAddressesOnBounceMax($arguments, $options);
    $this->logNoisySectionIf(
      $deletedUsers, 
      $this->name, 
      "Users with deleted email address: \n " . implode("\n", $deletedUsers),
      999999
    );     
    $this->logNoisySectionIf(
      $deletedUsers, 
      $this->name, 
      'Number of users with deleted email address: ' . count($deletedUsers)
    );
    
    
    if (!$this->isDryRun())
    {
      imap_expunge($this->mbox);
    }
    
    imap_close($this->mbox);
    
    $this->logSection($this->name, 'Disconnected from mailbox');
  }
  
  
 /** 
   * Connect to the bounce mailserver and check for new undelivered mails
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

      $unprocessableFolder = sfConfig::get('app_ull_mail_bounce_mailbox_base') . 
        sfConfig::get('app_ull_mail_bounce_unprocessable_folder', 'INBOX.unprocessable');
      $status = imap_createmailbox($this->mbox, $unprocessableFolder);
    }
    
    //array with mail numbers (sorted by date, newest first)
    $mailNumbers = imap_sort($this->mbox, SORTARRIVAL, 1);
    
    //to decrypt the ullMailLoggedMessage id
    $ullCrypt = ullCrypt::getInstance();
    
    $unprocessableLog = array();
    
    foreach ($mailNumbers as $mailNumber)
    {
      // get the id of the ullMailLogged entry for this message (it is saved in the email header)
      $found = preg_match("/X-ull-mail-logged-id:\s(.*)\s/i", imap_body($this->mbox, $mailNumber), $matches);
//      $found = preg_match("/ull-mail-logged-id:\s(.*)\s/i", imap_body($this->mbox, $mailNumber), $matches);
      if (!$found)
      {
        $unprocessableLog[] = 'No mail_log_id found in message: ' . $mailNumber;
        $this->imapMoveToUnproccessable($mailNumber);

        // skip the rest of processing
        continue;
      }
      
      
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

        $unprocessableLog[] = 'Failed to decrypt mail_log_id in message: ' . $mailNumber;
        $this->imapMoveToUnproccessable($mailNumber);

        
        // skip the rest of processing
        continue;
      }
      
      
      // check if the $ullMailLoggedMessage is valid and process it
      $ullMailLoggedMessage = Doctrine::getTable('UllMailLoggedMessage')->findOneById($ullMailLoggedMessageId);
      
      if (!$ullMailLoggedMessage)
      {
        $unprocessableLog[] = 'Invalid mail_log_id: ' . $ullMailLoggedMessageId;
        $this->imapMoveToUnproccessable($mailNumber);
        
        // skip the rest of processing
        continue;
      }
      
      
      // check if the the log entry is not already marked as failed (should not happen)
      if (!$options['skip-failed-check'] && $ullMailLoggedMessage->failed_at)
      {
        $unprocessableLog[] = 'Message already marked as failed: ' . $ullMailLoggedMessageId;
        $this->imapMoveToUnproccessable($mailNumber);
        
        // skip the rest of processing
        continue;
      }        
      
      if (!$this->isDryRun())
      {
        //saves the date of receiving the "undeliverable message"
        $header = imap_headerinfo($this->mbox, $mailNumber);
        $ullMailLoggedMessage->failed_at = date('Y-m-d H:i:s', $header->udate);
        $body = imap_body($this->mbox, $mailNumber);
        $ullMailLoggedMessage->last_error_message = $body;
        $ullMailLoggedMessage->UllMailError = $this->guessUllMailError($body);
        $ullMailLoggedMessage->save();
        
        // Move to processed
        imap_mail_move($this->mbox, $mailNumber, sfConfig::get('app_ull_mail_bounce_handled_folder', 'INBOX.processed'));
      }
      
      // extract email address
      preg_match("/<(.*)>/i", $ullMailLoggedMessage->to_list, $matches);
      // and remember it
      $bouncedEmailAddresses[] = $matches[1];
      
    } // end of foreach email in inbox
    
    $this->logNoisySectionIf(
      $unprocessableLog,
      $this->name, 
      "Unprocessable emails: \n " . implode("\n", $unprocessableLog),
      999999      
    );
    
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
        
        $userBounces[$user->getEmailTo()] = $user->num_email_bounces;
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
    
    if (count($userEmails))
    {
      $resetUsers = array();
      
      //uniquify the array
      $userEmails = array_values(array_unique($userEmails));
      
      foreach ($userEmails as $userEmail)
      {
        // Get the latest mail from the log for the current user
        $ullMailLoggedMessage = UllMailLoggedMessageTable::findLatestLogByEmail($userEmail);
        
        // Check if the latest mail was bounced
        if ($ullMailLoggedMessage && (! $ullMailLoggedMessage->failed_at))
        {
          //get email address from ullMailLoggedMessage
          // KU: why??? we already know the emailaddress!
          //preg_match("/<(.*)>/i", $ullMailLoggedMessage->to_list, $matches);
//          $toResetUsers = Doctrine::getTable('UllUser')->findByEmail($matches[1]);
          
          $toResetUsers = Doctrine::getTable('UllUser')->findByEmail($userEmail);
          
          foreach ($toResetUsers as $toResetUser)
          {
            $toResetUser->num_email_bounces = 0;
            if (!$this->isDryRun())
            {
              $toResetUser->save();
            }
            
            $resetUsers[] = $toResetUser->getEmailTo();
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
      
      $user->setLogEntry('Removed email "%email%" because of %number% undeliverable errors (Bounce handling).',
        array('%email%' => $user->email, '%number%' => $user->num_email_bounces),
        'ullMailMessages'
      );
      $user->num_email_bounces = 0;
      $user->email = '';
        
      if (!$this->isDryRun())
      {
        $user->save();
      }
      
    }
    
    return $userList;
  }
  
  
  /**
   * Helper to move unprocessable emails into the correct folder
   * 
   * @param integer $mailNumber
   */
  public function imapMoveToUnproccessable($mailNumber)
  {
    if (!$this->isDryRun())
    {
      imap_mail_move(
        $this->mbox, 
        $mailNumber, 
        sfConfig::get('app_ull_mail_bounce_unprocessable_folder', 'INBOX.unprocessable')
      );
    }    
  }
  
  
  /**
   * Try to categorize mail errors
   * 
   * @param string $body
   */
  public function guessUllMailError($body)
  {
    $dictionary = array(
      'out-of-office' => array(
        'out of office',
        'out of the office',
        'abwesenheitsnotiz',
        'Abwesenheitsmitteilung',
        'automated',
        'auto-reply',
        'auto:',
      ),
      'over-quota' => array(
        'over quota',
        'quota exceeded',
      ),
      'user-unknown' => array(
        'user unknown',
        'user is unknown',
        'unknown user',
        'existiert nicht',
        'does not exist',
        'requested action not taken',
        'mailbox not found',
        'mailbox unknown',
        'not found',
        'unknown recipient',
        'no such recipient',
        'invalid address',
        'Invalid recipient',
        'recipient address rejected',
        'recipients failed',
        'this user doesn\'t have a',
        'no mailbox here by that name',
        'action: failed',
      ),
      'invalid-domain' => array(
        'unrouteable address',
        'connection refused',
        'non-existent hosts',
        'couldn\'t find any host named',
        'no SMTP service',
      ),
    );
    
    $result = 'unknown';
    
    foreach ($dictionary as $errorSlug => $terms)
    {
      foreach ($terms as $term)
      {
        if (stristr($body, $term))
        {
          $result = $errorSlug;
          break 2;
        }
      }
    }
    
    return Doctrine::getTable('UllMailError')->findOneBySlug($result);
  }
  
  
}