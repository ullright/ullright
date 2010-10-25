<?php

/**
 * ullMail actions.
 *
 * @package    ullright
 * @subpackage ullMail
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllMailActions extends BaseUllGeneratorActions
{
  
  public function executeSendNewsletterTest(sfWebRequest $request)
  {
    $message = Swift_Message::newInstance()
      ->setFrom('null@ull.at')
      ->setTo(array('k@ull.at', 'klemens@ull.at', 'null@ull.at', 'office@ull.at'))
      ->setSubject('Subject')
      ->setBody('Body')
    ;
    
//    var_dump(get_class($this->getMailer()));die;
     
    $this->ok = $this->getMailer()->batchSendQueue($message);    
    
  }  
  
  public function executeFlushQueue(sfWebRequest $request)
  {
    
    $spool = $this->getMailer()->getSpool();
    
//    $mailsPerMinute = $spool->getMailsPerMinute();
//    
//    $refreshIntervalSeconds = 3;
//    
//    $numOfMailsToSend =  $mailsPerMinute / 60 * $refreshIntervalSeconds;
    
//    $spool->setMessageLimit($options['message-limit']);
    $spool->setTimeLimit(3);
    $spool->setMessageLimit(100);

    $this->num_sent = $this->getMailer()->flushQueue();
    
    $this->num_unsent = UllMailQueuedMessageTable::countUnsentMessages();
    
    
//    if ($this->num_unsent);
//    {
//      $this->redirect('ullMail/flushQueue');
//    }
    
  }

}