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
    
    This task usually is invoked by a frequently run cronjob which should run 
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
    
    $editions = UllNewsletterEditionTable::findEditionsToBeSpooled();
    
    if (!count($editions))
    {
      $this->log('No editions found to be spooled!');
    }
    
    foreach ($editions as $edition)
    {
      $this->logBlock('Now spooling ' . $edition['subject'], 'INFO');
      
      $context->getUser()->setCulture($edition['sender_culture']);
      
      $user = $edition->Sender;
      $mail = $edition->createMailMessage($user);
      
      $recipients = $edition->findRecipients();
      
      //TODO: allow to give an array of UllUsers
      //TODO: add handling for multiple UllUsers for batchSend
      $numSent = 0;
      
      $edition['queued_at'] = date('Y-m-d H:i:s');
      $edition->save();
      
      foreach ($recipients as $recipient)
      {
        $this->log('...for ' . $recipient['first_name'] . ' ' . $recipient['last_name']);
        
        $currentMail = clone $mail;
        $currentMail->clearRecipients(); // TODO: why is this necessary despite cloning?
        $currentMail->addAddress($recipient);
        
        $this->getMailer()->sendQueue($currentMail);
        
        $numSent++;
      }  
      
      $this->logBlock("Queued {$numSent} of " . count($recipients) . ' messages', 'INFO');
    }      
    
    
  }
  
}