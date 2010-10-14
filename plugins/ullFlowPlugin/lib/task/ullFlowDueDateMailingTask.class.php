<?php

class DueDateMailingTask extends ullBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'ull_flow';
    $this->name             = 'due_date-mailing';
    $this->briefDescription = 'Sends mails regarding due date expiration';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] sends mail to users regarding tickets
    which have expired due dates or are in danger of expiration
    
    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
    
    This task usually is invoked by a (daily) cronjob
EOF;

    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'dev');
  }


  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection($this->name, 'Initializing database connection');
    $configuration = ProjectConfiguration::getApplicationConfiguration(
    $arguments['application'], $arguments['env'], true);
    $databaseManager = new sfDatabaseManager($configuration);

    sfContext::createInstance($configuration);
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('ull', 'I18N'));

    //read reminder period from config, set to 2 if invalid
    $reminderDays = (int)sfConfig::get('app_ullFlowDoc_due_date_reminder_period', 2);
    
    if ($reminderDays < 0)
    {
      $reminderDays = 2;
    }
  
    $ownerReminderMailCount = 0;
    $creatorExpirationMailCount = 0;
    $ownerExpirationMailCount = 0;

    //send reminder mails
    //only send them if the functionality is enabled, i.e. not 0
    if ($reminderDays > 0)
    {
      $this->logSection('dueDate-mailing', "Reminder period is $reminderDays days");
      $expiringDocs = UllFlowDocTable::findExpiringDueDateDocs($reminderDays);
      $this->logSection('ullFlowDocs', 'Found ' . count($expiringDocs) . ' docs in reminder period');

      foreach ($expiringDocs as $doc)
      {
        //send reminder mail to ticket owner
        if (!$doc['owner_due_reminder_sent'] === true)
        {
          $mail = new ullFlowMailDueDateReminderOwner($doc, null, $doc['due_date']);
          $mail->send();
          $doc['owner_due_reminder_sent'] = true;
          $doc->save();
          $ownerReminderMailCount++;
        }
      }
    }
    else
    {
      $this->logSection('dueDate-mailing', 'Reminder period is zero, not sending reminder mails');
    }

    $expiredDocs = UllFlowDocTable::findExpiredDueDateDocs();
    $this->logSection('ullFlowDocs', 'Found ' . count($expiredDocs) . ' expired docs');
    
    //send expiration mails
    foreach ($expiredDocs as $doc)
    {
      //send mail to creator (checks for "creator = user" case itself)
      //if not done already
      if (!$doc['creator_due_expiration_sent'] === true)
      {
        $mail = new ullFlowMailDueDateExpiredCreator($doc);
        $mail->send();
        $doc['creator_due_expiration_sent'] = true;
        $doc->save();
        $creatorExpirationMailCount++;
      }

      //do the same for the ticket owner
      if (!$doc['owner_due_expiration_sent'] === true)
      {
        $mail = new ullFlowMailDueDateExpiredOwner($doc);
        $mail->send();
        $doc['owner_due_expiration_sent'] = true;
        $doc->save();
        $ownerExpirationMailCount++;
      }
    }
    
    $this->logSection('Summary', "Sent $ownerReminderMailCount reminder mails to owners");
    $this->logSection('Summary', "Sent $ownerExpirationMailCount expiration mails to owners");
    $this->logSection('Summary', "Sent $creatorExpirationMailCount expiration mails to creators");
  }
}