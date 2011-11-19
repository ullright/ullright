<?php

/**
 * This class is a plugin for Swift Mailer which saves
 * an UllMailLoggedMessage for every mail.
 * 
 * How it works: This plugin acts before and after sending
 * is performed. Before sending, a status flag is set to
 * false (it is null by default); it is set to true after 
 * sending.
 * 
 * To catch modifications done by previous plugins (e.g.
 * the RedirectingPlugin, which modifies headers), this
 * plugin has to be at the end of the filter chain, just
 * before the Swift_Plugins_ullDisableMailingPlugin, if
 * it is enabled.
 */
class Swift_Plugins_ullAuditPlugin
  extends Swift_Plugins_ullPrioritizedPlugin
  implements Swift_Events_SendListener
{ 
  protected $mailsInTransfer;
  
  /**
   * Constructor
   */
  public function __construct()
  {
    $this->mailsInTransfer = array();
  }
  
  
  /**
   * @param Swift_Events_SendEvent $evt
   */
  public function beforeSendPerformed(Swift_Events_SendEvent $evt)
  {
    $mail = $evt->getMessage();
    
    //parse the message object and create an UllMailLoggedMessage instance
    $loggedMessage = UllMailLoggedMessage::fromSwiftMessage($mail);
    
    //set the transport status flag
    $loggedMessage['transport_sent_status'] = false;
    
    if ($mail instanceof ullsfMail && $mail->getRecipientUllUserId())
    {
      $loggedMessage['main_recipient_ull_user_id'] = $mail->getRecipientUllUserId();
    }
    else
    {
      //see if we can resolve the main recipient to an UllUser
      $recipientAddresses = $mail->getHeaders()->get('to')->getAddresses();
      if (count($recipientAddresses) == 1)
      {
        $q = new Doctrine_Query();
        $q->from('UllUser')
          ->select('id')
          ->where('email = ?', $recipientAddresses[0])
          ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
        ;
        
        $recipientId = $q->execute();
        if (count($recipientId) == 1)
        {
          $loggedMessage['main_recipient_ull_user_id'] = $recipientId[0]['id']; 
        }
      }
    }
    
    $loggedMessage->save();
    
    
    //add encrypted loggedMessage id to mail headers
    $ullCrypt = ullCrypt::getInstance();
    $encryptLoggedMessageId = $ullCrypt->encryptBase64($loggedMessage->id, true);
    $headers = $mail->getHeaders();
    $headers->addTextHeader('ull-mail-logged-id', '' .$encryptLoggedMessageId);
    
    //replace logged message id tag in body
    $mail->setBody(str_replace('_-_LOGGED_MESSAGE_ID_-_',
      $encryptLoggedMessageId, $mail->getBody()));
    
    //we also need to do this for alternative parts
    // TODO: refactor -> better API of ullsfMessage
    foreach ($mail->getChildren() as $child)
    {
      $child->setBody(str_replace('_-_LOGGED_MESSAGE_ID_-_',
      $encryptLoggedMessageId, $child->getBody()));
    }
      
    //store the doctrine record under the unique object hash of the mail message
    //since that is the only thing we'll have in sendPerformed() later on
    //i don't think we can trust the mail id (is it really unique?)
    $this->mailsInTransfer[spl_object_hash($mail)] = $loggedMessage;
  }

  /**
   *
   * @param Swift_Events_SendEvent $evt
   */
  public function sendPerformed(Swift_Events_SendEvent $evt)
  { 
    $mail = $evt->getMessage();
    
    if (isset($this->mailsInTransfer[spl_object_hash($mail)]))
    {
      $loggedMessage = $this->mailsInTransfer[spl_object_hash($mail)];

      //set the transport status flag
      $loggedMessage['transport_sent_status'] = true;
      
      //set the 'sent' timestamp
      $loggedMessage['sent_at'] = date('Y-m-d H:i:s');
      
      $loggedMessage->save();
      unset($this->mailsInTransfer[spl_object_hash($mail)]);
    }
  }
}


