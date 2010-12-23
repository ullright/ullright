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
    $this->checkPermission('ull_newsletter_edit');
    
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
    $this->checkPermission('ull_newsletter_edit');
    
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
  
  public function executeDebugQueue(sfWebRequest $request)
  {
    $this->checkPermission('ull_newsletter_edit');
    
    $q = new Doctrine_Query();
    $q->from('UllMailQueuedMessage')->limit(25);
    $mails = $q->execute();
    
    $this->setVar('mails', $mails, true);
  }
  
  public function executeBreedSubscribers(sfWebRequest $request)
  {
    $this->checkPermission('ull_newsletter_edit');
    
    $list = Doctrine::getTable('UllNewsletterMailingList')->findOneByName('Product news');
    
//    var_dump($list->toArray());die;
    
    for ($i = 1; $i < 2000; $i++)
    {
      $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();

      $q = "INSERT INTO ull_entity (type, first_name, last_name, email, created_at, updated_at) VALUES
        ('user', 'First name {$i}', 'Last name {$i}', '{$i}@example.com', NOW(), NOW())";
      $dbh->exec($q);
      
      $id = $dbh->lastInsertId();
      
      $q = "INSERT INTO ull_newsletter_mailing_list_subscriber (ull_user_id, ull_newsletter_mailing_list_id) VALUES
      ( {$id}, {$list['id']} )";
      $dbh->exec($q);
      
//      $user = new UllUser;
//      $user['first_name'] = 'First name ' . $i;
//      $user['last_name'] = 'Last name ' . $i;
//      $user['email'] = $i . '@example.com';
//      $user->save();
//      
//      $subscription = new UllNewsletterMailingListSubscriber;
//      $subscription['ull_user_id'] = $user['id'];
//      $subscription['ull_newsletter_mailing_list_id'] = $list['id'];
//      $subscription->save();
    }
    
    return sfView::NONE;
  }  

}