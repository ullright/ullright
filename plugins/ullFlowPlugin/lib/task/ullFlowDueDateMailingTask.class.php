<?php

class DueDateMailingTask extends ullBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'ull_flow';
    $this->name             = 'due_date-mailing';
    $this->briefDescription = 'Sends mails regarding due dates (reminders and overdue notifications)';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] sends mail to users regarding tickets
    which are overdue or are in danger of due date expiration.
    
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
    
    ullCoreTools::fixRoutingForAbsoluteUrls();
    
    //read default culture from config and set it
    //so that mails are sent out in the correct language
    //TODO: Mails should decide for themselves based on recipient's set language
    $defaultCulture = sfConfig::get('sf_default_culture', 'en');
    sfContext::getInstance()->getUser()->setCulture($defaultCulture);
    //sfContext::getInstance()->getUser()->setCulture('de');
    
    //read reminder period from config, set to 2 if invalid
    $reminderDays = (int)sfConfig::get('app_ull_flow_due_date_reminder_period', 2);
    
    if ($reminderDays < 0)
    {
      $reminderDays = 2;
    }
  
    $ownerReminderMailCount = 0;
    $ownerOverdueMailCount = 0;
    
    $overdueDocs = UllFlowDocTable::findOverdueDocs(true);
    $this->logSection('ullFlowDocs', 'Found ' . count($overdueDocs) . ' active overdue docs');
    
    //send overdue notification mails
    foreach ($overdueDocs as $doc)
    {
      //send mail to owner (creator receives a CC)
      if (!$doc['owner_due_expiration_sent'] === true)
      {
        $mail = new ullFlowMailDueDateOverdueOwnerAndCreator($doc);
        $mail->send();
        $doc['owner_due_expiration_sent'] = true;
        $doc->save();
        $ownerOverdueMailCount++;
      }
    }
    
    //send reminder mails
    //only send them if the functionality is enabled, i.e. not 0
    if ($reminderDays > 0)
    {
      $this->logSection('dueDate-mailing', "Reminder period is $reminderDays days");
      $dangerDocs = UllFlowDocTable::findDueDateDangerDocs($reminderDays, true);
      $this->logSection('ullFlowDocs', 'Found ' . count($dangerDocs) . ' active docs in reminder period');

      foreach ($dangerDocs as $doc)
      {
        //send reminder mail to ticket owner
        if (!$doc['owner_due_reminder_sent'] === true)
        {
          if ($doc['owner_due_expiration_sent'] === false)
          {
            $mail = new ullFlowMailDueDateReminderOwner($doc, null, $doc['due_date']);
            $mail->send();
            $doc['owner_due_reminder_sent'] = true;
            $doc->save();
            $ownerReminderMailCount++;
          }
          else
          {
            //if an overdue notification has been sent an additional reminder mail
            //makes no sense (most likely cause is that this task was not executed
            //regularly)
            $doc['owner_due_reminder_sent'] = true;
            $doc->save();
            
            $this->logSection('dueDate-mailing', 'No reminder mail for id: ' . $doc['id'] .
              ' - overdue mail has already been sent');
          }
        }
      }
    }
    else
    {
      $this->logSection('dueDate-mailing', 'Reminder period is zero, not sending reminder mails');
    }
    
    $this->logSection('Summary', "Sent $ownerReminderMailCount reminder mails to owners");
    $this->logSection('Summary', "Sent $ownerOverdueMailCount overdue mails to owners (and creators)");
  }
}